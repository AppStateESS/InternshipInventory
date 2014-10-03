<?php

namespace Intern;

class Student {

    private $studentId;
    private $username;

    private $firstName;
    private $middleName;
    private $lastName;
    private $preferredName;

    private $gender;

    //private $dob; // Needs to be added to SOAP
    private $confidential;


    private $campus;
    private $college;
    private $level;
    private $department;
    private $major;
    private $gpa;
    private $gradDate;

    private $isStaff;
    private $isStudent;

    private $phone;

    private $address;
    private $address2;

    // City, state, zip?


    public function setStudentId($studentId) {
        $this->studentId = $studentId;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setFirstName($name) {
        $this->firstName = $name;
    }

    public function setMiddleName($name) {
        $this->middleName = $name;
    }

    public function setLastName($name) {
        $this->lastName = $name;
    }
}

?>