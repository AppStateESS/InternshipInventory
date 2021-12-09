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
use Intern\AcademicMajor;
use Intern\Student;

/**
 * TestStudentProvider - Always returns student objects with hard-coded testing data
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TestWebServiceDataProvider extends WebServiceDataProvider {

    public function __construct($currentUserName) {
        $this->currentUserName = $currentUserName;
    }

    public function getStudent($studentId){
        return $this->getFakeResponse();
    }

    public function getCreditHours(string $studentId, string $term){
        return 16;
    }

    public function getFacultyMember($facultyId){
        $response = $this->getFakeResponse();

        return $response->GetInternInfoResult->DirectoryInfo;
    }

    private function getFakeResponse(){
        $obj = new Student();;

        // ID & email
        $obj->setStudentId('900123456');
        $obj->setUsername('yosef');

        // Person type
        $obj->setStaffFlag(false);
        $obj->setStudentFlag(true);

        // Basic demographics
        $obj->setFirstName('Yosef');
        $obj->setMiddleName('Awesome');
        $obj->setLastName('Jr');
        $obj->setPreferredName('Joe Awesome');

        // Phone number
        $obj->setPhone('828 123 4567');

        // Academic Info
        $obj->setLevel('U');   // 'U' or 'G'
        //$obj->setLevel('G2');
        $obj->setCampus(Student::MAIN_CAMPUS);
        $obj->setGpa(round('3.8129032260', 4));
        //$obj->gpa       = '1.8129032260';

        //$obj->setGradDateFromString('');
        //$obj->grad_date = '12/23/2015'; // Can be empty, or format 12/12/2015 (MM/DD/YYYY)

        // Majors - Can be an arry of objects, or a single object
        $obj->addMajor(new AcademicMajor('355*','Management', AcademicMajor::LEVEL_UNDERGRAD));
        $obj->addMajor(new AcademicMajor('219A', 'Computer Science', AcademicMajor::LEVEL_UNDERGRAD));

        // Confidential flag
        //$obj->setConfidentialFlag('N');

        return $obj;
    }

    private function getFakeErrorResponse(){
        $obj = new \stdClass();

        $obj->banner_id = '900123456';

        // User does not have Banner permissions
        //$obj->error_num = 1002;
        //$obj->error_desc = 'InvalidUserName';

        // Web service had an unknown error
        //$obj->error_num = 1;
        //$obj->error_desc = 'SYSTEM';

        // Student was not found
        $obj->error_num = 1101;
        $obj->error_desc = 'LookupBannerID';

        // No data?
        //$obj = new \stdClass();

        $parentObj = new \stdClass();
        $parentObj->GetInternInfoResult = new \stdClass();
        $parentObj->GetInternInfoResult->DirectoryInfo = $obj;
        return $parentObj;
    }
}
