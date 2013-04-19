<?php

class GetFacultyListForDept {
    
    public function __construct()
    {
        
    }
    
    public function execute()
    {
        $departmentId = $_REQUEST['department'];
        
        if (is_null($departmentId)) {
            throw new InvalidArgumentException('Missing department id.');
        }
        
        PHPWS_Core::initModClass('intern', 'FacultyFactory.php');
        PHPWS_Core::initModClass('intern', 'DepartmentFactory.php');
        
        $department = DepartmentFactory::getDepartmentById($departmentId);
        
        $faculty = FacultyFactory::getFacultyByDepartmentAssoc($department);
        
        echo json_encode($faculty);
        exit; // Exit since this is called by JSON
    }
}

?>