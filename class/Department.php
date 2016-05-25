<?php

namespace Intern;

 /**
  * Represents an academic department.
  *
  * @author Robert Bost <bostrt at tux dot appstate dot edu>
  * @author Jeremy Booker <jbooker at tux dot appstate dot edu>
  * @package Intern
  */
class Department extends Model
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

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function isHidden(){
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
