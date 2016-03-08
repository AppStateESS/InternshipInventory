<?php

class MajorRest {

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
			case 'POST':
				$this->post();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
	}

	public function post()
	{
		$major = $_REQUEST['create'];

		if ($major == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing an undergraduate major title.");
            exit;
		}

		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "INSERT INTO intern_major (id, name, hidden)
				VALUES (nextval('intern_major_seq'), :major, :hidden)";

		$sth = $pdo->prepare($sql);
		
		$sth->execute(array('major'=>$major, 'hidden'=>0));

	}
	public function put()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		if(isset($_REQUEST['val']))
		{
			$hVal = $_REQUEST['val'];
			$id = $_REQUEST['id'];
		
			$sql = "UPDATE intern_major
					SET hidden=:val
					WHERE id=:id";
		
			$sth = $pdo->prepare($sql);
			
			$sth->execute(array('val'=>$hVal, 'id'=>$id));
		}
		else if(isset($_REQUEST['name']))
		{
			$mname = $_REQUEST['name'];
			$id = $_REQUEST['id'];
		
			$sql = "UPDATE intern_major
					SET name=:mname
					WHERE id=:id";
		
			$sth = $pdo->prepare($sql);
			
			$sth->execute(array('mname'=>$mname, 'id'=>$id));
		}
	}

	public function get()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT id, name, hidden 
				FROM intern_major
				ORDER BY name ASC";
		
		$sth = $pdo->prepare($sql);
		
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
?>