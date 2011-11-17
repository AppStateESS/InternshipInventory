<?php

/**
 * Agency
 *
 * Represents the agency that is hosting an internship.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
class Agency extends Model {

    public $name;
    public $address;
    public $city;
    public $state;
    public $zip;
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

        $csv['Agency Name'] = $this->name;
        $csv['Agency Address'] = $this->address;
        $csv['Agency City'] = $this->city;
        $csv['Agency State'] = $this->state == null ? '' : $this->state;
        $csv['Agency Zip Code'] = $this->zip == null ? '' : $this->zip;
        $csv['Agency Phone'] = $this->phone;
        $csv['Agency Country'] = $this->country;
        $csv['Agency Super. First Name'] = $this->supervisor_first_name;
        $csv['Agency Super. Last Name'] = $this->supervisor_last_name;
        $csv['Agency Super. Phone'] = $this->supervisor_phone;
        $csv['Agency Super. Email'] = $this->supervisor_email;
        $csv['Agency Super. Fax'] = $this->supervisor_fax;
        $csv['Agency Super. Address'] = $this->supervisor_address;
        $csv['Agency Super. City'] = $this->supervisor_city;
        $csv['Agency Super. State'] = $this->supervisor_state == null ? '' : $this->supervisor_state;
        $csv['Agency Super. Zip Code'] = $this->supervisor_zip == null ? '' : $this->supervisor_zip;
        $csv['Agency Super. Country'] = $this->supervisor_country;

        return $csv;
    }

    /*
     * Get full name of supervisor with space in between names.
     */

    public function getSupervisorFullName()
    {
        return $this->supervisor_first_name . ' ' . $this->supervisor_last_name;
    }

    /**
     * Get the domestic looking address of agency.
     */
    public function getDomesticAddress()
    {
        if ($this->address) {
            $add[] = $this->address . ',';
        }
        if ($this->city) {
            $add[] = $this->city . ',';
        }
        $add[] = $this->state;
        if ($this->zip) {
            $add[] = $this->zip;
        }
        return implode(' ', $add);
    }

    /**
     * Get an international looking address of agency.
     */
    public function getInternationalAddress()
    {
        return "$this->address, $this->city, $this->state, $this->country $this->zip";
    }

    /**
     * Get the domestic looking address of agency.
     * Update: 07/27/2011 reduction of required elements caused need for alteration
     */
    public function getSuperDomesticAddress()
    {
        if ($this->address_same_flag == 1) {
            return $this->getDomesticAddress();
        } else {
            if ($this->supervisor_address) {
                $add[] = $this->supervisor_address . ',';
            }
            if ($this->supervisor_city) {
                $add[] = $this->supervisor_city . ',';
            }
            $add[] = $this->supervisor_state;
            if ($this->supervisor_zip) {
                $add[] = $this->supervisor_zip;
            }

            return implode(' ', $add);
        }
    }

    /**
     * Get the international looking address of agency.
     */
    public function getSuperInternationalAddress()
    {
        if ($this->address_same_flag == 1)
            return $this->getInternationalAddress();
        else
            return "$this->supervisor_address, $this->supervisor_city,  $this->state, $this->supervisor_country $this->supervisor_zip";
    }
}

?>