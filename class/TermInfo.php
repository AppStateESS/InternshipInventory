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
        $partTerm = array();
    }

    /*****
     * Accessor / Mutator Methods *
     */

    public function setTermCode($term_code)
    {
        $this->termCode = $term_code;
    }

    public function setTermDesc($term_desc)
    {
        $this->termDesc = $term_desc;
    }

    public function setTermStartDate($term_start_date)
    {
        $this->termStartDate = $term_start_date;
    }

    public function setTermEndDate($term_end_date)
    {
        $this->termEndDate = $term_end_date;
    }

    public function setCensusDate($census_date)
    {
        $this->censusDate = $census_date;
    }

    public function setPartTermCode($part_term_code)
    {
        $this->partTerm['code'] = $part_term_code;
    }

    public function setPartTermDesc($part_term_desc)
    {
        $this->partTerm['desc'] = $part_term_desc;
    }

    public function setPartTermStartDate($part_start_date)
    {
        $this->partTerm['startDate'] = $part_start_date;
    }

    public function setPartTermEndDate($part_end_date)
    {
        $this->partTerm['endDate'] = $part_end_date;
    }

    public function getTermCode()
    {
        return $this->termCode;
    }

    public function getTermDesc()
    {
        return $this->termDesc;
    }

    public function getTermStartDate()
    {
        return $this->termStartDate;
    }

    public function getTermEndDate()
    {
        return $this->termEndDate;
    }

    public function getCensusDate()
    {
        return $this->censusDate;
    }

    public function getPartTermCode()
    {
        return $this->partTerm['code'];
    }

    public function getPartTermDesc()
    {
        return $this->partTerm['desc'];
    }

    public function getPartTermStartDate()
    {
        return $this->partTerm['startDate'];
    }

    public function getPartTermEndDate()
    {
        return $this->partTerm['endDate'];
    }


}
