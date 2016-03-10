<?php

namespace Intern\Command;

class GetDepartments
{

	public function getData()
	{
		$data = $this->getDeptData();
		echo (json_encode($data));
		exit;
	}

	public function getDeptData()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT name, id
				FROM intern_department
				ORDER BY name ASC";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

}
