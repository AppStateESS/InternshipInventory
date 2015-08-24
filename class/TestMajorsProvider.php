<?php

namespace Intern;

class TestMajorsProvider extends BannerMajorsProvider {

    public function getMajors($term)
    {
        $m1 = $this->createMajor('219A', 'Computer Science', 'UCERT_219A', 'Computer Science', 'ND', 'Co College Designated', 'U', 'CERT', 'C S', 'Computer Science');
        $m2 = $this->createMajor('301A', 'Accounting', 'BSB_301A', 'Accounting', 'CB', 'College of Business', 'U', 'BSB', 'ACC', 'Accounting');
        $m3 = $this->createMajor('301A', 'Accounting', '301A', 'Accounting', 'GC', 'University College', 'U', 'BSB', 'ACC', 'Accounting');
        $m4 = $this->createMajor('303A', 'Banking', 'BSB_303A', 'Banking', 'CB', 'College of Business', 'U', 'BSB', 'FIR', 'Finance Ins and Real Estate');

        return array($m1, $m2, $m3, $m4);
    }

    private function createMajor($majorCode, $majorDesc, $programCode, $programDesc, $collegeCode, $collegeDesc, $level, $degreeCode, $deptCode, $deptDesc)
    {
        $major = new \stdClass();

        $major->major_code = $majorCode;
        $major->major_desc = $majorDesc;

        $major->program_code = $programCode;
        $major->program_desc = $programDesc;

        $major->college_code = $collegeCode;
        $major->college_desc = $collegeDesc;

        $major->levl = $level;
        $major->degree_code = $degreeCode;

        $major->dept_code = $deptCode;
        $major->dept_desc = $deptDesc;

        return $major;
    }

}
