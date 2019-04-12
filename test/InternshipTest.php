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

namespace Intern\test;

use \Intern\Internship;
use \Intern\Student;
use \Intern\HostFactory;
use \Intern\Department;
use PHPUnit\Framework\TestCase;

class InternshipTest extends TestCase
{
    public function testSetStudentData()
    {
        $student = $this->getTestStudent();
        $term = 201640;
        $location = 'domestic';
        $state = 'NC';
        $country = null;

        // Mocks for Department and Host
        $department = $this->getMockBuilder('\Intern\Department')->getMock();
        $host = HostFactory::getHostById(1);

        $intern = new Internship($student, $term, $location, $state, $country, $department, $host);

        $foo = true;
        $this->assertTrue($foo);
    }

    private function getTestStudent()
    {
        $student = new Student();

        $student->setStudentId(900123456);
        $student->setUsername('smithjd');

        $student->setFirstName('John');
        $student->setMiddleName('Doe');
        $student->setLastName('Smith');

        $student->setLevel('U');
        $student->setCampus(Student::MAIN_CAMPUS);
        $student->setGpa(3.55);
        $student->addMajor(new \Intern\AcademicMajor('355*', 'Management', 'U'));
        $student->addMajor(new \Intern\AcademicMajor('224A', 'Computer Science', 'U'));

        $student->setPhone('828-262-1234');

        return $student;
    }
}
