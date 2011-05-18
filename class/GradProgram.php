<?php

  /**
   * GradProgram
   *
   * Models a graduate program. New grad programs will need to be created
   * in the future. Other graduate may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
PHPWS_Core::initModClass('intern', 'Model.php');
class GradProgram extends Model
{
    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_grad_prog');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Graduate Program' => $this->name);
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
            $tags['NAME'] = "<span class='hidden-major-prog'>$this->name</span>";
        }else{
            $tags['NAME'] = $this->name;
        }
        // TODO: Make all these JQuery. Make edit/hide functional.
        if(Current_User::allow('intern', 'edit_grad_prog')){
            $tags['EDIT'] = 'Edit | ';
            if($this->isHidden()){
                $tags['HIDE'] = PHPWS_Text::moduleLink('Show', 'intern', array('action' => 'edit_grad', 'hide' => false, 'id'=>$this->getId()));
            }else{
                $tags['HIDE'] = PHPWS_Text::moduleLink('Hide', 'intern', array('action' => 'edit_grad', 'hide' => true, 'id'=>$this->getId()));
            }
        }
        if(Current_User::allow('intern', 'delete_grad_prog')){
            $div = null;
            if(isset($tags['HIDE']))
                $div = ' | ';
            $tags['DELETE'] = $div.PHPWS_Text::moduleLink('Delete','intern',array('action'=>'edit_grad','del'=>TRUE,'id'=>$this->getID()));
        }
        return $tags;
    }

    /**
     * Return an associative array {id => Grad. Prog. name } for all grad programs in DB.
     */
    public static function getGradProgsAssoc()
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0);
        $progs = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $progs = array_reverse($progs, true); // preserve keys.
        $progs[-1] = 'None';
        return array_reverse($progs, true);
    }
    
    /**
     * Add a program to DB if it does not already exist.
     */
    public static function add($name)
    {
        /* Search DB for program with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            NQ::simple('intern', INTERN_WARNING, "The graduate program <i>$name</i> already exists.");
            return;
        }

        /* Program does not exist...keep going */
        $prog = new GradProgram();
        $prog->name = $name;
        try{
            $prog->save();
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, "Error adding graduate program <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        /* Program was successfully added. */
        NQ::simple('intern', INTERN_SUCCESS, "<i>$name</i> added as graduate program.");
    }

    /**
     * Hide a program.
     */
    public static function hide($id, $hide=true)
    {
        $prog = new GradProgram($id);
        
        if($prog->id == 0 || !is_numeric($prog->id)){
            // Program wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information for graduate program from database.");
            return;
        }

        // Set the program's hidden flag in DB.
        if($hide){
            $prog->hidden = 1;
        }else{
            $prog->hidden = 0;
        }

        try{
            $prog->save();
            NQ::simple('intern', INTERN_SUCCESS, "Graduate program <i>$prog->name</i> is now hidden.");
        }catch(Exception $e){
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }
    }

    /**
     * Delete a program from database by ID.
     */
    public static function del($id)
    {
        $prog = new GradProgram($id);

        if($prog->id == 0){
            // Program wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading information for graduate program from database.");
            return;
        }

        $name = $prog->getName();
        
        try{
            // Try to delete program.
            if(!$prog->delete()){
                // Something bad happend. This should have been caught in the check above...
                NQ::simple('intern', INTERN_SUCCESS, "Error occurred removing graduate program from database.");
                return;
            }
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }

        // Program deleted successfully.
        NQ::simple('intern', INTERN_SUCCESS, "Deleted graduate program <i>$name</i>");
    }
}

?>