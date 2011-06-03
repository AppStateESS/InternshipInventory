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
     * Return an associative array {id => dept. name} for all the 
     * departments in database.
     */
    public static function getDepartmentsAssoc()
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');

        $depts = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $depts = array_reverse($depts, true); // preserve keys.
        $depts[-1] = 'None';
        return array_reverse($depts, true);
    }

    /**
     * Return an associative array {id => dept. name} for all the departments
     * that the user with $username is allowed to see.
     */
    public static function getDepartmentsAssocForUsername($username)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addJoin('LEFT', 'intern_department', 'intern_admin', 'id', 'department_id');
        $db->addWhere('intern_admin.username', $username);
        return $db->select('assoc');
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