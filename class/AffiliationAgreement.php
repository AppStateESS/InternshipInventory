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
    public $begin_date;
    public $end_date;
    public $auto_renew;
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
    public function getId()
    {
      return $this->id;
    }

    public function getName()
    {
      return $this->name;
    }

    public function getBeginDate()
    {
      return $this->begin_date;
    }

    public function getEndDate()
    {
      return $this->end_date;
    }

    public function getAutoRenew()
    {
      return $this->auto_renew;
    }

    public function getNotes()
    {
      return $this->notes;
    }

    public function getDepartments()
    {
      PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
      $db = new PHPWS_DB('intern_affiliation_agreement');
      $db->addWhere('agreement_id', $this->id);

      //TODO Further code once the other sections of this project are further along
    }

    public function getLocations()
    {
      PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
      $db = new PHPWS_DB('intern_affiliation_location');
      $db->addWhere('agreement_id', $this->id);

      //TODO Further code once the other sections of this project are further along
    }

    /**
     * Setters
     */
    public function setId($id)
    {
      $this->id = $id;
    }

    public function setName($name)
    {
      $this->name = $name;
    }

    public function setBeginDate($beginDate)
    {
      $this->begin_date = $beginDate;
    }

    public function setEndDate($endDate)
    {
      $this->end_date = $endDate;
    }

    public function setAutoRenew($autoRenew)
    {
      $this->auto_renew = $autoRenew;
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
