<?php

namespace Intern\Command;
use \phpws2\Database;

class DocumentRest {

	public function execute()
	{
		/* Check if user should have access to folders */
		if(!\Current_User::isLogged()){
			\NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to files.');
			throw new \Intern\Exception\PermissionException('You do not have permission to files.');
		}

		// Makes sure the folder of documents, contract, and otherDocuments are made
		if(!file_exists("/var/www/html/files/documents")){
			mkdir("/var/www/html/files/documents");
		}
		if(!file_exists("/var/www/html/files/documents/contract")){
			mkdir("/var/www/html/files/documents/contract");
		}
		if(!file_exists("/var/www/html/files/documents/otherDocuments")){
			mkdir("/var/www/html/files/documents/otherDocuments");
		}

		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $data = $this->post();
				echo (json_encode($data));
                exit;
            case 'GET':
            	$data = $this->get();
				echo (json_encode($data));
				exit;
			case 'DELETE':
				$this->deleteFile();
				exit();
			default:
	            header('HTTP/1.1 405 Method Not Allowed');
	            exit;
			}
	}

	public function post(){
		$data = array('message' => '');
		$known_documents = array('csv', 'doc', 'docx', 'odt', 'pdf', 'ppt', 'pptx', 'rtf',
    			'tar', 'tgz', 'txt', 'xls', 'xlsx', 'xml', 'zip', 'gz', 'rar', 'ods', 'odp');
		$id = $_REQUEST['internship_id'];

		// Name based on internship_id + users file name, ex. 71Document.txt
		$key = $_REQUEST['key'];
		$fileLongType = $_FILES[$key]['type'];
		$name = $_FILES[$key]['name'];
		$fileName = $id . $name;
		$type = $_REQUEST['type'];
		// See where file is to be saved
		if($type == 'other'){
			$target_dir = "/var/www/html/files/documents/otherDocuments/";
		} else{
			$target_dir = "/var/www/html/files/documents/contract/";
		}
		$target_file = $target_dir.basename($fileName);
		// Check is the file type is one of the accepted ones
		$fileType = pathinfo($target_file, PATHINFO_EXTENSION);
		for($i = 0; $i < sizeof($known_documents); $i++){
			if($fileType == $known_documents[$i]){
				$data['message'] = '';
				break;
			}
			$data['message'] = 'Did not save. File not a known type.';
		}

		if(file_exists($target_file)){
			$data['message'] = 'Did not save. That file name already exist. Please rename the file.';
		}

		if(!is_writable($target_dir)){
			$data['message'] = 'Did not save. File is not writable.';
		}

		$size = $_FILES[$key]['size'];
		if($size > 2000000){
			$data['message'] = "Did not save. The file size was greater than 2MB";
		}

		if($data['message'] == null){
			if(move_uploaded_file($_FILES[$key]['tmp_name'], $target_file)){
				$this->saveFile($id, $name, $fileName, $target_file, $type, $fileLongType);
				$data['id'] = $this->getIdByName($fileName, $type);
				$data['name'] = $name;
			} else{
				$data['message'] = 'Sorry, there was an error uploading your file.';
			}
		}
		return $data;
	}

	public function saveFile($id, $name, $fileName, $target_file, $type, $fileLongType){
		//save in database
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "INSERT INTO intern_contract_documents (id, internship_id, name, store_name, path_name, type, file_type)
				VALUES (nextval('intern_contract_documents_seq'), :internId, :name, :store, :pathN, :type, :fileT)";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('internId'=>$id, 'name'=>$name, 'store'=>$fileName, 'pathN'=>$target_file, 'type'=>$type, 'fileT'=>$fileLongType));
	}

	public function deleteFile(){
		$id = $_REQUEST['internship_id'];
		$docId = $_REQUEST['docId'];

		// From folder
		$target_file = $this->getPath($docId);
		unlink($target_file);

		// From database
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "DELETE FROM intern_contract_documents
				WHERE id=:id";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$docId));
	}

	public function get(){
		$id = $_REQUEST['internship_id'];
		$type = $_REQUEST['type'];
		// If name is set then we're getting the download link
		if(isset($_REQUEST['docId'])){
			$docId = $_REQUEST['docId'];
			$this->getDownLoad($docId);
			return;
		}
		// If type is contract get the contract, else get all the otherDocuments
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT id, name
				FROM intern_contract_documents
				WHERE internship_id=:id AND type=:type
				ORDER BY name ASC";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$id, 'type'=>$type));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

	public function getDownLoad($docId){
		$target_file = $this->getPath($docId);
		if(!file_exists($target_file)){
			\NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'The file could not be found to download.');
			throw new \Intern\Exception\WebServiceException('The file could not be found to download.');
		}
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT file_type
				FROM intern_contract_documents
				WHERE id=:id";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$docId));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		$file = file_get_contents($target_file);
		header('Content-type: ' . $result[0]['file_type']);
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Length: '.strlen($file));
		header('Content-Disposition: inline; filename="'. basename($target_file) .'";');

		echo $file;

		exit;
	}

	public function getPath($docId){
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "SELECT path_name
				FROM intern_contract_documents
				WHERE id=:id";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$docId));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		return $result[0]['path_name'];
	}

	public function getIdByName($name, $type){
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "SELECT id
				FROM intern_contract_documents
				WHERE store_name=:name AND type=:type";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('name'=>$name, 'type'=>$type));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		return $result[0]['id'];
	}

}
