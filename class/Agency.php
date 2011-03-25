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
    public $phone;
    public $supervisor_first_name;
    public $supervisor_last_name;
    public $supervisor_phone;
    public $supervisor_email;
    public $supervisor_fax;
    public $supervisor_address;

    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_agency');
    }
}

?>