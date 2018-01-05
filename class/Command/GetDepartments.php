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

namespace Intern\Command;

class getDepartments {
    public function execute()
    {
        // Get list of departments for the current user
        // If user is a Deity, then get all departments
        if (\Current_User::isDeity()) {
            $departments = \Intern\DepartmentFactory::getDepartmentsAssoc();
        } else {
            $departments = \Intern\DepartmentFactory::getDepartmentsAssocForUsername(\Current_User::getUsername());
        }

        $departments = array('-1' => 'Select a Department') + $departments;

        /*
         * NB: Javascript objects are unordered. When the JSON data is
         * decoded, numeric keys may be re-arraged. Making the keys into strings
         * (by pre-pending an underscore) will prevent the re-ordering.
         */
        $newDepts = array();
        foreach($departments as $key=>$value){
            $newDepts['_' . $key] = $value;
        }

        echo json_encode($newDepts);

        exit;
    }
}
