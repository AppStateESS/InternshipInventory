<?php

namespace Intern;

/**
 * Class to contain and manage term information (e.g. class dates and census date)
 * from Banner. This info is acquired through the web service interface by using the
 * BannerTermProvider class.
 *
 * @see \Intern\BannerTermProvider
 * @author jbooker
 * @package Intern
 */
class TermInfo {
    // Defines for Internship Inventory Term Banner Data

    // Basic demographics
    private $termCode;
    private $termDesc;
    private $termStartDate; // Format: '8/22/2017'
    private $termEndDate;
    private $censusDate; // Date the term is no longer available for new internships

    private $partTerm;

    public function __construct()
    {
        $this->partTerm = array();
    }

    public function getTermPartByCode($code)
    {
        foreach($this->partTerm as $part){
            if($part->part_term_code == $code){
                return $part;
            }
        }

        return null;
    }

    public function getLongestTermPart()
    {
        /*
        $semester = Term::getSemester($this->termCode);
        if($semester == Term::SPRING || $semester == Term::FALL){
            $part = $this->getTermPartByCode('4');
        } else if($semester == Term::SUMMER1){
            $part = $this->getTermPartByCode('SD');
        } else if($semester == Term::SUMMER2){
            $part = $this->getTermPartByCode('SE');
        }
        */

        $parts = $this->getTermParts();

        $longestPart = null;
        $longestPartLength = 0;

        foreach($parts as $part){
            $beginTimestamp = strtotime($part->part_start_date);
            $endTimestamp = strtotime($part->part_end_date);

            $length = $beginTimestamp - $endTimestamp;

            if($length > $longestPartLength){
                // We've found a longer part, so save it
                $longestPart = $part;
                $longestPartLength = $length;
            }
        }

        return $longestPart;
    }

    /*****
     * Accessor / Mutator Methods *
     */

    public function getTermCode()
    {
        return $this->termCode;
    }

    public function setTermCode($termCode)
    {
        $this->termCode = $termCode;
    }

    public function getTermDesc()
    {
        return $this->termDesc;
    }

    public function setTermDesc($termDesc)
    {
        $this->termDesc = $termDesc;
    }

    public function getTermStartDate()
    {
        return $this->termStartDate;
    }

    public function setTermStartDate($termStartDate)
    {
        $this->termStartDate = $termStartDate;
    }

    public function getTermEndDate()
    {
        return $this->termEndDate;
    }

    public function setTermEndDate($termEndDate)
    {
        $this->termEndDate = $termEndDate;
    }

    public function getCensusDate()
    {
        return $this->censusDate;
    }

    public function setCensusDate($censusDate)
    {
        $this->censusDate = $censusDate;
    }

    public function getTermParts(){
        return $this->partTerm;
    }

    public function addTermPart($part){
        $this->partTerm[] = $part;
    }

}
