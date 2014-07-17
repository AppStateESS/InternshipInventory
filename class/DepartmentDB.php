<?php

namespace Intern;

/**
 * Subclass for restoring from database.
 * @author jbooker
 * @package intern
 */
class DepartmentDB extends Department {
    
    /**
     * Empty constructor for restoring object from database
     */
    public function __construct(){}
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setHidden($hide)
    {
        $this->hidden = $hide;
    }
    
    public function setCorequisite($coreq)
    {
        $this->corequisite = $coreq;
    }
}