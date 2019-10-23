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
            if($_REQUEST['domestic']='true'){
                $Main = $_REQUEST['main'];
                $State = $_REQUEST['location'];
                $sql = "SELECT id, main_host_id, sub_name, sub_condition FROM intern_sub_host WHERE main_host_id = :main AND state = :state ORDER BY sub_name ASC";
                $arr = array('main' => $Main, 'state'=>$State);
            } else if($_REQUEST['domestic']='false'){
                $Main = $_REQUEST['main'];
                $Country = $_REQUEST['location'];
                $sql = "SELECT id, main_host_id, sub_name, sub_condition FROM intern_sub_host WHERE main_host_id = :main AND country = :country ORDER BY sub_name ASC";
                $arr = array('main' => $Main, 'country'=>$Country);
            }
        } else if(isset($_REQUEST['Conditions'])){
            $sql = "SELECT intern_sub_host.id, sub_name, host_name, admin_message, address, city, state, zip, province, country, other_name, sub_condition, sub_approve_flag, sub_notes
            FROM intern_sub_host JOIN intern_host ON intern_sub_host.main_host_id = intern_host.id JOIN intern_special_host ON intern_sub_host.sub_condition = intern_special_host.id WHERE sub_condition IS NOT NULL ORDER BY sub_name ASC";
        } else{
            $sql = "SELECT intern_sub_host.id, sub_name, host_name, address, city, state, zip, province, country, other_name, sub_condition, sub_approve_flag, sub_notes
            FROM intern_sub_host JOIN intern_host ON intern_sub_host.main_host_id = intern_host.id ORDER BY sub_name ASC";
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
        echo json_encode($Name);
    }

    //Update Host
    public function put() {
        $Id = $_REQUEST['id'];
        $Main = $_REQUEST['main'];
        $Name = $_REQUEST['name'];
        $Address = $_REQUEST['address'];
        $City = $_REQUEST['city'];
        $State = $_REQUEST['state'];
        $Zip = $_REQUEST['zip'];
        $Province = $_REQUEST['province'];
        $Country = $_REQUEST['country'];
        $OtherName = $_REQUEST['other'];
        $Condition = $_REQUEST['condition'];
        $Date = $_REQUEST['dates'];
        $Flag = $_REQUEST['flag'];
        $Notes = $_REQUEST['notes'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "UPDATE intern_sub_host
                SET main_host_id=:main, sub_name=:name, address=:address, city=:city,
                state=:state, zip=:zip, province=:province, country=:country,
                other_name=:otherName, sub_condition=:condition,
                sub_condition_date=:dates, sub_approve_flag=:flag, sub_notes=:notes
                WHERE id=:id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id'=>$Id, 'main'=>$main, 'name'=>$Name, 'address'=>$Address,
                    'city'=>$City, 'state'=>$State, 'zip'=>$Zip,
                    'province'=>$Province, 'country'=>$Country,
                    'otherName'=>$OtherName, 'condition'=>$Condition,'dates'=>$Date,
                    'flag'=>$Flag, 'notes'=>$notes));

    }
}
