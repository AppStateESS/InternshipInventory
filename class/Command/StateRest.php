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
use \phpws2\Database;

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
		$db = Database::newDB();
		$pdo = $db->getPDO();


		$sql = "SELECT abbr, full_name, active FROM intern_state ORDER BY full_name ASC";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

	public function put()
	{
		$db = Database::newDB();
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
