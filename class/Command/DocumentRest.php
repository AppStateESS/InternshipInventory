<?php

namespace Intern\Command;

use \Intern\DatabaseStorage;

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
		$message = '';
		$known_documents = array('csv', 'doc', 'docx', 'odt', 'pdf', 'ppt', 'pptx', 'rtf',
    			'tar', 'tgz', 'txt', 'xls', 'xlsx', 'xml', 'zip', 'gz', 'rar', 'ods', 'odp');
		$id = $_REQUEST['internship_id'];
		$type = $_REQUEST['type'];

		// See what file is to be saved, name it based on internship_id + users file name, ex. 71Document.txt
		if($type == 'other'){
			$target_dir = "/var/www/html/files/documents/otherDocuments/";
		} else{
			$target_dir = "/var/www/html/files/documents/contract/";
		}
		//TODO test if this new name works
		$fileName = $id . $_FILES['file']['name'];
		$target_file = $target_dir.basename($fileName);
		unlink($target_file);exit;

		// Check is the file type is one of the accepted ones
		$fileType = pathinfo($target_file, PATHINFO_EXTENSION);
		for($i = 0; $i < sizeof($known_documents); $i++){
			if($fileType == $known_documents[$i]){
				$message = '';
				break;
			}
			$message = 'Did not save. File not a known type.';
		}

		if(file_exists($target_file)){
			$message = 'Did not save. That file name already exist.';
		}

		if(!is_writable($target_dir)){
			$message = 'File failed to save.';
		}

		$size = $_FILES['file']['size'];
		if($size > 2000000){
			$message = "Did not save. The file size was greater than 2MB";
		}

		if($message == null){
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
				// Link here to save file in database
				saveFile($fileName, $target_file, $id);
				return;
			} else{
				return 'Sorry, error uploading your file.';
			}
		} else{
			return $message;
		}

	}

	public function saveFile($name, $path, $id){
		//save in database
	}

	public function deleteFile(){
		$id = $_REQUEST['internship_id'];
		$target_dir = "/var/www/html/files/filecabinet/folder1/";
		$target_file = $target_dir.basename($id . $_FILES['file']['name']);
		//unlink($target_file);exit;
		if(file_exists($target_file)){
			$message = 'File exist.';
			$uploadPass = false;
		}

	}

	public function get(){
		$id = $_REQUEST['internship_id'];
		//look for every entry for docs that match the id
	}

}
