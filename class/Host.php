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
 * Host
 *
 * Represents the host that is hosting an internship.
 *
 * @author Cydney Caldwell
 * @package Intern
 */
class Host implements DbStorable {

    public $id;
    public $name;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $province;
    public $country;
    public $phone;

    public $address_same_flag;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function getTableName()
    {
        return 'intern_host';
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

        return $csv;
    }

    /**
     * Get the domestic looking address of host.
     */
    public function getStreetAddress(){
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

    public function extractVars(){
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
        $vars['address_same_flag']      = $this->getAddressSameFlag();

        return $vars;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function getAddress(){
        return $this->address;
    }

    public function getCity(){
        return $this->city;
    }

    public function getState(){
        return $this->state;
    }

    public function getZip(){
        return $this->zip;
    }

    public function getProvince(){
        return $this->province;
    }

    public function getCountry(){
        return $this->country;
    }

    public function getPhoneNumber(){
        return $this->phone;
    }

    public function getAddressSameFlag(){
        return $this->address_same_flag;
    }
}
