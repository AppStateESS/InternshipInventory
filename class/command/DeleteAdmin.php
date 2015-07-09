<?php
	
class DeleteAdmin
{
	public function deleteData($id)
	{

		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "DELETE FROM intern_admin
				WHERE id = :id";
	
		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('id'=>$id));
	}
}
?>