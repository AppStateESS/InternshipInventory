<?php

/**
 * REST-ful controller for creating/editing faculty to department associations.
 * @author jbooker
 * @package intern
 */
class FacultyDeptRest {

    public function execute()
    {
        switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'DELETE':
                $this->delete();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    private function post()
    {
        $postArray = json_decode(file_get_contents('php://input'), true);
        
        $facultyId       = $postArray['faculty_id'];
        $departmentId    = $postArray['department_id'];
        
        $sql = "INSERT INTO intern_faculty_department VALUES ('$facultyId', '$departmentId')";
        
        $result = PHPWS_DB::query($sql);
        
        if(PHPWS_Error::logIfError($result)){
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }

        $obj = new stdClass();
        $obj->faculty_id       = $facultyId;
        $obj->department_id    = $departmentId;
        
        echo json_encode($obj);
        
        exit;
    }
    
    private function delete()
    {
        $postArray = json_decode(file_get_contents('php://input'), true);
        
        $facultyId       = $postArray['faculty_id'];
        $departmentId    = $postArray['department_id'];
        
        $sql = "DELETE FROM intern_faculty_department WHERE faculty_id = $facultyId AND department_id = $departmentId";

        $result = PHPWS_DB::query($sql);
        
        if(PHPWS_Error::logIfError($result)){
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }
        
        header('HTTP/1.1 204 No Content');
        exit;
    }
}