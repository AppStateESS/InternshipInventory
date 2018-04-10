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

namespace Intern\DataProvider\Student;

use Intern\PdoFactory;
use Intern\Student;
use Intern\AcademicMajor;

use Intern\Exception\StudentNotFoundException;

class LocalDbStudentDataProvider extends StudentDataProvider {

    // Student level: grad, undergrad
    // TODO: update these for arbitrary level handling
    const UNDERGRAD = 'U';
    const GRADUATE  = 'G';
    const GRADUATE2 = 'G2';
    const DOCTORAL  = 'D';
    const POSTDOC   = 'P'; // Guessing at the name here, not sure what 'P' really is

    public function getStudent($studentId){
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM intern_local_student_data WHERE student_id = :studentId';

        $stmt = $db->prepare($query);
        $stmt->execute(array('studentId' => $studentId));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result === false){
            throw new StudentNotFoundException('Could not find student in local data with id: ' . $studentId);
        }

        $student = new Student();
        $this->plugValues($student, $result);

        return $student;
    }

    protected function plugValues(&$student, Array $data)
    {
        /**********************
         * Basic Demographics *
         **********************/
        $student->setStudentId($data['student_id']);
        $student->setUsername($data['user_name']);

        $student->setFirstName($data['first_name']);
        $student->setMiddleName($data['middle_name']);
        $student->setLastName($data['last_name']);
        $student->setPreferredName($data['preferred_name']);
        $student->setBirthDateFromString($data['birth_date']);
        $student->setGender($data['gender']);

        if($data['confidential'] === 'Y'){
            $student->setConfidentialFlag(true);
        } else {
            $student->setConfidentialFlag(false);
        }

        // Person type flags
        // TODO - only works for students right now
        $student->setStudentFlag(true);
        $student->setStaffFlag(false);

        /*****************
         * Academic Info *
         *****************/
        // Campus
        if($data['campus'] === 'main_campus'){
            $student->setCampus(Student::MAIN_CAMPUS);
        } else if ($data['campus'] != ''){
            $student->setCampus($data['campus']);
        }

        // Level (grad vs undergrad)
        // TODO: Merge this with changes for variable levels
        if($data['level'] == self::UNDERGRAD) {
            $student->setLevel(self::UNDERGRAD);
        } else if ($data['level'] == self::GRADUATE) {
            $student->setLevel(self::GRADUATE);
        } else if ($data['level'] == self::GRADUATE2) {
            $student->setLevel(self::GRADUATE2);
        } else if ($data['level'] == self::DOCTORAL) {
            $student->setLevel(self::DOCTORAL);
        } else if ($data['level'] == self::POSTDOC) {
            $student->setLevel(self::POSTDOC);
        } else {
            $student->setLevel(null);
        }

        // Credit Hours
        //$student->setCreditHours($data['credit_hours']);

        // Majors - Only one allowed here (this differs from WebServiceDataProvider)
        // code and description fields must both be not null and not empty string to add a major
        if($data['major_code'] !== null && $data['major_code'] !== ''
            && $data['major_description'] !== null && $data['major_description'] !== '') {
            $student->addMajor(new AcademicMajor($data['major_code'], $data['major_description'], $data['level']));
        }

        $student->setGpa(round($data['gpa'], 4));

        // Grad date, if available
        if($data['grad_date'] !== null && $data['grad_date'] != '') {
            $student->setGradDateFromString($data['grad_date']);
        }

        // Contact Info
        $student->setPhone($data['phone']);

        // Address info
        $student->setAddress($data['address']);
        $student->setAddress2($data['address2']);
        $student->setCity($data['city']);
        $student->setState($data['state']);
        $student->setZip($data['zip']);
    }

    public function getCreditHours(string $studentId, string $term)
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM intern_local_student_data WHERE student_id = :studentId';

        $stmt = $db->prepare($query);
        $stmt->execute(array('studentId' => $studentId));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result === false){
            throw new StudentNotFoundException('Could not find student in local data with id: ' . $studentId);
        }

        return $result['credit_hours'];
    }

    public function getFacultyMember($facultyId)
    {

    }
}
