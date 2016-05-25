<?php

namespace Intern;

use \Intern\UI\NotifyUI;

  /**
   * Admin
   *
   * Encapsulates the manaing of granular access by department.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

class Admin extends Model
{
    public $username;
    public $department_id;

    // For DBPager join
    public $department_name; // Department name, when joined to intern_department table

    /**
     * @Override Model::getDb
     */
    public static function getDb()
    {
        return new \PHPWS_DB('intern_admin');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV(){}

    public static function currentAllowed($dept)
    {
        \PHPWS_Core::initModClass('users', 'Current_User.php');
        return self::allowed(\Current_User::getUsername(), $dept);
    }

    public static function allowed($username, $dept)
    {
        if($dept instanceof Department){
            // User passed Department Obj.
            $dept = $dept->id;
        }

        $db = new PHPWS_DB('intern_admin');
        $db->addWhere('username', $username);
        $db->addWhere('department_id', $dept);
        $db->addColumn('id', $count=true);
        $count = $db->select();
        // If 1+ row exists in table then they're allowed.
        if(sizeof($count) >= 1){
            return true;
        }else{
            return false;
        }
    }
}
