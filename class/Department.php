<?php

namespace Intern;

 /**
  * Represents an academic department.
  *
  * @author Robert Bost <bostrt at tux dot appstate dot edu>
  * @author Jeremy Booker <jbooker at tux dot appstate dot edu>
  * @package Intern
  */
class Department extends Editable
{
    public $name;
    public $hidden;
    public $corequisite; // Whether or not a corequisite course is required for interns in this department.

    /**
     * @Override Model::getDb
     */
    public static function getDb(){
        return new \PHPWS_DB('intern_department');
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
        return 'edit_dept';
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
        if(!\Current_User::allow('intern', $this->getDeletePermission())){
            return \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'You do not have permission to delete departments.');
        }

        if($this->id == 0){
            // Item wasn't loaded correctly
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Error occurred while loading information from database.");
            return;
        }

        $name = $this->getName();

        try{
            // Try to delete item
            if(!$this->delete()){
                // Something bad happend. This should have been caught in the check above...
                \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Error occurred removing <i>$name</i> from database.");
                return;
            }
            // Item deleted successfully.
            \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Deleted <i>$name</i>");
        }catch(Exception $e){
            if($e->getCode() == DB_ERROR_CONSTRAINT){
                return \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "One or more internship has <i>$this->name</i> as their department. Sorry, cannot delete.");
            }else{
                return \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, $e->getMessage());
            }
        }
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
        if(!is_null($except)) {
            $db->addWhere('id', $except, '=', 'OR');
        }

        $db->setIndexBy('id');
        
        $depts[-1] = 'Select Department';
        $depts += $db->select('col');

        return $depts;
    }

    /**
     * Return an associative array {id => dept. name} for all the departments
     * that the user with $username is allowed to see.
     * @param $includeHiddenDept - Include the department with this ID, even if it's hidden. Used for internships
     *                  with a hidden department. We still want to see it in the select box.
     */
    public static function getDepartmentsAssocForUsername($username, $includeHiddenDept = null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR', 'grp');

        if(!is_null($includeHiddenDept)){
            $db->addWhere('id', $includeHiddenDept, '=', 'OR', 'grp');
        }

        // If the user doesn't have the 'all_departments' permission,
        // then add a join to limit to specific departments
        if(!\Current_User::allow('intern', 'all_departments') && !\Current_User::isDeity()){
            $db->addJoin('LEFT', 'intern_department', 'intern_admin', 'id', 'department_id');
            $db->addWhere('intern_admin.username', $username);
        }

        $db->setIndexBy('id');

        $depts[-1] = 'Select Department';
        $depts += $db->select('col');

        return $depts;
    }

    /**
     * Add a department to database with the passed name.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return \NQ::simple('intern', \Intern\NotifyUI::WARNING, 'No name given for new major. No major was added.');
        }

        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            \NQ::simple('intern', \Intern\NotifyUI::WARNING, "Department <i>$name</i> already exists.");
            return;
        }

        // Create the new Department Obj.
        $dept = new Department();

        $dept->name = $name;
        $dept->hidden = 0; // Be sure to set a default value for this, otherwise it gets set to null and screws things up
        $dept->corequisite = 0; // Be sure to set a default value for this, otherwise it gets set to null and screws things up

        $dept->save();

        // Successfully saved department to DB. Alert user and remind them the department they just saved.
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Department <i>$name</i> added.");
    }

    /**
     * Delete a department from database with the passed ID.
     */
    public static function delDepartment($departmentId)
    {
        $dept = new Department($departmentId);

        if($dept->id == 0){
            // Department wasn't loaded correctly
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Error occurred while loading department from database.");
            return;
        }

        $name = $dept->getName();

        try{
            // Try to delete department.
            if(!$dept->delete()){
                // Something bad happend. This should have been caught in the check above...
                \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Error occurred deleting department from database.");
                return;
            }
        }catch(Exception $e){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, $e->getMessage());
            return;
        }

        // Department deleted successfully.
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Deleted department <i>$name</i>");
    }

    public function getName()
    {
        return $this->name;
    }
    public function isHidden()
    {
        return $this->hidden == 1;
    }

    public function hasCorequisite()
    {
    	if ($this->corequisite == 1) {
    		return true;
    	}

    	return false;
    }
}
?>
