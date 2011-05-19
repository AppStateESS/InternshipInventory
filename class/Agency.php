<?php

  /**
   * Agency
   *
   * Represents the agency that is hosting an internship.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

class Agency extends Model
{
    public $name;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $phone;
    public $supervisor_first_name;
    public $supervisor_last_name;
    public $supervisor_phone;
    public $supervisor_email;
    public $supervisor_fax;
    public $supervisor_address;
    public $supervisor_city;
    public $supervisor_state;
    public $supervisor_zip;

    /**
     * @Override Model::getDb
     */
    public function getDb(){
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
        $csv['Agency State']             = $this->state;
        $csv['Agency Zip Code']          = $this->zip;
        $csv['Agency Phone']             = $this->phone;
        $csv['Agency Super. First Name'] = $this->supervisor_first_name;
        $csv['Agency Super. Last Name']  = $this->supervisor_last_name;
        $csv['Agency Super. Phone']      = $this->supervisor_phone;
        $csv['Agency Super. Email']      = $this->supervisor_email;
        $csv['Agency Super. Fax']        = $this->supervisor_fax;
        $csv['Agency Super. Address']    = $this->supervisor_address;
        $csv['Agency Super. City']       = $this->supervisor_city;
        $csv['Agency Super. State']      = $this->supervisor_state;
        $csv['Agency Super. Zip Code']   = $this->supervisor_zip;

        
        return $csv;
    }

    public function getSupervisorFullName()
    {
        return $this->supervisor_first_name.' '.$this->supervisor_last_name;
    }
}

?>