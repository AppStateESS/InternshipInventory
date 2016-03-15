<?php

namespace Intern\test;

use \Intern\Internship;
use \Intern\Student;
use \Intern\Agency;
use \Intern\Department;

class InternshipTest extends \PHPUnit_Framework_TestCase
{
    public function testSetStudentData()
    {
        $student = $this->getTestStudent();
        $term = 201640;
        $location = 'domestic';
        $state = 'NC';
        $country = null;

        // Mocks for Department and Agency
        $department = $this->getMockBuilder('\Intern\Department')->getMock();
        $agency = $this->getMockBuilder('\Intern\Agency')->setConstructorArgs(array('Acme, Inc.'))->getMock();

        $intern = new Internship($student, $term, $location, $state, $country, $department, $agency);

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
        $student->setBirthDateFromString('01/01/1986');

        $student->setLevel(Student::UNDERGRAD);
        $student->setCampus(Student::MAIN_CAMPUS);
        $student->setGpa(3.55);
        $student->addMajor(new \Intern\AcademicMajor('355*', 'Management', 'U'));
        $student->addMajor(new \Intern\AcademicMajor('224A', 'Computer Science', 'U'));

        $student->setPhone('828-262-1234');

        $student->setAddress('John Thomas Hall');
        $student->setAddress2('123 Rivers St.');
        $student->setCity('Boone');
        $student->setState('NC');
        $student->setZip('28608');

        return $student;
    }
}
