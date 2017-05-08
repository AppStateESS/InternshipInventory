<?php
namespace Intern;

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
    public $terminated;
    public $status;

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

    public function getTerminated()
    {
      return $this->terminated;
    }

    public function getDepartments()
    {
      \PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
      $db = new \PHPWS_DB('intern_affiliation_agreement');
      $db->addWhere('agreement_id', $this->id);

      //TODO Further code once the other sections of this project are further along
    }

    public function getLocations()
    {
      \PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
      $db = new \PHPWS_DB('intern_affiliation_location');
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

    public function setTerminated($terminated)
    {
      $this->terminated = $terminated;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get Document objects associated with this internship.
     */
    public function getDocuments()
    {
        $db = new \PHPWS_DB('intern_agreement_documents');
        $db->addWhere('agreement_id', $this->id);
        return $db->getObjects('\Intern\AffiliationContract');
    }

    public function getRowTags()
    {
          $tpl = array();
          $tpl['NAME']     = \PHPWS_Text::moduleLink($this->getName(), 'intern',
                              array('action' => 'showAffiliateEditView', 'affiliation_agreement_id' => $this->getId()));
          $tpl['EXPIRES']  = \PHPWS_Text::moduleLink(date('m/d/Y', $this->getEndDate()), 'intern',
                              array('action' => 'showAffiliateEditView', 'affiliation_agreement_id' => $this->getId()));
          $expirationTime = ((int)$this->getEndDate() - time());

        //   if($this->getAutoRenew()){
        //     $this->status = "green";
        //   }else if($expirationTime < 0){
        //     $this->status = "red";
        //   } else if($expirationTime < 7884000) {
        //     $this->status = "orange";
        //   } else {
        //     $this->status = "green";
        //   }

          return $tpl;
    }

    public function expirationStatus()
    {
        // assign values for active, warning and danger
        // json stuff to pass it to react thing
        if($this->getAutoRenew()){
          $this->status = "green";
        }else if($expirationTime < 0){
          $this->status = "red";
        } else if($expirationTime < 7884000) {
          $this->status = "orange";
        } else {
          $this->status = "green";
        }
    }

}
