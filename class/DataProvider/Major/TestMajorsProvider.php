<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
