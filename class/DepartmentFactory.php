<?php

namespace Intern;
use Intern\Department;

/**
 * Factory for loading Department objects.
 * @author jbooker
 * @package intern
 */
class DepartmentFactory {
    
    /**
     * Returns a Department object based on the given department ID.
     * @param unknown $id
     * @return Department
     */
    public static function getDepartmentById($id)
    {
        // Sanity checking
        if (!isset($id) || $id === '') {
           throw new InvalidArgumentException('Missing department ID.'); 
        }
        
        // Query
        $query = "SELECT * FROM intern_department WHERE id = $id";
        $result = \PHPWS_DB::getRow($query);
        
        if (\PHPWS_Error::isError($result)) {
            throw new DatabaseException($result->toString());
        }
        
        if (sizeof($result) == 0) {
           return null; 
        }
        
        // Create the object and set member variables
        $department = new DepartmentDB();
        $department->setId($result['id']);
        $department->setName($result['name']);
        $department->setHidden($result['hidden']);
        $department->setCorequisite($result['corequisite']);
        
        return $department;
    }
}
