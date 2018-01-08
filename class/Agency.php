<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern;

/**
 * Agency
 *
 * Represents the agency that is hosting an internship.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 * @package Intern
 */
class Agency implements DbStorable {

    public $id;

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

    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function getTableName()
    {
        return 'intern_agency';
    }

    /**
     *
     */
    public function getCSV()
    {
        $csv = array();

        $csv['Host Name']              = $this->name;
        $csv['Host Address']           = $this->address;
        $csv['Host City']              = $this->city;
        $csv['Host State']             = $this->state == null ? '' : $this->state;
        $csv['Host Zip Code']          = $this->zip == null ? '' : $this->zip;
        $csv['Host Phone']             = $this->phone;
        $csv['Host Country']           = $this->country;

        $csv['Host Super. First Name'] = $this->supervisor_first_name;
        $csv['Host Super. Last Name']  = $this->supervisor_last_name;
        $csv['Host Super. Title']      = $this->supervisor_title;
        $csv['Host Super. Phone']      = $this->supervisor_phone;
        $csv['Host Super. Email']      = $this->supervisor_email;
        $csv['Host Super. Fax']        = $this->supervisor_fax;
        $csv['Host Super. Address']    = $this->supervisor_address;
        $csv['Host Super. City']       = $this->supervisor_city;
        $csv['Host Super. State']      = $this->supervisor_state == null ? '' : $this->supervisor_state;
        $csv['Host Super. Zip Code']   = $this->supervisor_zip == null ? '' : $this->supervisor_zip;
        $csv['Host Super. Country']    = $this->supervisor_country;

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
    public function getStreetAddress()
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

    public function extractVars()
    {
        $vars = array();

        $vars['id']         = $this->getId();
        $vars['name']       = $this->getName();
        $vars['address']    = $this->getAddress();
        $vars['city']       = $this->getCity();
        $vars['state']      = $this->getState();
        $vars['zip']        = $this->getZip();
        $vars['province']   = $this->getProvince();
        $vars['country']    = $this->getCountry();
        $vars['phone']      = $this->getPhoneNumber();
        $vars['supervisor_first_name']  = $this->getSupervisorFirstName();
        $vars['supervisor_last_name']   = $this->getSupervisorLastName();
        $vars['supervisor_title']       = $this->getSupervisorTitle();
        $vars['supervisor_phone']       = $this->getSupervisorPhoneNumber();
        $vars['supervisor_email']       = $this->getSupervisorEmail();
        $vars['supervisor_fax']         = $this->getSupervisorFaxNumber();
        $vars['supervisor_address']     = $this->getSupervisorAddress();
        $vars['supervisor_city']        = $this->getSupervisorCity();
        $vars['supervisor_state']       = $this->getSupervisorState();
        $vars['supervisor_zip']         = $this->getSupervisorZip();
        $vars['supervisor_province']    = $this->getSupervisorProvince();
        $vars['supervisor_country']     = $this->getSupervisorCountry();
        $vars['address_same_flag']      = $this->getAddressSameFlag();

        return $vars;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getPhoneNumber()
    {
        return $this->phone;
    }

    public function getSupervisorFirstName()
    {
        return $this->supervisor_first_name;
    }

    public function getSupervisorLastName()
    {
        return $this->supervisor_last_name;
    }

    public function getSupervisorTitle()
    {
        return $this->supervisor_title;
    }

    public function getSupervisorPhoneNumber()
    {
        return $this->supervisor_phone;
    }

    public function getSupervisorEmail()
    {
        return $this->supervisor_email;
    }

    public function getSupervisorFaxNumber()
    {
        return $this->supervisor_fax;
    }

    public function getSupervisorAddress()
    {
        return $this->supervisor_address;
    }

    public function getSupervisorCity()
    {
        return $this->supervisor_city;
    }

    public function getSupervisorState()
    {
        return $this->supervisor_state;
    }

    public function getSupervisorZip()
    {
        return $this->supervisor_zip;
    }

    public function getSupervisorProvince()
    {
        return $this->supervisor_province;
    }

    public function getSupervisorCountry()
    {
        return $this->supervisor_country;
    }

    public function getAddressSameFlag()
    {
        return $this->address_same_flag;
    }
}
