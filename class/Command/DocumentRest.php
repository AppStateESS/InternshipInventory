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

		$type = $_REQUEST['type'];
		// See where file is to be saved
		if($type == 'other'){
			$target_dir = "/var/www/html/files/documents/otherDocuments/";
		} else{
			$target_dir = "/var/www/html/files/documents/contract/";
		}

		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $data = $this->post($target_dir);
				echo (json_encode($data));
                exit;
            case 'GET':
            	$data = $this->get($target_dir);
				echo (json_encode($data));
				exit;
			case 'DELETE':
				$this->deleteFile($target_dir);
				exit();
			default:
	            header('HTTP/1.1 405 Method Not Allowed');
	            exit;
			}
	}

	public function post($target_dir){
		$data = array('message' => '');
		$known_documents = array('csv', 'doc', 'docx', 'odt', 'pdf', 'ppt', 'pptx', 'rtf',
    			'tar', 'tgz', 'txt', 'xls', 'xlsx', 'xml', 'zip', 'gz', 'rar', 'ods', 'odp');
		$id = $_REQUEST['internship_id'];

		// Name based on internship_id + users file name, ex. 71Document.txt
		$name = $_FILES['file']['name'];
		$fileName = $id . $name;
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

		$size = $_FILES['file']['size'];
		if($size > 2000000){
			$data['message'] = "Did not save. The file size was greater than 2MB";
		}

		if($data['message'] == null){
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file) && $this->saveFile($id, $name, $fileName, $target_file, $type)){
				$data['name'] = $fileName;
			} else{
				$data['message'] = 'Sorry, there was an error uploading your file.';
			}
		}
		return $data;
	}

	public function saveFile($id, $name, $fileName, $target_file, $type){
		//save in database
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "INSERT INTO intern_contract_documents (id, internship_id, name, store_name, path_name, type)
				VALUES (nextval('intern_contract_documents_seq'), :internId, :name, :store, :pathN, :type)";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('internId'=>$id, 'name'=>$name, 'store'=>$fileName, 'pathN'=>$target_file, 'type'=>$type));
	}

	public function deleteFile($target_dir){
		$id = $_REQUEST['internship_id'];
		$name = $_REQUEST['name'];
		// From folder
		$target_file = $target_dir.basename($name);
		unlink($target_file);

		// From database
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "DELETE FROM intern_contract_documents
				WHERE store_name=:name";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('name'=>$name));
	}

	public function get($target_dir){
		$id = $_REQUEST['internship_id'];
		// If name is set then we're getting the download link
		if(isset($_REQUEST['name'])){
			$name = $_REQUEST['name'];
			$target_file = $target_dir.basename($name);
			return $target_file;
		}
		// If type is contract get the contract, else get all the otherDocuments
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT name, store_name
				FROM intern_contract_documents
				WHERE internship_id=:id AND type=:type
				ORDER BY name ASC";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$id, 'type'=>$type));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

}
