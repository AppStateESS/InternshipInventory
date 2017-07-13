<?php

namespace Intern\Command;
use \phpws2\Database;

class LevelRest {

	public function execute()
	{
		switch($_SERVER['REQUEST_METHOD']) {
			case 'PUT':
			$this->put();
			exit;
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

	// Update code
	public function put()
	{
		$cod = $_REQUEST['code'];
		$descri = $_REQUEST['descr'];
		$lev = $_REQUEST['level'];

		if ($lev == ''){
			header('HTTP/1.1 500 Internal Server Error');
			echo("Edit was missing a level. No changes saved.");
			exit;
		}

		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "UPDATE intern_student_level
		SET level=:lev, description=:descri
		WHERE code=:cod";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('cod'=>$cod, 'descri'=>$descri, 'lev'=>$lev));
	}

	// New code
	public function post()
	{
		$cod = $_REQUEST['code'];
		$descri = $_REQUEST['descr'];
		$lev = $_REQUEST['level'];

		if ($cod == ''){
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a code.");
			exit;
		}

		if ($lev == ''){
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a level.");
			exit;
		}
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT code
		FROM intern_student_level
		WHERE code=:cod";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('cod'=>$cod));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		if (sizeof($result) > 0){
			header('HTTP/1.1 500 Internal Server Error');
			echo("Code already exist.");
			exit;
		}

		$sql = "INSERT INTO intern_student_level (code, description, level)
		VALUES (:cod, :descri, :lev)";
		$sth = $pdo->prepare($sql);
		$sth->execute(array('cod'=>$cod, 'descri'=>$descri, 'lev'=>$lev));
	}

	// Get code information
	public function get()
	{
		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT *
		FROM intern_student_level
		ORDER BY code ASC";

		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}
}
