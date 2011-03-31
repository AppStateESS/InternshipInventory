<?php

  /**
   * FacultySupervisor
   *
   * Represents a faculty supervisor for an academic department.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');
class FacultySupervisor extends Model
{
    public $first_name;
    public $last_name;
    public $phone;
    public $email;
    public $department_id;
    
    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_faculty_supervisor');
    }

    public function getFullName(){
        return "$this->first_name $this->last_name";
    }
}

?>