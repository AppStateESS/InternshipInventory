<?php

namespace Intern;

use \SoapFault;

/**
 * BannerTermProvider
 *
 * Returns a Term object with data pulled from a web service connected to Banner.
 *
 * @author Jeremy Booker
 * @package Intern
 */
class BannerTermProvider {

    protected $currentUserName;

    private $client;


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
     * Returns a Term object with hard-coded data
     * @return Term
     */
    public function getTerm($term)
    {
        $term .= "0";

        if($term === null || $term== '0'){
            throw new \InvalidArgumentException('Missing term.');
        }

        $params = array('Term' => $term,
                        'UserName' => $this->currentUserName);

        try {
            $response = $this->sendRequest($params);
        } catch (SoapFault $e){
            throw $e;
        }

        if(is_array($response)){
            $response = $response[0];
        }

        $response = $response->GetTermInfoResult;

        // Log the request
        $this->logRequest('getTerm', 'success', $params);

        // Create the Student object and plugin the values
        $termObj = new TermInfo();
        $this->plugValues($termObj, $response);

        return $termObj;
    }

    protected function sendRequest(Array $params)
    {
        return $this->client->GetTermInfo($params);
    }

    /**
     * Takes a reference to a Student object and a SOAP response,
     * Plugs the SOAP values into Student object.
     *
     * @param TermInfo $termInfo
     * @param stdClass $data
     */
    protected function plugValues(&$termInfo, \stdClass $data)
    {
        /**********************
         * Basic Demographics *
         **********************/
        $termInfo->setTermCode($data->term_code);
        $termInfo->setTermDesc($data->term_desc);
        $termInfo->setTermStartDate($data->term_start_date);
        $termInfo->setTermEndDate($data->term_end_date);
        $termInfo->setCensusDate($data->census_date);

        $termInfo->setPartTermCode($data->part_term->part_term_code);
        $termInfo->setPartTermDesc($data->part_term->part_term_desc);
        $termInfo->setPartTermStartDate($data->part_term->part_start_date);
        $termInfo->setPartTermEndDate($data->part_term->part_end_date);
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
