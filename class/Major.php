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
PHPWS_Core::initModClass('intern', 'Editable.php');
define('MAJOR_EDIT', 'edit_major');
class Major extends Editable
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

    /**
     * Get an empty CSV to fill in fields.
     */
    public static function getEmptyCSV(){
        return array('Undergraduate Major' => '');
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return MAJOR_EDIT;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_major';
    }

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_major';
    }

    /**
     * @Override Editable::forceDelete
     */
    public function forceDelete()
    {
        if(!Current_User::allow('intern', $this->getDeletePermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to delete majors.');
        }

        PHPWS_Core::initModClass('intern', 'Student.php');
        if($this->id == 0)
            return;
        $db = Student::getDb();
        $db->addWhere('ugrad_major', $this->id);
        $studs = $db->getObjects('Student');
        
        // Set each ugrad_major to NULL
        foreach($studs as $stud){
            $stud->ugrad_major = null;
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
     * Return an associative array {id => Major name } for all majors in DB
     * that aren't hidden.
     * @param $except - Always show the major with this ID. Used for students
     *                  with a hidden major. We still want to see it in the select box. 
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
        $majors[-1] = 'Select Major';
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
}

?>