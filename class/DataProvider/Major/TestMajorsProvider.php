<?php

namespace Intern\DataProvider\Major;

use Intern\AcademicMajorList;
use Intern\AcademicMajor;

class TestMajorsProvider extends BannerMajorsProvider {

    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;
    }

    public function getMajors($term): AcademicMajorList
    {
        $m1 = $this->createMajor('219A', 'Computer Science', 'G');
        $m2 = $this->createMajor('355*', 'Management', 'G');
        $m3 = $this->createMajor('301A', 'Accounting', 'G');

        $m4 = $this->createMajor('301A', 'Accounting', 'U');
        $m5 = $this->createMajor('301A', 'Accounting', 'U');
        $m6 = $this->createMajor('303A', 'Banking', 'U');

        $objs = array($m1, $m2, $m3, $m4, $m5, $m6);

        $majors = array();

        foreach ($objs as $obj){
            $majors[] = new AcademicMajor($obj->major_code, $obj->major_desc, $obj->levl);
        }

        return new AcademicMajorList($majors);
    }

    private function createMajor($majorCode, $majorDesc, $level)
    {
        $major = new \stdClass();

        $major->major_code = $majorCode;
        $major->major_desc = $majorDesc;
        $major->levl = $level;

        /*
        $major->program_code = $programCode;
        $major->program_desc = $programDesc;
        $major->degree_code = $degreeCode;

        $major->college_code = $collegeCode;
        $major->college_desc = $collegeDesc;

        $major->dept_code = $deptCode;
        $major->dept_desc = $deptDesc;
        */

        return $major;
    }

}
