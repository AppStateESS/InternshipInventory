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

class InternshipRest {

    public function execute() {
        /* Check if user should have access to Host page */
        if(!\Current_User::isLogged()){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to view records.');
            throw new \Intern\Exception\PermissionException('You do not have permission to view records.');
        }

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }
    public function get() {
        $db = \phpws2\Database::newDB();
		$pdo = $db->getPDO();
        $arr = array();

        $banner = $_REQUEST['banner'];
        $term = $_REQUEST['term'];
        $sql = "SELECT id FROM intern_internship WHERE banner = :banner AND term = :term AND multi_part != 1 AND state !='RegisteredState'";
        $arr = array('banner' => $banner, 'term'=>$term);

        $sth = $pdo->prepare($sql);
		$sth->execute($arr);
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if(sizeof($result) <= 0){
            return false;
        }

		return true;
    }
}
