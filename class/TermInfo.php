<?php

namespace Intern;

class TermInfo {
    // Defines for Internship Inventory Term Banner Data

    // Basic demographics
    private $termCode;
    private $termDesc;
    private $termStartDate;
    private $termEndDate;
    private $censusDate;

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
        $semester = Term::getSemester($this->termCode);
        if($semester == Term::SPRING || $semester == Term::FALL){
            $part = $this->getTermPartByCode('4');
        } else if($semester == Term::SUMMER1){
            $part = $this->getTermPartByCode('SD');
        } else if($semester == Term::SUMMER2){
            $part = $this->getTermPartByCode('SE');
        }

        return $part;
    }

    /*****
     * Accessor / Mutator Methods *
     */

    public function getTermCode()
    {
        return $this->termCode;
    }

    public function setTermCode($term_code)
    {
        $this->termCode = $term_code;
    }

    public function getTermDesc()
    {
        return $this->termDesc;
    }

    public function setTermDesc($term_desc)
    {
        $this->termDesc = $term_desc;
    }

    public function getTermStartDate()
    {
        return $this->termStartDate;
    }

    public function setTermStartDate($term_start_date)
    {
        $this->termStartDate = $term_start_date;
    }

    public function getTermEndDate()
    {
        return $this->termEndDate;
    }

    public function setTermEndDate($term_end_date)
    {
        $this->termEndDate = $term_end_date;
    }

    public function getCensusDate()
    {
        return $this->censusDate;
    }

    public function setCensusDate($census_date)
    {
        $this->censusDate = $census_date;
    }

    public function getTermParts(){
        return $this->partTerm;
    }

    public function addTermPart($part){
        $this->partTerm[] = $part;
    }

}
