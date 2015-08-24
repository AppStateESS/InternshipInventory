<?php

namespace Intern;

class BannerMajorsProvider extends MajorsProvider {

    private $currentUserName;

    private $client;

    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $wsdlUri = \PHPWS_Settings::get('intern', 'wsdlUri');

        // Create the SOAP instance
        $this->client = new \SoapClient($wsdlUri);
    }

    public function getMajors($term)
    {
        if($term === null || $term == '') {
            throw new \InvalidArgumentException('Missing term.');
        }

        $params = array('Term'      => $term,
                        'UserName'  => $this->currentUserName);

        try {
            $response = $this->client->getMajorInfo($params);
        } catch (SoapFault $e){
            throw $e;
        }

        return $response->GetMajorInfoResult;
    }
}
