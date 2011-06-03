<?php

  /**
   * GradProgram
   *
   * Models a graduate program. New grad programs will need to be created
   * in the future. Other graduate may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Editable.php');
define('GRAD_PROG_EDIT', 'edit_grad');
class GradProgram extends Editable
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

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return GRAD_PROG_EDIT;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_grad_prog';
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
        if(Current_User::allow('intern', 'edit_grad_prog')){
            $tags['EDIT'] = "<span id='edit-$this->id' class='$this->id edit-major-prog'>Edit</span> | ";
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
     * Return an associative array {id => Grad. Prog. name } for all programs in DB
     * that aren't hidden. Always show the program with id $except. The parameter 
     * is used when viewing an internship that has a program that was hidden.
     * This will cause the program to still be shown in the drop down list.
     */
    public static function getGradProgsAssoc($except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');
        if(!is_null($except)){
            $db->addWhere('id', $except, '=', 'OR');
        }
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
        $name = trim($name);
        if($name == ''){
            return NQ::simple('intern', INTERN_ERROR, 'No name given for new graduate program. No graduate program added.');
        }

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
}

?>