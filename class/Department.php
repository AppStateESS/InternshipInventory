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
