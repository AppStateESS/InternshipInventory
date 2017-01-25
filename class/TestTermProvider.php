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

        if($this->term == '201620') {
            $obj->census_date   = '5/25/2016';
        } else if($this->term == '201630') {
            $obj->census_date   = '7/4/2016';
        } else if($this->term == '201640') {
            $obj->census_date   = '8/29/2016';
        } else if($this->term == '201710') {
            $obj->census_date   = '01/30/2017';
        } else if($this->term == '201720') {
            $obj->census_date   = '06/01/2017';
        } else if($this->term == '201730') {
            $obj->census_date   = '07/10/2017';
        } else {
            throw new \Exception('Missing fake census date for ' . $this->term);
        }

        $partTerm = new \stdClass();

        $partTerm->part_term_code   = '3';
        $partTerm->part_term_desc   = 'Special Term';
        $partTerm->part_start_date  = '6/7/2016';
        $partTerm->part_end_date    = '7/14/2016';

        $obj->part_term             = $partTerm;

        $parentObj = new \stdClass();
        $parentObj->GetTermInfoResult = $obj;

        return $parentObj;
    }

    private function getFakeErrorResponse()
    {
        $obj = new \stdClass();

        return $obj;
    }
}
