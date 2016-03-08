<?php

class AdminRest {

	public function execute()
	{

		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'DELETE':
                $this->delete();
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
		$user = $_REQUEST['user'];
        $dept = $_REQUEST['dept'];

        if ($user == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a username.");
            exit;
		}

		if ($dept == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Please choose a department.");
            exit;
		}

		if ($dept == '-1')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Please choose a department.");
            exit;
		}

		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT username, id 
		FROM intern_admin
		WHERE username=:user and department_id=:dept";

		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('user'=>$user, 'dept'=>$dept));

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) > 0)
		{

			header('HTTP/1.1 500 Internal Server Error');
			echo("Multiple usernames in the same department.");
            exit;
		}

		//Check to see if the username is real
		$sql = "SELECT username
		FROM users
		WHERE username=:user";

		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('user'=>$user));

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($result) == 0)
		{

			header('HTTP/1.1 500 Internal Server Error');
			echo("Please enter a valid username.");
            exit;
		}

		$sql = "INSERT INTO intern_admin (id, username, department_id)
				VALUES (nextval('intern_admin_seq'), :user, :dept)";
	
		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('user'=>$user, 'dept'=>$dept));
	}

	public function delete()
	{
		$id = $_REQUEST['id'];

		$db = \Database::newDB();
		$pdo = $db->getPDO();


		$sql = "DELETE FROM intern_admin
				WHERE id = :id";
	
		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('id'=>$id));
	}

	public function get()
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