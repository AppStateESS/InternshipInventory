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

namespace Intern\DataProvider\Term;

use Intern\TermInfo;

use \SoapFault;

/**
 * BannerTermProvider
 *
 * Returns a Term object with data pulled from a web service connected to Banner.
 * Usually created through the TermInfoProviderFactory.
 *
 * @see \Intern\DataProvider\Term\TermInfoProviderFactory
 * @author Jeremy Booker
 * @package Intern
 */
class WebServiceTermInfoProvider extends TermInfoProvider {

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
    public function getTermInfo(string $termCode): TermInfo
    {
        $termCode .= "0";

        if($termCode === null || $termCode == '0'){
            throw new \InvalidArgumentException('Missing term.');
        }

        $params = array('Term' => $termCode,
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
