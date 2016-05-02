<?php

namespace Intern;

/**
 * TestStudentProvider - Always returns student objects with hard-coded testing data
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TestTermProvider extends BannerTermProvider {

    private $term;

    public function __construct($currentUserName) {
        $this->currentUserName = $currentUserName;
    }

    protected function sendRequest(Array $params)
    {
        $this->term = $params['Term'];
        return $this->getFakeResponse();
        //return $this->getFakeErrorResponse();
    }

    private function getFakeResponse()
    {
        $obj = new \stdClass();

        // ID & email
        $obj->term_code             = $this->term;
        $obj->term_desc             = 'Summer 2016';
        $obj->term_start_date       = '6/1/2016';
        $obj->term_end_date         = '7/14/2016';
        if($this->term == '201620')
        {
            $obj->census_date           = '5/25/2016';
        }
        else if($this->term == '201630')
        {
            $obj->census_date           = '7/4/2016';
        }
        else if($this->term == '201640')
        {
            $obj->census_date           = '7/29/2016';
        }

        $partTerm = new \stdClass();

        $partTerm->part_term_code   = '3';
        $partTerm->part_term_desc   = 'Special Term';
        $partTerm->part_start_date  = '6/7/2016';
        $partTerm->part_end_date    = '7/14/2016';

        $obj->part_term             = $partTerm;

        return $obj;
    }

    private function getFakeErrorResponse()
    {
        $obj = new \stdClass();

        // $obj->banner_id = '900123456';
        //
        // // User does not have Banner permissions
        // //$obj->error_num = 1002;
        // //$obj->error_desc = 'InvalidUserName';
        //
        // // Web service had an unknown error
        // //$obj->error_num = 1;
        // //$obj->error_desc = 'SYSTEM';
        //
        // // Student was not found
        // $obj->error_num = 1101;
        // $obj->error_desc = 'LookupBannerID';

        // No data?
        //$obj = new \stdClass();

        $parentObj = new \stdClass();
        $parentObj->GetInternInfoResult = new \stdClass();
        $parentObj->GetInternInfoResult->DirectoryInfo = $obj;
        return $parentObj;
    }
}
