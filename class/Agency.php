<?php

/**
 * Agency
 *
 * Represents the agency that is hosting an internship.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 * @package Intern
 */
class Agency extends Model {

    public $name;

    public $address;
    public $city;
    public $state;
    public $zip;
    public $province;
    public $country;

    public $phone;

    public $supervisor_first_name;
    public $supervisor_last_name;
    public $supervisor_title;
    public $supervisor_phone;
    public $supervisor_email;
    public $supervisor_fax;
    public $supervisor_address;
    public $supervisor_city;
    public $supervisor_state;
    public $supervisor_zip;
    public $supervisor_province;
    public $supervisor_country;
    public $address_same_flag;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_agency');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        $csv = array();

        $csv['Agency Name']              = $this->name;
        $csv['Agency Address']           = $this->address;
        $csv['Agency City']              = $this->city;
        $csv['Agency State']             = $this->state == null ? '' : $this->state;
        $csv['Agency Zip Code']          = $this->zip == null ? '' : $this->zip;
        $csv['Agency Phone']             = $this->phone;
        $csv['Agency Country']           = $this->country;

        $csv['Agency Super. First Name'] = $this->supervisor_first_name;
        $csv['Agency Super. Last Name']  = $this->supervisor_last_name;
        $csv['Agency Super. Title']      = $this->supervisor_title;
        $csv['Agency Super. Phone']      = $this->supervisor_phone;
        $csv['Agency Super. Email']      = $this->supervisor_email;
        $csv['Agency Super. Fax']        = $this->supervisor_fax;
        $csv['Agency Super. Address']    = $this->supervisor_address;
        $csv['Agency Super. City']       = $this->supervisor_city;
        $csv['Agency Super. State']      = $this->supervisor_state == null ? '' : $this->supervisor_state;
        $csv['Agency Super. Zip Code']   = $this->supervisor_zip == null ? '' : $this->supervisor_zip;
        $csv['Agency Super. Country']    = $this->supervisor_country;

        return $csv;
    }

    /*
     * Get full name of supervisor with space in between names.
     */

    public function getSupervisorFullName()
    {
        $name = "";
        if(isset($this->supervisor_first_name)){
            $name .= $this->supervisor_first_name . " ";
        }

        if(isset($this->supervisor_last_name)){
            $name .= $this->supervisor_last_name;
        }

        return $name;
    }

    /**
     * Get the domestic looking address of agency.
     */
    public function getAddress()
    {
        $add = array();

        if (!empty($this->address)) {
            $add[] = $this->address . ',';
        }
        if (!empty($this->city)) {
            $add[] = $this->city . ',';
        }
        if(!empty($this->state)){
            $add[] = $this->state;
        }
        if (!empty($this->zip)) {
            $add[] = $this->zip;
        }

        if(!empty($this->province)){
            $add[] = $this->province . ', ';
        }

        if(!empty($this->country)){
            $add[] = $this->country;
        }

        return implode(' ', $add);
    }


    /**
     * Get the street address of the agency
     */
    public function getStreetAddress()
    {
        return $this->address;
    }

    /**
     * Get the domestic looking address of agency.
     * Update: 07/27/2011 reduction of required elements caused need for alteration
     */
    public function getSuperAddress()
    {
        if($this->address_same_flag == 1){
            return $this->getAddress();
        }else{
            $add = array();
            if(!empty($this->supervisor_address)){
                $add[] = $this->supervisor_address . ',';
            }
            if(!empty($this->supervisor_city)){
                $add[] = $this->supervisor_city . ',';
            }
            if(!empty($this->supervisor_state)){
                $add[] = $this->supervisor_state;
            }
            if(!empty($this->supervisor_zip)){
                $add[] = $this->supervisor_zip;
            }

            if(!empty($this->supervisor_country)){
                $add[] = $this->supervisor_country;
            }

            return implode(' ', $add);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhoneNumber()
    {
        return $this->phone;
    }

    public function getSupervisorTitle()
    {
        return $this->supervisor_title;
    }

    public function getSupervisorEmail()
    {
        return $this->supervisor_email;
    }

    public function getSupervisorPhoneNumber()
    {
        return $this->supervisor_phone;
    }

    public function getSupervisorFaxNumber()
    {
        return $this->supervisor_fax;
    }

}
