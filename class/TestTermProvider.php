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
        $responseObj = new \stdClass();

        // ID & email
        $responseObj->term_code             = $this->term;
        $responseObj->term_desc             = Term::rawToRead($this->term);
        $responseObj->term_start_date       = '6/1/2016';
        $responseObj->term_end_date         = '7/14/2016';

        if($this->term == '201620') {
            $responseObj->census_date   = '5/25/2016';
        } else if($this->term == '201630') {
            $responseObj->census_date   = '7/4/2016';
        } else if($this->term == '201640') {
            $responseObj->census_date   = '8/29/2016';
        } else if($this->term == '201710') {
            $responseObj->census_date   = '1/15/2017';
        } else if($this->term == '201720') {
            $responseObj->census_date   = '5/15/2017';
        } else if($this->term == '201730') {
            $responseObj->census_date   = '6/15/2017';
        } else if($this->term == '201740') {
            $responseObj->census_date   = '8/15/2017';
        } else {
            throw new \Exception('Missing fake census date for ' . $this->term);
        }

        $partTerm = new \stdClass();

        $semester = Term::getSemester($this->term);
        if($semester == Term::SPRING || $semester == Term::FALL){
            $partTerm->part_term_code   = '4';
        } else if($semester == Term::SUMMER1){
            $partTerm->part_term_code   = 'SD';
        } else if($semester == Term::SUMMER2){
            $partTerm->part_term_code   = 'SE';
        }

        $partTerm->part_term_desc   = 'Special Term';
        $partTerm->part_start_date  = '6/7/2016';
        $partTerm->part_end_date    = '7/14/2016';

        $responseObj->part_term[] = $partTerm;

        $obj->GetTermInfoResult = $responseObj;

        return $obj;
    }

    private function getFakeErrorResponse()
    {
        $obj = new \stdClass();

        return $obj;
    }
}
