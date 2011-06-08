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

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_grad_prog';
    }

    /**
     * @Override Editable::forceDelete
     */
    public function forceDelete()
    {
        if(!Current_User::allow('intern', $this->getDeletePermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to delete grad programs.');
        }

        PHPWS_Core::initModClass('intern', 'Student.php');
        if($this->id == 0)
            return;
        $db = Student::getDb();
        $db->addWhere('grad_prog', $this->id);
        $studs = $db->getObjects('Student');
        
        // Set each grad_prog to NULL
        foreach($studs as $stud){
            $stud->grad_prog = null;
            $stud->save();
        }

        // Finally, delete this.
        try{
            $this->delete();
            NQ::simple('intern', INTERN_SUCCESS, "<i>$this->name</i> deleted.");
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }
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
     * Return an associative array {id => Grad. Prog. name } for all programs in DB
     * that aren't hidden. 
     * @param $except - Always show the major with this ID. Used for students
     *                  with a hidden major. We still want to see it in the select box.
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
        $progs[-1] = 'Select Grad Program';
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