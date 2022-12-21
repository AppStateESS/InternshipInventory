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

class AgreementTypeRest {

	public function execute()
	{
		/* Check if user should have access to affiliation data*/
		if(!\Current_User::isLogged()){
			\NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to affiliation agreement.');
			throw new \Intern\Exception\PermissionException('You do not have permission to files affiliation agreement.');
		}

		switch($_SERVER['REQUEST_METHOD']) {
			case 'PUT':
				$this->put();
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

	public function get()
	{
		$id = $_REQUEST['internId'];

		$db = Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT contract_type, affiliation_agreement_id
				FROM intern_internship
				WHERE id=:id";

		$sth = $pdo->prepare($sql);

		$sth->execute(array('id'=>$id));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		if(empty($result)){
			$result = array();
		}
		return $result;
	}

	public function put()
	{
		$id = $_REQUEST['internId'];

		$db = Database::newDB();
		$pdo = $db->getPDO();

		if(isset($_REQUEST['affilId'])){
			$affilNum = $_REQUEST['affilId'];
			if($affilNum == '-1'){
				$affilNum = NULL;
			}

			$sql = "UPDATE intern_internship
					SET affiliation_agreement_id=:affil
					WHERE id=:id";

			$sth = $pdo->prepare($sql);
			$sth->execute(array('affil'=>$affilNum, 'id'=>$id));

		} elseif(isset($_REQUEST['agreeType'])){
			$type = $_REQUEST['agreeType'];

			$sql = "UPDATE intern_internship
					SET contract_type=:value
					WHERE id=:id";

			$sth = $pdo->prepare($sql);
			$sth->execute(array('value'=>$type, 'id'=>$id));
		}

	}

}
