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

use \Intern\DepartmentFactory;
use \phpws2\Database;

/**
 * REST-ful controller for creating/editing faculty to department associations.
 * @author jbooker
 * @package intern
 */
class FacultyDeptRest {

    public function execute()
    {
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'POST':
                $this->post();
                exit;
            case 'DELETE':
                $this->delete();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    private function get()
    {
        $departments = DepartmentFactory::getDepartmentsAssocForUsername(\Current_User::getUsername());

        $props = array();

       foreach ($departments as $id => $val) {
            $props[]=array('id'=>$id, 'name'=>$val);
       }

        return $props;
    }

    private function post()
    {
        $facultyId       = $_REQUEST['faculty_id'];
        $departmentId    = $_REQUEST['department_id'];

        if ($facultyId == '')
        {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a faculty ID.");
            exit;
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        // Check to see if this faculty member exists in this department already
        $sql = "SELECT * FROM intern_faculty_department WHERE faculty_id = :facultyId AND department_id = :departmentId";
        $statement = $pdo->prepare($sql);
        $statement->execute(array('facultyId'=>$facultyId, 'departmentId'=>$departmentId));

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        // If there's any results, then the faculty member is already in this department and we can just quit here
        if(sizeof($result) > 0){
            return;
        }

        // Add the faculty member to the department
        $sql = "INSERT INTO intern_faculty_department VALUES (:facultyId, :departmentId)";
        $sth = $pdo->prepare($sql);

        $sth->execute(array('facultyId'=>$facultyId, 'departmentId'=>$departmentId));
    }

    private function delete()
    {
        // Because we're halfway between an "old way" and a "new way", delete
        // takes input from query instead of JSON.  Beg your pardon but this
        // is the quickest way to get this thing out the door.
        $facultyId       = $_REQUEST['faculty_id'];
        $departmentId    = $_REQUEST['department_id'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "DELETE FROM intern_faculty_department WHERE faculty_id = :facultyId AND department_id = :departmentId";


        $sth = $pdo->prepare($sql);

        $sth->execute(array('facultyId'=>$facultyId, 'departmentId'=>$departmentId));

        exit;
    }
}
