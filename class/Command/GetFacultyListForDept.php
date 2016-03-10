<?php
namespace Intern\Command;
use Intern\DepartmentFactory;
use Intern\FacultyFactory;

class GetFacultyListForDept {
    
    public function __construct()
    {
        
    }
    
    public function execute()
    {
        $departmentId = $_REQUEST['department'];
        
        if (is_null($departmentId) || !isset($departmentId)) {
            throw new \InvalidArgumentException('Missing department id.');
        }
        
        $department = DepartmentFactory::getDepartmentById($departmentId);
        
        $faculty = FacultyFactory::getFacultyByDepartmentAssoc($department);
        
        /*
        $props = array();

        foreach ($faculty as $id => $val) {
            $props[]=array('id'=>$id, 'name'=>$val);        
        }
    
        return $props;
    */

        echo json_encode($faculty);
        exit; // Exit since this is called by JSON
    }
}

?>
