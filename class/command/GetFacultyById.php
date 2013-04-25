<?php

/**
 * Controller class for returning a Faculty member's data
 * given the faculty member's ID.
 * 
 * @author jbooker
 * @package intern
 */
class GetFacultyById {
    
    public function __construct()
    {
        
    }
    
    public function execute()
    {
        PHPWS_Core::initModClass('intern', 'FacultyFactory.php');
        
        $id = $_GET['id'];
        
        if(!isset($id) || $id == '') {
            throw new InvalidArgumentException('Missing faculty ID.');
        }
        
        $faculty = FacultyFactory::getFacultyById($id);
        
        echo json_encode($faculty);
        
        exit;
    }
}

?>
