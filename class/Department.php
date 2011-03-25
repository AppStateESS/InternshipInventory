<?php

  /**
   * Model
   *
   * Represents an academic department at Appalachian State University.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');

class Department extends Model
{
    public $name;

    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_department');
    }

    /**
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        $tags = array();
        $tags['NAME'] = $this->name;
        $tags['DELETE'] = PHPWS_Text::moduleLink('Delete','intern',array('action'=>'edit_departments','delDep'=>TRUE,'id'=>$this->getID()));
        return $tags;
    }

    public static function getDepartmentsAssoc()
    {
        $db = self::getDb();
        $depts = $db->getObjects('Department');
        $deptsAssoc = array();
        foreach($depts as $dept)
        {
            $deptsAssoc[$dept->id] = $dept->name;
        }
        return $deptsAssoc;
    }
    
    /**
     * Show the appropriate department UI based on $todo.
     */
    public static function showDepartments($todo, $department)
    {
        PHPWS_Core::initModClass('intern', 'UI/DepartmentUI.php');
        
        $disp = new DepartmentUI();

        // Do appropriate action.
        if($todo == 'addDep' && isset($department)){
            self::addDepartment($department);
        }
        else if($todo == 'delDep' && isset($department)){
            self::delDepartment($department);
        }

        return $disp->display();
    }

    /**
     * Add a department to database with the passed name.
     */
    public static function addDepartment($name)
    {
        // Create the new Department Obj.
        $dept = new Department();
        $dept->name = $name;

        try{
            $dept->save();
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, "Error adding department <i>$name</i>. <br/>".$e->getMessage());
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
}

?>