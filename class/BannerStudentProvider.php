<?php

namespace Intern;

/**
 * BannerStudentProvider
 *
 * Returns a Student object with data pulled from a web service connected to Banner.
 *
 * @author Jeremy Booker
 * @package Intern
 */
class BannerStudentProvider extends StudentProvider {

    private $currentUserName;

    private $soapClient;

    // Campus: main campus, distance ed
    const MAIN_CAMPUS = 'Main Campus';

    // Student level: grad, undergrad
    const UNDERGRAD = 'U';
    const GRADUATE  = 'G';
    // TODO: level 'D'??


    /**
     * @param string $currentUserName - Username of the user currently logged in. Will be sent to web service
     */
    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $wsdlUri = \PHPWS_Settings::get('intern', 'wsdlUri');

        // Create the SOAP instance
        $this->client = new \SoapClient($wsdlUri);
    }

    /**
     * Returns a Student object with hard-coded data
     * @return Student
     */
    public function getStudent($studentId)
    {
        if($studentId === null || $studentId == ''){
            throw new InvalidArgumentException('Missing student ID.');
        }

        $params = array('BannerID' => $studentId,
                        'UserName' => $this->username);

        try {
            $response = $this->client->GetInternInfo($params);
        } catch (SoapFault $e){
            throw $e;
        }

        // Create the Student object and plugin the values
        $student = new Student();
        $this->plugValues($student);

        return $student;
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
        if($data->campus == BannerStudentProvider::MAIN_CAMPUS) {
            // If campus is 'Main Campus', then we know it's a main campus student
            $student->setCampus(Student::MAIN_CAMPUS);
        } else if ($data->campus != '') {
            // If the campus is set, but is not 'Main Campus', then we know it's some other campus name (e.g. "Catawba EdD EdLead")
            // We're not going to check for every possible campus name; as long as there's *something* there, we'll assume it's distance ed
            $student->setCampus(Student::DISTANCE_ED);
        } else {
            // If the campus isn't set, then throw an exception
            throw \InvalidArgumentException("Unrecognized campus ({$data->campus}) for {$data->banner_id}.");
        }

        // Level (grad vs undergrad)
        if($data->level == self::UNDERGRAD) {
            $student->setLevel(Student::UNDERGRAD);
        } else if ($data->level == self::GRADUATE) {
            $student->setLevel(Student::GRADUATE);
        } else {
            throw \InvalidArgumentException("Unrecognized student level ({$data->level}) for {$data->banner_id}.");
        }

        // Majors - Can be an array of objects, or just a single object
        if(is_array($data->majors)) {
            foreach($data->majors as $major){
                $student->addMajor(new AcademicMajor($major->major_code, $major->major_desc));
            }
        } else if(is_object($data->majors)){
            $student->addMajor(new AcademicMajor($data->majors->major_code, $data->majors->major_desc));
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
        $addrParts = explode(" ", $data->addr2);
        $student->setCity($addrParts[0]);
        $student->setState($addrParts[1]);
        $student->setZip($addrParts[2]);
    }

    /**
     * Logs this request to PHPWS' soap.log file
     */
    private function logRequest($functionName, $result, Array $params)
    {
        $args = implode(', ', $params);
        $msg = "$function($args) result: $result";
        \PHPWS_Core::log($msg, 'soap.log', 'SOAP');
    }
}

?>