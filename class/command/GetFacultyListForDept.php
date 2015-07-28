<?php

class GetFacultyListForDept {

    public function __construct()
    {

    }

    public function execute()
    {
        $departmentId = $_REQUEST['department'];

        if (is_null($departmentId) || !isset($departmentId)) {
            throw new InvalidArgumentException('Missing department id.');
        }

        PHPWS_Core::initModClass('intern', 'FacultyFactory.php');
        PHPWS_Core::initModClass('intern', 'DepartmentFactory.php');

        $department = DepartmentFactory::getDepartmentById($departmentId);

        if(is_null($department) || !isset($department))
        {
          throw new Exceptiong('Department returned was null. Check department id.');
        }

        $faculty = FacultyFactory::getFacultyByDepartmentAssoc($department);

        if(is_null($faculty) || !isset($faculty))
        {
          throw new Exceptiong('Faculty returned was null.');
        }

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
