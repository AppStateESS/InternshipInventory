<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
