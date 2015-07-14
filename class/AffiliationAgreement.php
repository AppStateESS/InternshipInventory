<?php

PHPWS_Core::initModClass('intern', 'Editable.php');


 /**
  * Represents an Affiliation Agreement
  *
  * @author Chris Detsch
  * @package Intern
  */
class AffiliationAgreement
{
    public $id;
    public $name;
    public $beginDate;
    public $endDate;
    public $autoRenew;
    public $departments;
    public $contractId;
    public $locations;
    public $notes;


    /**
     * @Override Model::getDb
     */
    public static function getDb(){
        return new PHPWS_DB('intern_affiliation_agreement');
    }

    /**
     * Getters
     */
    public function getName()
    {
      return $this->name;
    }

    public function getBeginDate()
    {
      return $this->beginDate;
    }

    public function getEndDate()
    {
      return $this->endDate;
    }

    public function getAutoRenew()
    {
      return $this->autoRenew;
    }

    public function getDepartments()
    {
      return $this->departments;
    }

    public function getContractId()
    {
      return $this->contractId;
    }

    public function getLocations()
    {
      return $this->locations;
    }

    public function getNotes()
    {
      return $this->notes;
    }

    /**
     * Setters
     */
    public function setName($name)
    {
      $this->name = $name;
    }

    public function setBeginDate($beginDate)
    {
      $this->beginDate = $beginDate;
    }

    public function setEndDate($endDate)
    {
      $this->endDate = $endDate;
    }

    public function setAutoRenew($autoRenew)
    {
      $this->autoRenew = $autoRenew;
    }

    public function setDepartments($departments)
    {
      $this->departments = $departments;
    }

    public function setContractId($contractId)
    {
      $this->contractId = $contractId;
    }

    public function setLocations($locations)
    {
      $this->locations = $locations;
    }

    public function setNotes($notes)
    {
      $this->notes = $notes;
    }

    public function rowTags()
    {
          $tpl             = array();
          $tpl['NAME']     = $this->getName();
          $tpl['EXPIRES']   = $this->getEndDate();
          return $tpl;
    }
}

/**
 * Subclass for restoring from database.
 * @author Chris Detsch
 * @package intern
 */
class AffiliationAgreementDB extends AffiliationAgreement {

    /**
     * Empty constructor for restoring object from database
     */
    public function __construct(){

    }
}
