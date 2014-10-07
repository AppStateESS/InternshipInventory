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
    private $department;
    private $level;
    private $major;
    private $gpa;
    private $gradDate;

    private $isStaff;
    private $isStudent;

    private $phone;

    private $address;
    private $address2;

    // City, state, zip?

    /*****
     * Accessor / Mutator Methods *
     */

    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId) {
        $this->studentId = $studentId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($name) {
        $this->firstName = $name;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function setMiddleName($name) {
        $this->middleName = $name;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($name) {
        $this->lastName = $name;
    }

    public function getCampus() {
        return $this->campus;
    }

    public function setCampus($campus) {
        $this->campus = $campus;
    }

    public function getLevel() {
        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }
}

?>