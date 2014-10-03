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

        $params = array('BannerID' => $bannerId,
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