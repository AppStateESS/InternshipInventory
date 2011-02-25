<?php

  /**
   * Manages documents and uploading documents for Systems.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

class Sysinventory_Document
{
    public $id;
    public $path;
    public $system_id;

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
        if(isset($this->id)){
            $db = new PHPWS_DB('sysinventory_document');
            return $db->saveObject($this);
        }
        return FALSE;
    }

    /**
     * Delete row from database that matches this object's $id.
     */
    public function delete(){
        $db = new PHPWS_DB('sysinventory_document');
        $db->addWhere('id',$this->getID());
        $result = $db->delete();
        if(PHPWS_Error::logIfError($result)){
            return FALSE; 
        }
        return TRUE;
    }

    public function getDownloadLink($text=NULL)
    {
        // TODO: Implement.
    }
}

?>