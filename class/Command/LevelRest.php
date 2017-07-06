<?php

namespace Intern\Command;
use \phpws2\Database;

class LevelRest {

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

	public function post()
	{
		$code = $_REQUEST['code'];
    $desc = $_REQUEST['desc'];
		$level = $_REQUEST['level'];

    if ($code == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a code.");
      exit;
		}

		if ($level == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a level.");
      exit;
		}

		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT code, description, level
		FROM intern_student_level
		WHERE code=:cod and description=:des and level=:lev";//TODO

		$sth = $pdo->prepare($sql);

		$sth->execute(array('cod'=>$code, 'des'=>$desc, 'lev'=>$level));

		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		if (sizeof($result) > 0)
		{

			header('HTTP/1.1 500 Internal Server Error');
			echo("Multiple codes in use.");
            exit;
		}

		$sql = "INSERT INTO intern_student_level (code, description, level)
				VALUES (nextval('intern_student_level_seq'), :cod, :des, :lev)";

		$sth = $pdo->prepare($sql);

		$sth->execute(array('cod'=>$code, 'des'=>$desc, 'lev'=>$level));
	}

	public function get()
	{
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT intern_student_level.code,
					   intern_student_level.description,
					   intern_student_level.level
				FROM intern_student_level";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}
}
