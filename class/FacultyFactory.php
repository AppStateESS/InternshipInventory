<?php

namespace Intern;

class FacultyFactory {

    public static function getFacultyById($id)
    {
        $sql = "SELECT intern_faculty.* FROM intern_faculty WHERE intern_faculty.id = {$id}";

        $row = PHPWS_DB::getRow($sql);
         
        /*
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

        */
         
        return $row;
    }

    public static function getFacultyObjectById($id)
    {

        if(!isset($id)) {
            throw new InvalidArgumentException('Missing faculty id.');
        }

        $sql = "SELECT intern_faculty.* FROM intern_faculty WHERE intern_faculty.id = {$id}";
         
        $row = PHPWS_DB::getRow($sql);

        if (PHPWS_Error::logIfError($row)) {
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
        $sql = "SELECT intern_faculty.* FROM intern_faculty JOIN intern_faculty_department ON intern_faculty.id = intern_faculty_department.faculty_id WHERE intern_faculty_department.department_id = {$department->getId()} ORDER BY last_name ASC";
         
        $result = PHPWS_DB::getAll($sql);

        return $result;
    }
}
