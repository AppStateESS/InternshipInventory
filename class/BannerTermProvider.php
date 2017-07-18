<?php

namespace Intern;

use \SoapFault;

/**
 * BannerTermProvider
 *
 * Returns a Term object with data pulled from a web service connected to Banner.
 * Usually created through the TermProviderFactory.
 *
 * @see \Intern\TermProviderFactory
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

        $response = $response->GetTermInfoResult;

        if(isset($response->error_desc) && $response->error_desc !== '') {
            if($response->error_desc === 'InvalidUserName') {
                throw new \Intern\Exception\BannerPermissionException('While fetching term data, webservice returned permission error for: ' . $this->currentUserName);
            }

            throw new \Intern\Exception\WebServiceException('Web service returned an error while fetching term data.');
        }

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

        // Census date may not always be set in our web service response
        if(isset($data->census_date)){
            $termInfo->setCensusDate($data->census_date);
        }

        // Part of term data may not be available yet. If not, we'll skip plugging in those values.
        if(isset($data->part_term)){
            if(is_array($data->part_term)){
                foreach($data->part_term as $termPart){
                    $termInfo->addTermPart($termPart);
                }
            }else{
                $termInfo->addTermPart($data->part_term);
            }
        }
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
