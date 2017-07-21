<?php

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\DatabaseStorage;

class DocumentRest {

	public function execute()
	{
		switch($_SERVER['REQUEST_METHOD']) {
            case 'PUT':
                $this->put();
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

}
