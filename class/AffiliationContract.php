<?php

PHPWS_Core::initModClass('intern', 'Editable.php');


 /**
  * Represents an Affiliation Contract
  *
  * @author Chris Detsch
  * @package Intern
  */
class AffiliationContract
{
    public $agreement_id;
    public $document_id;


    /**
     * @Override Model::getDb
     */
    public static function getDb(){
        return new PHPWS_DB('intern_agreement_contract');
    }

    /**
     * Getters
     */
    public function getAgreementId()
    {
      return $this->agreement_id;
    }

    public function getDocumentId()
    {
      return $this->document_id;
    }

    /**
     * Setters
     */
    public function setAgreementId($agreementId)
    {
      $this->agreement_id = $agreementId;
    }

    public function setDocumentId($documentId)
    {
      $this->document_id = $documentId;
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
