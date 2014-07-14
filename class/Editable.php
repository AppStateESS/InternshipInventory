<?php

namespace Intern;

/**
 * Editable
 *
 * This abstract class makes things easier for building
 * the UI used for editing majors, grad programs, and 
 * departments. Anything else that needs to be 
 * hidden, renamed, or deleted can extend this abstract class
 * and be easily plugged into the javascript (edit).
 *
 *@author Robert Bost <bostrt at tux dot appstate dot edu>
 */
abstract class Editable extends Model
{
    /**
     * This should return a string that corresponds
     * the the case statement in index.php
     * Ex. Major implements Editable and it's 
     *     getEditAction method returns 'edit_major'.
     */
    static function getEditAction()
    {
        throw new Exception('Not yet implemented.');
    }

    /**
     * Get the name of the permission needed to edit the item.
     */
    static function getEditPermission()
    {
        throw new Exception('Not yet implemented.');
    }
    

    /**
     * Get the name of the permission needed to delete the item.
     */
    static function getDeletePermission()
    {
        throw new Exception('Not yet implemented.');
    }

    /**
     * Rename the Editable item with ID $id to $newName.
     */
    public function rename($newName)
    {
        /* Permission check */
        if(!Current_User::allow('intern', $this->getEditPermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to rename this.');
        }

        /* Must be valid name */
        $newName = trim($newName);
        if($newName == ''){
            return NQ::simple('intern', INTERN_WARNING, 'No name was given. Nothing were changed.');
        }
       
        /* Check ID */
        if($this->id == 0){
            // Editable wasn't loaded correctly
            if(isset($_REQUEST['ajax'])){
                NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information from database.");
                NQ::close();
                echo true;
                exit;
            }
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information from database.");
            return;
        }
        /* Keep old name around for NQ */
        $old = $this->name;
        try{
            /* Save and notify */
            $this->name = $newName;
            $this->save();
            if(isset($_REQUEST['ajax'])){
                NQ::simple('intern', INTERN_SUCCESS, "<i>$old</i> renamed to <i>$newName</i>");
                NQ::close();
                echo true;
                exit;
            }
            return NQ::simple('intern', INTERN_SUCCESS, "<i>$old</i> renamed to <i>$newName</i>");
        }catch(Exception $e){
            if(isset($_REQUEST['ajax'])){
                NQ::simple('intern', INTERN_ERROR, $e->getMessage());
                NQ::close();
                echo false;
                exit;
            }
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }
    }

    /**
     * Set an editable item to hidden/visible depending on parameter.
     */
    public function hide($hide=true)
    {
        /* Permission check */
        if(!Current_User::allow('intern', $this->getEditPermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to hide that.');
        }
        
        if($this->id == 0 || !is_numeric($this->id)){
            // Program wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information from database.");
            return;
        }

        // Set the item's hidden flag in DB.
        if($hide){
            $this->hidden = 1;
        }else{
            $this->hidden = 0;
        }

        try{
            $this->save();
            if($this->hidden == 1){
                NQ::simple('intern', INTERN_SUCCESS, "<i>$this->name</i> is now hidden.");
            }
            else{
                NQ::simple('intern', INTERN_SUCCESS, "<i>$this->name</i> is now visible.");
            }
        }catch(Exception $e){
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }
    }

    /**
     * Delete an editable item from database.
     */
    public function del()
    {
        if(!Current_User::allow('intern', $this->getDeletePermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to delete that.');
        }

        if($this->id == 0){
            // Item wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information from database.");
            return;
        }

        $name = $this->getName();
        
        try{
            // Try to delete item
            if(!$this->delete()){
                // Something bad happend. This should have been caught in the check above...
                NQ::simple('intern', INTERN_SUCCESS, "Error occurred removing <i>$name</i> from database.");
                return;
            }
            // Item deleted successfully.
            NQ::simple('intern', INTERN_SUCCESS, "Deleted <i>$name</i>");
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }
    }

    /**
     * Row tags for DBPager.
     */
    public function getRowTags()
    {
        $tags = array();
        $tags['ID'] = $this->id;
        if($this->isHidden()){
            $tags['NAME'] = "<span id='$this->id' class='$this->id prog hidden-prog'>$this->name</span>";
        }else{
            $tags['NAME'] = "<span id='$this->id' class='$this->id prog'>$this->name</span>";
        }

        if(Current_User::allow('intern', $this->getEditPermission())){
            $tags['EDIT'] = "<span id='edit-$this->id' class='$this->id edit-prog'>Edit</span> | ";
            if($this->isHidden()){
                $tags['HIDE'] = PHPWS_Text::moduleLink('Show', 'intern', array('action' => $this->getEditAction(), 'hide' => false, 'id'=>$this->getId()));
            }else{
                $tags['HIDE'] = PHPWS_Text::moduleLink('Hide', 'intern', array('action' => $this->getEditAction(), 'hide' => true, 'id'=>$this->getId()));
            }
        }
        if(Current_User::allow('intern', $this->getDeletePermission())){
            $div = null;
            if(isset($tags['HIDE']))
                $div = ' | ';
            $tags['DELETE'] = $div.PHPWS_Text::moduleLink('Delete','intern',array('action'=> $this->getEditAction(),'del'=>TRUE,'id'=>$this->getID()));
        }

        return $tags;
    }
}

?>
