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
    const MAIN_CAMPUS = 'MC';
    const DISTANCE_ED = 'DE'; //todo verify this

    // Student level: grad, undergrad
    const UNDERGRAD = 'U';
    const GRADUATE  = 'G'; // todo verify


    /**
     * @param string $currentUserName - Username of the user currently logged in. Will be sent to web service
     */
    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $wsdlUri = \PHPWS_Settings::get('intern', 'wsdlUri');

        // Create the SOAP instance
        $this->client = new SoapClient($wsdlUri);
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
        $student->setStudentId($data->banner_id);
        $student->setUsername($data->user_name);

        // Basic demographics
        $student->setFirstName($data->first_name);
        $student->setLastName($data->last_name);
        $student->setMiddleName($data->middle_name);
        $student->setBirthDateFromString($data->birth_date);

        // Contact info
        $student->setPhone($data->phone);

        // Level (grad vs undergrad)
        if($data->level == self::UNDERGRAD) {
            $student->setLevel('ugrad'); // TODO get rid of magic value
        } else if ($data->level == self::GRADUATE) {
            $student->setLevel('grad');  // TODO get rid of magic value
        } else {
            throw \InvalidArgumentException("Unrecognized student level ({$data->level}) for {$data->banner_id}.");
        }

        // Campus
        if($data->campus == BannerStudentProvider::MAIN_CAMPUS) {
            $student->setCampus('main_campus');
        } else if ($data->campus == BannerStudentProvider::DISTANCE_ED) {
            $student->setCampus('distance_ed');
        } else {
            throw \InvalidArgumentException("Unrecognized campus ({$data->campus}) for {$data->banner_id}.");
        }
        $student->setGpa($data->gpa);

        //TODO more here as it bcomes available
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