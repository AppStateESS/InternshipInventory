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

          if($this->getAutoRenew()){
            $tpl['STATUS'] = "active";
          }else if($expirationTime < 0){
            $tpl['STATUS'] = "danger";
          } else if($expirationTime < 7884000) {
            $tpl['STATUS'] = "warning";
          } else {
            $tpl['STATUS'] = "active";
          }

          return $tpl;
    }
}
