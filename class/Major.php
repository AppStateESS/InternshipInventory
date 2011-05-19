<?php

  /**
   * Major
   *
   * Models an undergraduate major. New majors will be created in future.
   * Other majors may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
PHPWS_Core::initModClass('intern', 'Model.php');
class Major extends Model
{
    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_major');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Undergraduate Major' => $this->name);
    }

    public function getName()
    {
        return $this->name;
    }
    public function isHidden()
    {
        return $this->hidden == 1;
    }
    
    /**
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        $tags = array();
        if($this->isHidden()){
            $tags['NAME'] = "<span id='$this->id' class='$this->id major-prog hidden-major-prog'>$this->name</span>";
        }else{
            $tags['NAME'] = "<span id='$this->id' class='$this->id major-prog'>$this->name</span>";
        }
        // TODO: Make all these JQuery.
        if(Current_User::allow('intern', 'edit_major')){
            $tags['EDIT'] = "<span id='edit-$this->id' class='$this->id edit-major-prog'>Edit</span> | ";
            if($this->isHidden()){
                $tags['HIDE'] = PHPWS_Text::moduleLink('Show', 'intern', array('action' => 'edit_majors', 'hide' => false, 'id'=>$this->getId()));
            }else{
                $tags['HIDE'] = PHPWS_Text::moduleLink('Hide', 'intern', array('action' => 'edit_majors', 'hide' => true, 'id'=>$this->getId()));
            }
        }
        if(Current_User::allow('intern', 'delete_major')){
            $div = null;
            if(isset($tags['HIDE']))
                $div = ' | ';
            $tags['DELETE'] = $div.PHPWS_Text::moduleLink('Delete','intern',array('action'=>'edit_majors','del'=>TRUE,'id'=>$this->getID()));
        }
        return $tags;
    }

    /**
     * Return an associative array {id => Major name } for all majors in DB
     * that aren't hidden. Always show the major with id $except.
     */
    public static function getMajorsAssoc($except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');

        if(!is_null($except)){
            $db->addWhere('id', $except, '=', 'OR');
        }
        $majors = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $majors = array_reverse($majors, true); // preserve keys.
        $majors[-1] = 'None';
        return array_reverse($majors, true);
    }
    
    /**
     * Add a major to DB if it does not already exist.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return NQ::simple('intern', INTERN_WARNING, 'No name given for new major. No major was added.');
        }
        /* Search DB for major with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            NQ::simple('intern', INTERN_WARNING, "The major <i>$name</i> already exists.");
            return;
        }

        /* Major does not exist...keep going */
        $major = new Major();
        $major->name = $name;
        try{
            $major->save();
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, "Error adding major <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        /* Major was successfully added. */
        NQ::simple('intern', INTERN_SUCCESS, "<i>$name</i> added as undergraduate major.");
    }

    /**
     * Hide a major.
     */
    public static function hide($id, $hide=true)
    {
        // Permission check
        if(!Current_User::allow('intern', 'edit_major')){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to hide majors.');
        }

        $m = new Major($id);
        
        if($m->id == 0 || !is_numeric($m->id)){
            // Major wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information for major from database.");
            return;
        }

        try{
            // Set the program's hidden flag in DB.
            if($hide){
                $m->hidden = 1;
                $m->save();
                return NQ::simple('intern', INTERN_SUCCESS, "Major <i>$m->name</i> is now hidden.");
            }else{
                $m->hidden = 0;
                $m->save();
                return NQ::simple('intern', INTERN_SUCCESS, "Major <i>$m->name</i> is now visible.");
            }
        }catch(Exception $e){
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }
    }

    /**
     * Delete a major from database by ID.
     */
    public static function del($id)
    {
        $m = new Major($id);

        if($m->id == 0){
            // Major wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information for major from database.");
            return;
        }

        $name = $m->getName();
        
        try{
            // Try to delete major.
            if(!$m->delete()){
                // Something bad happend. This should have been caught in the check above...
                NQ::simple('intern', INTERN_SUCCESS, "Error occurred removing major from database.");
                return;
            }
            // Major deleted successfully.
            NQ::simple('intern', INTERN_SUCCESS, "Deleted major <i>$name</i>");
        }catch(Exception $e){
            if($e->getCode() == DB_ERROR_CONSTRAINT){
                // TODO: Implement force delete.
                NQ::simple('intern', INTERN_ERROR, "One or more students have $name as their major. Cannot delete");
                return;
            }

            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }
    }

    /**
     * Rename the Major with ID $id to $newName.
     */
    public static function rename($id, $newName)
    {
        /* Permission check */
        if(!Current_User::allow('intern', 'edit_major')){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to rename a major.');
        }
        
        /* Must be valid name */
        $newName = trim($newName);
        if($newName == ''){
            return NQ::simple('intern', INTERN_WARNING, 'No name was given. No majors were changed.');
        }
        
        $m = new Major($id);
        
        if($m->id == 0){
            // Major wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information for major from database.");
            return;
        }
        $old = $m->name;
        try{
            $m->name = $newName;
            $m->save();
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
}

?>