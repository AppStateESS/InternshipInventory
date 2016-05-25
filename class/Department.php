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

        return $db->select('col');
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

        $depts = array();
        $depts[-1] = 'Select Department';
        $depts += $db->select('col');

        return $depts;
    }
}
