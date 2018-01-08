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

class AutoLoader {

    public static function loadClass($className) {
        // Strip off the module name, since we're already in that namespace
        $className = preg_replace('/Intern\\\/', '', $className);

        // Convert any remaining backslases to forward slashes
        $className = preg_replace('/\\\/', '/', $className);

        // Add 'class/' to the beginning, and .php to the end
        $className = 'class/' . $className . '.php';
        if(is_file($className)){
            require_once($className);
        }
     }

}

spl_autoload_register(array('AutoLoader', 'loadClass'));
