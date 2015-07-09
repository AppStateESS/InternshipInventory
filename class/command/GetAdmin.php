<?php
	
class GetAdmin
{

	public function getData()
	{
		//Call method here
		$data = $this->getAdminData();
		echo (json_encode($data));
		exit;
	}

	public function getAdminData()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT intern_admin.username,
					   intern_admin.id,
					   intern_department.name,
					   users.display_name
				FROM intern_admin
				INNER JOIN intern_department
				ON 	 intern_admin.department_id = intern_department.id
				INNER JOIN users
				ON intern_admin.username = users.username";
		
		$sth = $pdo->prepare($sql);
		
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

}
?>