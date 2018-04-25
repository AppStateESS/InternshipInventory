<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern\Command;

use Intern\PdoFactory;

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

    public function get()
    {
        $pdo = PdoFactory::getPdoInstance();

        $sql = "SELECT id, description, hidden
                FROM intern_major
                ORDER BY description ASC";

        $sth = $pdo->prepare($sql);

        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
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

		$pdo = PdoFactory::getPdoInstance();

		$sql = "INSERT INTO intern_major (id, description, hidden)
				VALUES (nextval('intern_major_seq'), :major, :hidden)";

		$sth = $pdo->prepare($sql);

		$sth->execute(array('major'=>$major, 'hidden'=>0));

	}

	public function put()
	{
		$pdo = PdoFactory::getPdoInstance();

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
					SET description=:mname
					WHERE id=:id";

			$sth = $pdo->prepare($sql);

			$sth->execute(array('mname'=>$mname, 'id'=>$id));
		}
	}
}
