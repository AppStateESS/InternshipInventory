<?php

  /**
   * Model
   *
   * Represents an academic department at Appalachian State University.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Editable.php');
define('DEPT_EDIT', 'edit_dept');
class Department extends Editable
{
    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_department');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Department' => $this->name);
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return DEPT_EDIT;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_dept';
    }

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_dept';
    }

    /**
     * @Override Editable::del
     *
     * Do not show the 'Force Delete?' link if this delete fails.
     */
    public function del()
    {
        if(!Current_User::allow('intern', $this->getDeletePermission())){
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to delete departments.');
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
            if($e->getCode() == DB_ERROR_CONSTRAINT){
                return NQ::simple('intern', INTERN_ERROR, "One or more internship has <i>$this->name</i> as their department. Sorry, cannot delete.");
            }else{
                return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            }
        }
    }

    /**
     * @Override Editable::forceDelete
     */
    public function forceDelete()
    {
        /* This isn't called because it's not supported right now. Maybe later though....? */
        return NQ::simple('intern', INTERN_WARNING, 'Sorry, cannot forcefully delete a department.');
    }

    /**
     * Return an associative array {id => dept. name} for all the 
     * departments in database.
     * @param $except - Always show the department with this ID. Used for internships
     *                  with a hidden department. We still want to see it in  the select box.
     */
    public static function getDepartmentsAssoc($except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');
        if(!is_null($except))
            $db->addWhere('id', $except, '=', 'OR');

        $depts = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $depts = array_reverse($depts, true); // preserve keys.
        $depts[-1] = 'Select Department';
        return array_reverse($depts, true);
    }

    /**
     * Return an associative array {id => dept. name} for all the departments
     * that the user with $username is allowed to see.
     * @param $except - Always show the department with this ID. Used for internships
     *                  with a hidden department. We still want to see it in the select box. 
     */
    public static function getDepartmentsAssocForUsername($username, $except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR', 'grp');
        if(!is_null($except))
            $db->addWhere('id', $except, '=', 'OR', 'grp');

        // If the user doesn't have the 'all_departments' permission,
        // then add a join to limit to specific departments
        if(!Current_User::allow('intern', 'all_departments')){
            $db->addJoin('LEFT', 'intern_department', 'intern_admin', 'id', 'department_id');
            $db->addWhere('intern_admin.username', $username);
        }

        $depts = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $depts = array_reverse($depts, true); // preserve keys.
        $depts[-1] = 'Select Department';
        return array_reverse($depts, true);
    }
    
    /**
     * Add a department to database with the passed name.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return NQ::simple('intern', INTERN_WARNING, 'No name given for new major. No major was added.');
        }

        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            NQ::simple('intern', INTERN_WARNING, "Department <i>$name</i> already exists.");
            return;
        }

        // Create the new Department Obj.
        $dept = new Department();

        $dept->name = $name;
        $dept->hidden = 0; // Be sure to set a default value for this, otherwise it gets set to null and screws things up

        try{
            $dept->save();
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, "Error adding department <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        // Successfully saved department to DB. Alert user and remind them the department they just saved.
        NQ::simple('intern', INTERN_SUCCESS, "Department <i>$name</i> added.");
    }

    /**
     * Delete a department from database with the passed ID.
     */
    public static function delDepartment($departmentId)
    {
        $dept = new Department($departmentId);

        if($dept->id == 0){
            // Department wasn't loaded correctly
            NQ::simple('intern', INTERN_ERROR, "Error occurred while loading department from database.");
            return;
        }

        $name = $dept->getName();
        
        try{
            // Try to delete department.
            if(!$dept->delete()){
                // Something bad happend. This should have been caught in the check above...
                NQ::simple('intern', INTERN_SUCCESS, "Error occurred deleting department from database.");
                return;
            }
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }

        // Department deleted successfully.
        NQ::simple('intern', INTERN_SUCCESS, "Deleted department <i>$name</i>");
    }

    public function getName()
    {
        return $this->name;
    }
    public function isHidden()
    {
        return $this->hidden == 1;
    }
}

?>