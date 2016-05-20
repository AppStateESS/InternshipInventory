<?php
namespace Intern;

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

    /**
     * Get the link to download this document.
     */
    public function getDownloadLink()
    {
        \PHPWS_Core::initModClass('filecabinet', 'Document.php');
        $doc = new \PHPWS_Document($this->document_id);
        return \PHPWS_Text::moduleLink($doc->title, 'filecabinet', array('id' => $doc->id));
    }

    /**
     * Get the link to delete this document.
     */
    public function getDeleteLink()
    {
        $vars = array();
        $vars['doc_id'] = $this->document_id;
        $vars['action'] = 'delete_document';
        $link = new PHPWS_Link(null, 'intern', $vars);

        $jsVars = array();
        $jsVars['QUESTION'] = 'Are you sure you want to delete this document?';
        $jsVars['ADDRESS']  = $link->getAddress();
        $jsVars['LINK']     = '<i class="fa fa-trash-o close"></i>';
        return javascript('confirm', $jsVars);
    }

    /**
     * Get the folder ID storing documents.
     */
    public static function getFolderId()
    {
        $db = new \PHPWS_DB('folders');
        $db->addWhere('module_created', 'intern');
        return $db->select('one');
    }

    public function rowTags()
    {
          $tpl             = array();
          $tpl['NAME']     = $this->getName();
          $tpl['EXPIRES']   = $this->getEndDate();
          return $tpl;
    }
}
