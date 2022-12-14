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

class SubRest {

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
        $arr = array();

        if(isset($_REQUEST['domestic'])){
            if($_REQUEST['domestic']=='true'){
                $Main = $_REQUEST['main'];
                $State = $_REQUEST['location'];
                $sql = "SELECT id, main_host_id, sub_name, sub_condition FROM intern_sub_host WHERE main_host_id = :main AND state = :state AND sub_approve_flag != 0 ORDER BY sub_name ASC";
                $arr = array('main' => $Main, 'state'=>$State);
            } elseif($_REQUEST['domestic']=='false'){
                $Main = $_REQUEST['main'];
                $Country = $_REQUEST['location'];
                $sql = "SELECT id, main_host_id, sub_name, sub_condition FROM intern_sub_host WHERE main_host_id = :main AND country = :country AND sub_approve_flag != 0 ORDER BY sub_name ASC";
                $arr = array('main' => $Main, 'country'=>$Country);
            }
        } elseif(isset($_REQUEST['Conditions'])){
            $sql = "SELECT intern_sub_host.id, sub_name, host_name, admin_message, address, city, state, zip, province, country, other_name, sub_condition, sub_approve_flag, sub_notes, intern_special_host.id AS con_id
            FROM intern_sub_host JOIN intern_host ON intern_sub_host.main_host_id = intern_host.id JOIN intern_special_host ON intern_sub_host.sub_condition = intern_special_host.id WHERE sub_condition IS NOT NULL ORDER BY sub_name ASC";
        } elseif(isset($_REQUEST['internId'])){
            $id = $_REQUEST['internId'];
            $sql = "SELECT host_id, host_sub_id FROM intern_internship WHERE id = $id";
        } elseif(isset($_REQUEST['change'])){
            $id = $_REQUEST['change'];
            $sql = "SELECT id, main_host_id, sub_name, sub_condition FROM intern_sub_host WHERE main_host_id = $id";
        } else{
            $sql = "SELECT intern_sub_host.id, main_host_id, sub_name, host_name, address, city, state, zip, province, country, other_name, sub_condition, sub_approve_flag, sub_notes, intern_special_host.id AS con_id
            FROM intern_sub_host JOIN intern_host ON intern_sub_host.main_host_id = intern_host.id LEFT JOIN intern_special_host ON intern_sub_host.sub_condition = intern_special_host.id ORDER BY sub_name ASC";
        }
		$sth = $pdo->prepare($sql);
		$sth->execute($arr);
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }

    //New Host
    public function post() {
        $Main = $_REQUEST['main'];
        $Name = $_REQUEST['name'];
        $Address = $_REQUEST['address'];
        $City = $_REQUEST['city'];
        $State = $_REQUEST['state'];
        $Zip = $_REQUEST['zip'];
        $Province = $_REQUEST['province'];
        $Country = $_REQUEST['country'];
        $OtherName = $_REQUEST['other_name'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        if(isset($_REQUEST['admin'])){
            $Condition = $_REQUEST['condition'];
            $Date = $_REQUEST['dates'];
            $Flag = $_REQUEST['flag'];
            $Notes = $_REQUEST['notes'];

            $sql = "INSERT INTO intern_sub_host
                    VALUES (nextval('intern_sub_host_seq'), :main, :name, :address, :city, :state, :zip, :province,
                    :country, :otherName, :condition, :dates, :flag, :notes)";
            $arr = array('main'=>$Main, 'name'=>$Name, 'address'=>$Address,
                        'city'=>$City, 'state'=>$State, 'zip'=>$Zip,
                        'province'=>$Province, 'country'=>$Country,
                        'otherName'=>$OtherName, 'condition'=>$Condition,
                        'dates'=>$Date, 'flag'=>$Flag, 'notes'=>$notes);
        } else{
            $sql = "INSERT INTO intern_sub_host
                    VALUES (nextval('intern_sub_host_seq'), :main, :name, :address, :city, :state, :zip, :province,
                    :country, :otherName)";
            $arr = array('main'=>$Main, 'name'=>$Name, 'address'=>$Address,
                        'city'=>$City, 'state'=>$State, 'zip'=>$Zip,
                        'province'=>$Province, 'country'=>$Country,
                        'otherName'=>$OtherName);
        }
        $sth = $pdo->prepare($sql);
        $sth->execute($arr);
        echo json_encode("Success");
    }

    //Update Host
    public function put() {
        $postarray = json_decode(file_get_contents('php://input'));

        $Id = $postarray->id;
        $Name = $postarray->name;
        $Address = $postarray->address;
        $City = $postarray->city;
        $State = $postarray->state;
        $Zip = $postarray->zip;
        $Province = $postarray->province;
        $Country = $postarray->country;
        $OtherName = $postarray->other;
        $Condition = $postarray->condition;
        if ($Condition == '' || $Condition == '-1') {$Condition = null;}
        $Flag = $postarray->flag;

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "UPDATE intern_sub_host
                SET sub_name=:name, address=:address, city=:city,
                state=:state, zip=:zip, province=:province, country=:country,
                other_name=:otherName, sub_condition=:condition,
                sub_approve_flag=:flag
                WHERE id=:id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id'=>$Id, 'name'=>$Name, 'address'=>$Address,
                    'city'=>$City, 'state'=>$State, 'zip'=>$Zip,
                    'province'=>$Province, 'country'=>$Country,
                    'otherName'=>$OtherName, 'condition'=>$Condition,
                    'flag'=>$Flag));
        echo json_encode("Success");
    }
}
