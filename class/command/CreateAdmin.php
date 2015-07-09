<?php

class CreateAdmin
{
	public function addAdmin($user, $dept)
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "INSERT INTO intern_admin (id, username, department_id)
				VALUES (nextval('intern_admin_seq'), :user, :dept)";
	
		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('user'=>$user, 'dept'=>$dept));
	}
}

?>