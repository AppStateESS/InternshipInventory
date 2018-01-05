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
 * Class to handle saving and loading objects from the database.
 *
 * @author jbooker
 * @package intern
 */
class DatabaseStorage {

    /**
     * Saves the given object to the database.
     * @param Object $obj
     */
    public static function save(DbStorable $obj)
    {
        $db = new \PHPWS_DB($obj->getTableName());

        try {
            $result = $db->saveObject($obj);
        } catch (\Exception $e) {
            // rethrow any exceptions
            throw $e;
        }

        if (\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }

        return $obj->id;
    }

    /**
     * Loads an object from the database using the given class name and object id.
     *
     * @param String $class
     * @param int $id
     */
    public static function load($class, $id)
    {
        \PHPWS_Core::initModClass($class . '.php');

        $table = $class::getTableName();

        $db = new \PHPWS_DB($table);

        $instance = new $class;

        $db->addWhere('id', $id);
        $result = $db->loadObject($instance);

        if (\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }

        return $result;
    }

    public static function saveObject(DbStorable $o)
    {
        $vars = $o->extractVars();
        $tableName = $o::getTableName();

        // Check if the key already exists
        $query = "SELECT * FROM $tableName WHERE id = {$vars['id']}";
        $result = \PHPWS_DB::getAll($query);

        if (count($result) > 0) {
            $exists = true;
        } else {
            $exists = false;
        }

        $db = new \PHPWS_DB($o->getTableName());

        foreach ($vars as $key => $value) {
            $db->addValue($key, $value);
        }

        if ($exists) {
            $db->addWhere('id', $vars['id']);
            $result = $db->update();
        } else {
            $result = $db->insert(false);
        }

        if(\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }
    }


}
