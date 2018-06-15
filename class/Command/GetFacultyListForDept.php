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
