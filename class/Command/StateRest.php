<?php

namespace Intern\Command;

class StateRest {

	public function execute()
	{

		switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
            	$data = $this->get();
				echo (json_encode($data));
				exit;
			case 'PUT':
				$this->put();
				exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
	}

	public function get()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();


		$sql = "SELECT abbr, full_name, active FROM intern_state ORDER BY full_name ASC";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function put()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		if (!isset($_REQUEST['remove']))
		{
			$abbr = $_REQUEST['abbr'];

			$sql = "UPDATE intern_state
					SET active=1
					WHERE abbr=:abbr";

			$sth = $pdo->prepare($sql);

			$sth->execute(array('abbr'=>$abbr));
		}
		else
		{
			$abbr = $_REQUEST['abbr'];

			$sql = "UPDATE intern_state
					SET active=0
					WHERE abbr=:abbr";

			$sth = $pdo->prepare($sql);

			$sth->execute(array('abbr'=>$abbr));
		}


	}
}
