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

class ConditionRest {

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
        $sql = "SELECT * FROM intern_special_host ORDER BY admin_message ASC";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }

    //New Condition
    public function post() {
        $Admin = $_REQUEST['admin'];
        $User = $_REQUEST['user'];
        $Stop = $_REQUEST['stop'];
        $Sup = $_REQUEST['sup'];
        $Email = $_REQUEST['email'];
        $Notes = $_REQUEST['notes'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "INSERT INTO intern_special_host
                VALUES (nextval('intern_special_host_seq'), :admin, :user, :stop, :sup, :email, :notes)";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('admin'=>$Admin, 'user'=>$User, 'stop'=>$Stop,
                    'sup'=>$Sup, 'email'=>$Email, 'notes'=>$Notes));
    }

    //Update Condition
    public function put() {
        $postarray = json_decode(file_get_contents('php://input'));
        $Id = $postarray->id;
        $Admin = $postarray->admin;
        $User = $postarray->user;
        $Stop = $postarray->stop;
        $Sup = $postarray->sup;
        $Email = $postarray->email;
        $Notes = $postarray->notes;

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "UPDATE intern_special_host
                SET admin_message=:admin, user_message=:user, stop_level=:stop,
                sup_check=:sup, email=:email, special_notes=:notes
                WHERE id=:id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id'=>$Id, 'admin'=>$Admin, 'user'=>$User, 'stop'=>$Stop,
                    'sup'=>$Sup, 'email'=>$Email, 'notes'=>$Notes));
        echo json_encode($this->get());
    }
}
