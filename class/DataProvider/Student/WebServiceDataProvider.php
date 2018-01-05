<?php

namespace Intern\DataProvider\Student;

use Intern\Student;
use Intern\AcademicMajor;

use \SoapFault;

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

    private $client;

    // Campus: main campus, distance ed
    const MAIN_CAMPUS = 'Main Campus';

    // Student level: grad, undergrad
    const UNDERGRAD = 'U';
    const GRADUATE  = 'G';
    const GRADUATE2 = 'G2';
    const DOCTORAL  = 'D';
    const POSTDOC   = 'P'; // Guessing at the name here, not sure what 'P' really is

    /**
     * @param string $currentUserName - Username of the user currently logged in. Will be sent to web service
     */
    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $wsdlUri = \PHPWS_Settings::get('intern', 'wsdlUri');

        // Create the SOAP instance
        $this->client = new \SoapClient($wsdlUri, array('WSDL_CACHE_MEMORY'));
    }

    /**
     * Returns a Student object with hard-coded data
     * @return \Intern\Student
     */
    public function getStudent($studentId)
    {
        if($studentId === null || $studentId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        $params = array('BannerID' => $studentId,
                        'UserName' => $this->currentUserName);

        try {
            $response = $this->sendRequest($params);
        } catch (SoapFault $e){
            throw $e;
        }

        // Check for an empty response
        if(isset($response->GetInternInfoResult->DirectoryInfo)) {
            $response = $response->GetInternInfoResult->DirectoryInfo;
        } else {
            throw new \Intern\Exception\StudentNotFoundException("Could not locate student: $studentId");
        }

        // Response may have multiple records (faculty/staff + student), so
        // just take the first one
        // TODO: Maybe be smarter about which result we use?
        if(is_array($response)){
            $response = $response[0];
        }

        // Check for an InvalidUsername error (i.e. the user doesn't have banner permissions)
        if($response->error_num == 1002 && $response->error_desc == 'InvalidUserName'){
            throw new \Intern\Exception\BannerPermissionException("No banner permissions for {$this->currentUserName}");
        }

        // Check for a web service system error
        if($response->error_num == 1 && $response->error_desc == 'SYSTEM'){
            throw new \Intern\Exception\WebServiceException("Web service system error while looking up {$studentId}");
        }

        if($response->error_num == 1101 && $response->error_desc == 'LookupBannerID'){
            throw new \Intern\Exception\StudentNotFoundException("Invalid banner id: {$studentId}");
        }

        if($response->error_num == 1001 && $response->error_desc == 'InvalidBannerID'){
            throw new \Intern\Exception\StudentNotFoundException("Invalid banner id: {$studentId}");

        }

        // Log the request
        $this->logRequest('getStudent', 'success', $params);

        // Removed built-in credit-hour fetching because we don't always have a term (but still need to lookup a student)
        //$response->creditHours = $this->getCreditHours($studentId, $term);

        // Create the Student object and plugin the values
        $student = new Student();
        $this->plugValues($student, $response);

        return $student;
    }

    protected function sendRequest(Array $params)
    {
        return $this->client->GetInternInfo($params);
    }

    public function getCreditHours(string $studentId, string $term)
    {
        if($studentId === null || $studentId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        if($term === null || $term == ''){
            throw new \InvalidArgumentException('Missing student term.');
        }

        $params = array('BannerID'  => $studentId,
                        'Term'      => $term,
                        'UserName'  => $this->currentUserName);

        try {
            $response = $this->client->GetCreditHours($params);
        } catch (SoapFault $e){
            throw $e;
        }

        // Log the request
        $this->logRequest('getCreditHours', 'success', $params);

        if(isset($response->GetCreditHoursResult)){
            return $response->GetCreditHoursResult;
        }else{
            return null;
        }
    }

    public function getFacultyMember($facultyId)
    {
        if($facultyId === null || $facultyId == ''){
            throw new \InvalidArgumentException('Missing student ID.');
        }

        $params = array('BannerID' => $facultyId,
                        'UserName' => $this->currentUserName);

        try {
            $response = $this->client->getInternInfo($params);
        } catch (SoapFault $e){
            throw $e;
        }

        // Check for an empty response
        if(isset($response->GetInternInfoResult->DirectoryInfo)) {
            $response = $response->GetInternInfoResult->DirectoryInfo;
        } else {
            throw new \Intern\Exception\StudentNotFoundException("Could not locate faculty member with id: $facultyId");
        }

        // Check for an arry of results
        if(is_array($response)){
            $response = $response[0];
        }

        // Check for an InvalidUsername error (i.e. the user doesn't have banner permissions)
        if($response->error_num == 1002 && $response->error_desc == 'InvalidUserName'){
            throw new \Intern\Exception\BannerPermissionException("No banner permissions for {$this->currentUserName}");
        }

        // Check for a web service system error
        if($response->error_num == 1 && $response->error_desc == 'SYSTEM'){
            throw new \Intern\Exception\WebServiceException("Web service system error while looking up {$facultyId}");
        }

        if($response->error_num == 1101 && $response->error_desc == 'LookupBannerID'){
            throw new \Intern\Exception\StudentNotFoundException("Invalid banner id: {$facultyId}");
        }

        if($response->error_num == 1001 && $response->error_desc == 'InvalidBannerID'){
            throw new \Intern\Exception\StudentNotFoundException("Invalid banner id: {$facultyId}");

        }

        if(is_array($response)){
            $response = $response[0];
        }

        return $response;
    }

    /**
     * Takes a reference to a Student object and a SOAP response,
     * Plugs the SOAP values into Student object.
     *
     * @param Student $student
     * @param stdClass $data
     */
    protected function plugValues(&$student, \stdClass $data)
    {
        /**********************
         * Basic Demographics *
         **********************/
        $student->setStudentId($data->banner_id);
        $student->setUsername($data->user_name);

        $student->setFirstName($data->first_name);
        $student->setMiddleName($data->middle_name);
        $student->setLastName($data->last_name);
        $student->setPreferredName($data->preferred_name);
        $student->setBirthDateFromString($data->birth_date);
        $student->setGender($data->gender);

        if($data->confid === 'N') {
            $student->setConfidentialFlag(false);
        } else {
            $student->setConfidentialFlag(true);
        }

        // Person type flags
        if($data->isstudent == 1){
            $student->setStudentFlag(true);
        } else {
            $student->setStudentFlag(false);
        }

        if($data->isstaff == 1){
            $student->setStaffFlag(true);
        } else {
            $student->setStaffFlag(false);
        }

        /*****************
         * Academic Info *
         *****************/

        // Campus
        if($data->campus == WebServiceDataProvider::MAIN_CAMPUS) {
            // If campus is 'Main Campus', then we know it's a main campus student
            $student->setCampus(Student::MAIN_CAMPUS);
        } else if ($data->campus != '') {
            // If the campus is set, but is not 'Main Campus', then we know it's some other campus name (e.g. "Catawba EdD EdLead")
            // We're not going to check for every possible campus name; as long as there's *something* there, we'll assume it's distance ed
            $student->setCampus(Student::DISTANCE_ED);
        } else {
            // If the campus isn't set, then throw an exception
            //throw new \InvalidArgumentException("Unrecognized campus ({$data->campus}) for {$data->banner_id}.");
        }

        // Level (grad vs undergrad)
        if($data->level == self::UNDERGRAD) {
            $student->setLevel(Student::UNDERGRAD);
        } else if ($data->level == self::GRADUATE) {
            $student->setLevel(Student::GRADUATE);
        } else if ($data->level == self::GRADUATE2) {
            $student->setLevel(Student::GRADUATE2);
        } else if ($data->level == self::DOCTORAL) {
            $student->setLevel(Student::DOCTORAL);
        } else if ($data->level == self::POSTDOC) {
            $student->setLevel(Student::POSTDOC);
        } else {
            $student->setLevel(null);
        }

        // Credit Hours
        // Removed built-in credit hour fetching, since we don't always have a term
        //$student->setCreditHours($data->creditHours);

        // Majors - Can be an array of objects, or just a single object, or not set at all
        // TODO: Fix hard-coded 'U' level passed to AcademicMajor
        if(isset($data->majors) && is_array($data->majors)) {
            foreach($data->majors as $major){
                $student->addMajor(new AcademicMajor($major->major_code, $major->major_desc, 'U'));
            }
        } else if(isset($data->majors) &&  is_object($data->majors)){
            $student->addMajor(new AcademicMajor($data->majors->major_code, $data->majors->major_desc, 'U'));
        }

        // GPA - Rounded to 4 decimial places
        $student->setGpa(round($data->gpa, 4));

        // Grad date, if available
        if(isset($data->grad_date) && $data->grad_date != '') {
            $student->setGradDateFromString($data->grad_date);
        }

        // Holds
        // TODO - Find out what these look like

        // Contact info
        $student->setPhone($data->phone);

        // Address info
        $student->setAddress($data->addr1);
        $student->setAddress2($data->addr2);
        $student->setCity($data->city);
        $student->setState($data->state);
        $student->setZip($data->zip);
    }

    /**
     * Logs this request to PHPWS' soap.log file
     */
    private function logRequest($functionName, $result, Array $params)
    {
        $args = implode(', ', $params);
        $msg = "$functionName($args) result: $result";
        \PHPWS_Core::log($msg, 'soap.log', 'SOAP');
    }
}
