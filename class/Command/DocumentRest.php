<?php

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\DatabaseStorage;

class DocumentRest {

	public function execute()
	{
		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'GET':
            	$data = $this->get();
				echo (json_encode($data));
				exit;
			default:
	            header('HTTP/1.1 405 Method Not Allowed');
	            exit;
			}
	}

	public function post(){
		var_dump($_FILES);
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$internId = $_REQUEST['internship_id'];

		$sql = "INSERT INTO intern_document (id, internship_id, document_fc_id)
				VALUES (nextval('intern_document_seq'), :id, nextval('documents_seq'))";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id' => $internId));
		postFc($params);
	}

	public function postFc($data){
		if($_FILES['event_image']['size'] > 0 and $_FILES['event_image']['size'] < 2097152)
		{
			$tempFile = $_FILES['event_image']['tmp_name'];
			$targetPath = PHPWS_SOURCE_DIR . "mod/events/images/";
			$targetFile =  $targetPath. $_FILES['event_image']['name'];
			$image_url = "mod/events/images/" . $_FILES['event_image']['name'];
			move_uploaded_file($tempFile, $targetFile);
		}
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "INSERT INTO documents (id, file_name, file_directory, folder_id, file_type, title, description, size, downloaded)
				VALUES (nextval('documents_seq'), :fileName)";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('fileName' => $name));
	}

}
