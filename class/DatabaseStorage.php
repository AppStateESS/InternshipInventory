<?php

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
    public static function save($obj)
    {
        $db = new PHPWS_DB($obj->getTableName());
        
        try {
            $result = $db->saveObject($obj);
        } catch (Exception $e) {
            // rethrow any exceptions
            throw $e;
        }
        
        if (PHPWS_Error::logIfError($result)) {
            throw new Exception($result->toString());
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
        PHPWS_Core::initModClass($class . '.php');
        
        $table = $class::getTableName();
        
        $db = new PHPWS_DB($table);
        
        $instance = new $class;
        
        $db->addWhere('id', $id);
        $result = $db->loadObject($instance);
        
        if (PHPWS_Error::logIfError($result)) {
            throw new Exception($result->toString());
        }
        
        return $result;
    }
    
    public static function saveObject(DbStorable $o)
    {
        $vars = $o->extractVars();
        $tableName = $o::getTableName();
        
        // Check if the key already exists
        $query = "SELECT * FROM $tableName WHERE id = {$vars['id']}";
        $result = PHPWS_DB::getAll($query);
        
        if (count($result) > 0) {
            $exists = true;
        } else {
            $exists = false;
        }

        $db = new PHPWS_DB($o->getTableName());
        
        foreach ($vars as $key => $value) {
            $db->addValue($key, $value);
        }
        
        if ($exists) {
            $db->addWhere('id', $vars['id']);
            $result = $db->update();
        } else {
            $result = $db->insert(false);
        }

        if(PHPWS_Error::logIfError($result)) {
            throw new Exception($result->toString());
        }
    }
    
    
}
