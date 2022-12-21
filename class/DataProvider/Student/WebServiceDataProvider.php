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

use Intern\Student;
use Intern\AcademicMajor;
use Intern\LevelFactory;
use Intern\DataProvider\Curl;

/**
 * WebServiceDataProvider
 *
 * Returns a Student object with data pulled from a web service connected to Banner.
 *
 * @author Jeremy Booker
 * @package Intern
 */
class WebServiceDataProvider extends StudentDataProvider {

    protected $currentUserName;

    private $apiKey;

    // Campus: main campus, distance ed
    const MAIN_CAMPUS = 'Main';

    /**
    * @param string $currentUserName - Username of the user currently logged in. Will be sent to web service
    */
    public function __construct($currentUserName){
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $this->apiKey = \PHPWS_Settings::get('intern', 'wsdlUri');
        //$this->client = new \SoapClient($wsdlUri, array('WSDL_CACHE_MEMORY'));
    }

    /**
     * Returns a Student object with hard-coded data
     * @return \Intern\Student
     */
    public function getStudent($studentId){
        if($studentId === null || $studentId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        //See if current user has permission to access data
        if(!\Current_User::isLogged()){
            throw new \BannerPermissionException('You do not have permission to access student data.');
        }

        $params = array('BannerID' => $studentId, 'UserName' => $this->currentUserName);

        $url = 'https://sawarehouse.ess.appstate.edu/api/intern/student/' . $studentId . '?username=intern&api_token=' . $this->apiKey;

        $curl = new Curl();
        $curl->setUrl($url);
        $result = json_decode($curl->exec());
        $curl->close();

        // Check for an empty response
        if($result === null || (isset($result->message) && $result->message == "No Results Found")) {
            throw new \Intern\Exception\StudentNotFoundException("Could not locate student: $studentId");
        }

        // Response may have multiple records (faculty/staff + student), so
        // just take the first one
        // TODO: Maybe be smarter about which result we use?
        if(is_array($result)){
            $result = $result[0];
        }

        // Log the request
        $this->logRequest('getStudent', 'success', $params);

        // Create the Student object and plugin the values, Full check for missing data
        try {
            $student = new Student();
            $this->plugStudentValues($student, $result);
        }
        catch(\Exception $e) {
            throw new \Intern\Exception\StudentNotFoundException("Missing student data: $studentId");
        }
        return $student;
    }

    protected function sendRequest(Array $params){
        return $this->client->GetInternInfo($params);
    }

    public function getCreditHours(string $studentId, string $term){
        if($studentId === null || $studentId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        if($term === null || $term == ''){
            throw new \InvalidArgumentException('Missing student term.');
        }

        //See if current user has permission to access data
        if(!\Current_User::isLogged()){
            throw new \BannerPermissionException('You do not have permission to access student data.');
        }

        $params = array('BannerID' => $studentId, 'Term' => $term, 'UserName' => $this->currentUserName);

        $url = 'https://sawarehouse.ess.appstate.edu/api/intern/student/' . $studentId . '/' . $term . '?username=intern&api_token=' . $this->apiKey;

        $curl = new Curl();
        $curl->setUrl($url);
        $result = json_decode($curl->exec());
        $curl->close();

        // Log the request
        $this->logRequest('getCreditHours', 'success', $params);

        if(isset($result->GetCreditHoursResult)){
            return $result->GetCreditHoursResult;
        }else{
            return null;
        }
    }

    public function getFacultyMember($facultyId){
        if($facultyId === null || $facultyId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        //See if current user has permission to access data
        if(!\Current_User::isLogged()){
            throw new \BannerPermissionException('You do not have permission to access student data.');
        }

        $params = array('BannerID' => $facultyId, 'UserName' => $this->currentUserName);

        $url = 'https://sawarehouse.ess.appstate.edu/api/intern/employee/' . $facultyId . '?username=intern&api_token=' . $this->apiKey;

        $curl = new Curl();
        $curl->setUrl($url);
        $result = json_decode($curl->exec());
        $curl->close();

        // Check for an empty response
        if($result === null || (isset($result->message) && $result->message == "No Results Found")) {
            throw new \Intern\Exception\StudentNotFoundException("Could not locate faculty member with id: $facultyId");
        }

        // Check for an arry of results
        if(is_array($result)){
            $result = $result[0];
        }

        $facultyRes = $this->plugfacultyValues($result);

        return $facultyRes;
    }

    /**
    * Takes a reference to a Student object and a curl response,
    * Plugs the curl values into Student object.
    *
    * @param Student $student
    * @param stdClass $data
    */
    protected function plugStudentValues(&$student, \stdClass $data){
        /**********************
        * Basic Demographics *
        **********************/
        $student->setStudentId($data->bannerID);
        $student->setUsername($data->userName);
        $student->setFirstName($data->firstName);
        if(isset($data->middleName)){
            $student->setMiddleName($data->middleName);
        }
        $student->setLastName($data->lastName);
        if(isset($data->preferredName)){
            $student->setPreferredName($data->preferredName);
        }

        if(isset($data->confidential) && $data->confidential === 'N') {
            $student->setConfidentialFlag(false);
        } else {
            $student->setConfidentialFlag(true);
        }

        // Person type flags
        if(isset($data->isStudent) && $data->isStudent == 1){
            $student->setStudentFlag(true);
        } else {
            $student->setStudentFlag(false);
        }

        if(isset($data->isStaff) && $data->isStaff == 1){
            $student->setStaffFlag(true);
        } else {
            $student->setStaffFlag(false);
        }

        /*****************
        * Academic Info *
        *****************/

        // Campus
        if(isset($data->campusDescription) && $data->campusDescription == WebServiceDataProvider::MAIN_CAMPUS) {
            // If campus is 'Main Campus', then we know it's a main campus student
            $student->setCampus(Student::MAIN_CAMPUS);
        } elseif (isset($data->campusDescription) && $data->campusDescription != '') {
            // If the campus is set, but is not 'Main Campus', then we know it's some other campus name (e.g. "Catawba EdD EdLead")
            // We're not going to check for every possible campus name; as long as there's *something* there, we'll assume it's distance ed
            $student->setCampus(Student::DISTANCE_ED);
        } else {
            // If the campus isn't set, then defalt to main campus
            $student->setCampus(Student::MAIN_CAMPUS);
            //\NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "Campus not found for this student in banner so Main Campus was initially selected.");
            //\NQ::close();
        }

        // Check if level exist, if not add it
        if(isset($data->studentLevel) && LevelFactory::checkLevelExist($data->studentLevel)){
            $student->setLevel($data->studentLevel);
        } elseif(isset($data->studentLevel)) {
            $newLevel = LevelFactory::saveNewCode($data->studentLevel);
            $student->setLevel($newLevel);
        }

        // Majors - Can be an array of objects, or just a single object, or not set at all
        if(isset($data->majors) && is_array($data->majors)) {
            foreach($data->majors as $major){
                $student->addMajor(new AcademicMajor($major->majorCode, $major->majorDescription, AcademicMajor::LEVEL_UNDERGRAD));
            }
        } elseif(isset($data->majors) &&  is_object($data->majors)){
            $student->addMajor(new AcademicMajor($data->majors->majorCode, $data->majors->majorDescription, AcademicMajor::LEVEL_UNDERGRAD));
        }

        // GPA - Rounded to 4 decimial places
        $student->setGpa(round($data->overallGPA, 4));

        // Grad date, if available
        if(isset($data->gradDate) && $data->gradDate != '') {
            $student->setGradDateFromString($data->gradDate);
        } /*else if(isset($data->gradYear) && $data->gradYear != '') {
            $student->setGradDateFromString($data->gradYear);
        }*/

        // Contact info
        if(isset($data->phoneNumber)){
            $student->setPhone($data->phoneNumber);
        }
    }

    /**
    * Renames fields of a curl response
    *
    * @param stdClass $data
    */
    protected function plugFacultyValues(\stdClass $data) {

        $result = array();
        $result['id'] = $data->bannerID;
        $result['username'] = $data->userName;
        $result['first_name'] = $data->firstName;
        $result['last_name'] = $data->lastName;
        $result['phone'] = $data->phoneNumber;
        //taking only the first address listed
        if(!empty($data->address)){
            $result['street_address1'] = $data->address[0]->street1;
            $result['street_address2'] = $data->address[0]->street2;
            $result['city'] = $data->address[0]->city;
            $result['state'] = $data->address[0]->state;
            $result['zip'] = $data->address[0]->zip;
        }else{
            $result['street_address1'] = '';
            $result['street_address2'] = '';
            $result['city'] = '';
            $result['state'] = '';
            $result['zip'] = '';
        }
        return $result;
    }

    /**
    * Logs this request to PHPWS' curlapi.log file
    */
    private function logRequest($functionName, $result, Array $params) {
        $args = implode(', ', $params);
        $msg = "$functionName($args) result: $result";
        \PHPWS_Core::log($msg, 'curlapi.log', 'CURLAPI');
    }
}
