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

use \Intern\PdoFactory;
use \Intern\SubHostFactory;
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

        if(!isset($_REQUEST['Waiting']) && !isset($_REQUEST['Condition'])){
            $sql = "SELECT id, host_name, host_condition, host_approve_flag FROM intern_host WHERE host_approve_flag = 2 ORDER BY host_name ASC";
        } else if(isset($_REQUEST['Condition'])){
            $sql = "SELECT intern_host.id, host_name, host_condition, host_approve_flag, host_condition_date, host_notes, admin_message, intern_special_host.id AS con_id
            FROM intern_host LEFT JOIN intern_special_host ON intern_host.host_condition = intern_special_host.id ORDER BY host_name ASC";
        } else if(isset($_REQUEST['Waiting'])){
            if($_REQUEST['Waiting']){
                $sql = "SELECT id, host_name, host_condition, host_approve_flag FROM intern_host WHERE host_approve_flag != 0 ORDER BY host_name ASC";
            } else{
                $sql = "SELECT id, host_name, host_condition, host_approve_flag FROM intern_host ORDER BY host_name ASC";
            }
        }
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }

    public function notDenied(){
        $db = \phpws2\Database::newDB();
        $pdo = $db->getPDO();
        $sql = "SELECT id, host_name, host_condition, host_approve_flag FROM intern_host WHERE host_approve_flag != 0 ORDER BY host_name ASC";
        $sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }

    //New Host
    public function post() {
        $Name = $_REQUEST['name'];
        $db = PdoFactory::getPdoInstance();

        if(isset($_REQUEST['admin'])){
            $Condition = $_REQUEST['condition'];
            $ConDate = $_REQUEST['date'];
            $Flag = $_REQUEST['flag'];
            $Notes= $_REQUEST['notes'];
            $sql = "INSERT INTO intern_host (id, host_name, host_condition,
                    host_condition_date, host_approve_flag, host_notes)
                    VALUES (nextval('intern_host_seq'), :name, :condition, :conDate, :flag, :notes)";
            $arr = array('name'=>$Name, 'condition'=>$Condition,
                        'conDate'=>$ConDate, 'flag'=>$Flag, 'notes'=>$Notes);
        } else{
            $sql = "INSERT INTO intern_host (id, host_name)
                    VALUES (nextval('intern_host_seq'), :name)";
            $arr = array('name'=>$Name);
        }

        $sth = $db->prepare($sql);
        $sth->execute($arr);
        echo json_encode($this->notDenied());
    }

    //Update Host
    public function put() {
        $postarray = json_decode(file_get_contents('php://input'));
        $Id = $postarray->id;

        $db = Database::newDB();
        $pdo = $db->getPDO();

        if(isset($_REQUEST['HostCon'])){
            $Name = $postarray->name;
            $Condition = $postarray->condition;
            if($Condition == '-1'){$Condition = null;}
            $ConDate = $postarray->date;
            $Flag = $postarray->flag;
            $Notes = $postarray->notes;
            $sql = "UPDATE intern_host
                    SET host_name=:name, host_condition=:condition,
                    host_condition_date=:conDate, host_approve_flag=:flag, host_notes=:notes
                    WHERE id=:id";

            $sth = $pdo->prepare($sql);
            $sth->execute(array('id'=>$Id, 'name'=>$Name, 'condition'=>$Condition,
                        'conDate'=>$ConDate, 'flag'=>$Flag, 'notes'=>$Notes));
            echo json_encode('Success');
            return;
        }

        if(!isset($postarray->old)){
            $Name = $postarray->name;
            $Condition = $postarray->condition;
            if($Condition == '-1'){$Condition = null;}
            $ConDate = $postarray->date;
            $Flag = $postarray->flag;
            $sql = "UPDATE intern_host
                    SET host_name=:name, host_condition=:condition,
                    host_condition_date=:conDate, host_approve_flag=:flag
                    WHERE id=:id";

            $sth = $pdo->prepare($sql);
            $sth->execute(array('id'=>$Id, 'name'=>$Name, 'condition'=>$Condition,
                        'conDate'=>$ConDate, 'flag'=>$Flag));

        //change host everywhere if switching
        } else{
            $oldId = $postarray->old;

            $sql = "UPDATE intern_host
                    SET host_approve_flag=0
                    WHERE id=:old";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('old'=>$oldId));

            $sql = "UPDATE intern_internship
                SET host_id = :id
                WHERE host_id=:old";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('id'=>$Id, 'old'=>$oldId));

            $sql = "UPDATE intern_sub_host
                SET main_host_id = :id
                WHERE main_host_id=:old";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('id'=>$Id, 'old'=>$oldId));
        }
        //if the host is changed to one that's denied, then change the internship state to denied
        if(!isset($_REQUEST['HostCon'])){
            $sql = "UPDATE intern_internship
                SET state = 'DeniedState'
                FROM intern_host, intern_special_host
                WHERE intern_internship.host_id = intern_host.id and intern_host.host_condition = intern_special_host.id
                and intern_internship.host_id=:id and intern_special_host.stop_level='Stop'";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('id'=>$Id));
        }
        echo json_encode($this->get());
    }
}
