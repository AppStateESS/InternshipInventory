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

class HostRest {

    public function execute() {
        /* Check if user should have access to Host page */
        if(!\Current_User::isLogged()){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to view Hosts.');
            throw new \Intern\Exception\PermissionException('You do not have permission to view Hosts.');
        }

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'POST':
                $this->post();
                exit;
            case 'PUT':
                $this->put();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    public function get() {
        $db = \phpws2\Database::newDB();
		$pdo = $db->getPDO();

        if(isset($_REQUEST['Waiting'])){
            $sql = "SELECT * FROM intern_host WHERE host_approve_flag = 2 ORDER BY host_name ASC";
        }else{
            $sql = "SELECT * FROM intern_host ORDER BY host_name ASC";
        }
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }

    //New Host
    public function post() {
        $Name = $_REQUEST['name'];
        $Condition = $_REQUEST['condition'];
        $ConDate = $_REQUEST['date'];
        $Flag = $_REQUEST['flag'];
        $Notes= $_REQUEST['notes'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "INSERT INTO intern_host (nextval('intern_host_seq'), host_name, host_condition,
                host_condition_date, host_approve_flag, host_notes)
                VALUES (:name, :condition, :conDate, :flag, :notes)";

        $sth = $pdo->prepare($sql);

        $sth->execute(array('name'=>$Name, 'condition'=>$Condition,
                    'conDate'=>$ConDate, 'flag'=>$Flag, 'notes'=>$Notes));
    }

    //Update Host
    public function put() {
        $Id = $_REQUEST['id'];
        $Name = $_REQUEST['name'];
        $Condition = $_REQUEST['condition'];
        $ConDate = $_REQUEST['date'];
        $Flag = $_REQUEST['flag'];
        $Notes= $_REQUEST['notes'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "UPDATE intern_host
                SET host_name=:Name, host_condition=:Condition, host_condition_date=:conDate,
                host_approve_flag=:Flag, host_notes=:notes
                WHERE id=:id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id'=>$Id, 'name'=>$Name, 'condition'=>$Condition,
                    'conDate'=>$ConDate, 'flag'=>$Flag, 'notes'=>$Notes));

    }
}
