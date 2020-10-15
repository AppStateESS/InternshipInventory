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

use \Intern\DataProvider\Student\StudentDataProviderFactory;

use \phpws2\Database;

class FacultyFactory {

    public static function getFacultyById($id)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "SELECT intern_faculty.* FROM intern_faculty WHERE intern_faculty.id = :id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id' => $id));

        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        // If no results from database, try to lookup the faculty member in Banner
        if(!$result){
            $provider = StudentDataProviderFactory::getProvider();
            $result = $provider->getFacultyMember($id);
        }

        return $result;
    }

    public static function getFacultyObjectById($id)
    {

        if(!isset($id)) {
            throw new \InvalidArgumentException('Missing faculty id.');
        }

        $sql = "SELECT intern_faculty.* FROM intern_faculty WHERE intern_faculty.id = {$id}";

        $row = \PHPWS_DB::getRow($sql);

        if (\PHPWS_Error::logIfError($row)) {
            throw new Exception($row);
        }

        $faculty = new FacultyDB();

        $faculty->setId($row['id']);
        $faculty->setUsername($row['username']);
        $faculty->setFirstName($row['first_name']);
        $faculty->setLastName($row['last_name']);
        $faculty->setPhone($row['phone']);
        $faculty->setFax($row['fax']);
        $faculty->setStreetAddress1($row['street_address1']);
        $faculty->setStreetAddress2($row['street_address2']);
        $faculty->setCity($row['city']);
        $faculty->setState($row['state']);
        $faculty->setZip($row['zip']);

        return $faculty;
    }

    /**
     * Returns an array of Faculty objects for the given department.
     * @param Department $department
     * @return Array List of faculty for requested department.
     */
    public static function getFacultyByDepartmentAssoc(Department $department)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "SELECT intern_faculty.* FROM intern_faculty JOIN intern_faculty_department ON intern_faculty.id = intern_faculty_department.faculty_id WHERE intern_faculty_department.department_id = :departmentId ORDER BY last_name ASC";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('departmentId' => $department->getId()));

        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
