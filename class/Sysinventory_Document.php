<?php

  /**
   * Manages documents and uploading documents for Systems.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

class Sysinventory_Document
{
    public $id;
    public $system_id;
    public $document_fc_id; // File cabinet ID. References documents(id).

    public function __construct($id = NULL)
    {
        if(is_null($id)) return;

        $db = new PHPWS_DB('sysinventory_document');
        $db->addWhere('id', $id);
        $db->loadObject($this);
    }
    
    /**
     * Save row in database for this object.
     */
    public function save(){
        $db = new PHPWS_DB('sysinventory_document');
        $result = $db->saveObject($this);

        return $result;
    }

    /**
     * Delete row from database that matches this object's $id.
     * Also, delete the associated document in filecabinet.
     */
    public function delete()
    {
        PHPWS_Core::initModClass('filecabinet', 'Document.php');

        PHPWS_DB::begin();
        $db = new PHPWS_DB('sysinventory_document');
        $db->addWhere('id',$this->id);
        $result = $db->delete();

        if(PHPWS_Error::logIfError($result)){
            PHPWS_DB::rollback();
            return FALSE;
        }
        
        $doc = new PHPWS_Document($this->document_fc_id);
        $result = $doc->delete();

        if(PHPWS_Error::logIfError($result)){
            PHPWS_DB::rollback();
            return FALSE;
        }

        PHPWS_DB::commit();
        return TRUE;
    }

    /**
     * Get the link to download this document.
     */
    public function getDownloadLink($text=NULL)
    {
        PHPWS_Core::initModClass('filecabinet', 'Document.php');
        $doc = new PHPWS_Document($this->document_fc_id);
        return $doc->getViewLink('download');
    }

    /**
     * Get the icon link to edit this document. 
     */
    public function getEditLink()
    {
        PHPWS_Core::initModClass('filecabinet', 'Document.php');
        $doc = new PHPWS_Document($this->document_fc_id);
        return $doc->editLink(true);
    }

    /**
     * Get the link to delete this document.
     */
    public function getDeleteLink()
    {
        $vars = array();
        $vars['doc_id'] = $this->id;
        $vars['action'] = 'delete_document';
        $link = new PHPWS_Link(null, 'sysinventory', $vars);
        
        $jsVars = array();
        $jsVars['QUESTION'] = 'Are you sure you want to delete this document?';
        $jsVars['ADDRESS']  = $link->getAddress();
        $jsVars['LINK']     = '<img src="images/mod/filecabinet/delete.png" title="Delete" />';
        return javascript('confirm', $jsVars);
    }

    /**
     * Get the folder ID storing documents.
     */
    public static function getFolderId()
    {
        $db = new PHPWS_DB('folders');
        $db->addWhere('module_created', 'sysinventory');
        return $db->select('one');
    }
}
?>