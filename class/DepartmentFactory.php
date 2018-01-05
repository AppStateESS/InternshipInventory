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

namespace Intern;
use Intern\Department;

/**
 * Factory for loading Department objects.
 * @author jbooker
 * @package intern
 */
class DepartmentFactory {

    /**
     * Returns a Department object based on the given department ID.
     * @param unknown $id
     * @return Department
     */
    public static function getDepartmentById($id)
    {
        // Sanity checking
        if (!isset($id) || $id === '') {
           throw new \InvalidArgumentException('Missing department ID.');
        }

        // Query
        $query = "SELECT * FROM intern_department WHERE id = $id";
        $result = \PHPWS_DB::getRow($query);

        if (\PHPWS_Error::isError($result)) {
            throw new DatabaseException($result->toString());
        }

        if (sizeof($result) == 0) {
           return null;
        }

        // Create the object and set member variables
        $department = new DepartmentDB();
        $department->setId($result['id']);
        $department->setName($result['name']);
        $department->setHidden($result['hidden']);
        $department->setCorequisite($result['corequisite']);

        return $department;
    }

    /**
     * Return an associative array {id => dept. name} for all the
     * departments in database.
     * @param $except - Always show the department with this ID. Used for internships
     *                  with a hidden department. We still want to see it in  the select box.
     */
    public static function getDepartmentsAssoc($except=null)
    {
        $db = new \PHPWS_DB('intern_department');
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');
        if(!is_null($except)) {
            $db->addWhere('id', $except, '=', 'OR');
        }

        $db->setIndexBy('id');

        return $db->select('col');
    }

    /**
     * Return an associative array {id => dept. name} for all the departments
     * that the user with $username is allowed to see.
     * @param $includeHiddenDept - Include the department with this ID, even if it's hidden. Used for internships
     *                  with a hidden department. We still want to see it in the select box.
     */
    public static function getDepartmentsAssocForUsername($username, $includeHiddenDept = null)
    {
        $db = new \PHPWS_DB('intern_department');
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR', 'grp');

        if(!is_null($includeHiddenDept)){
            $db->addWhere('id', $includeHiddenDept, '=', 'OR', 'grp');
        }

        // If the user doesn't have the 'all_departments' permission,
        // then add a join to limit to specific departments
        if(!\Current_User::allow('intern', 'all_departments') && !\Current_User::isDeity()){
            $db->addJoin('LEFT', 'intern_department', 'intern_admin', 'id', 'department_id');
            $db->addWhere('intern_admin.username', $username);
        }

        $db->setIndexBy('id');

        $depts = array();
        $depts[-1] = 'Select Department';
        $depts += $db->select('col');

        return $depts;
    }
}
