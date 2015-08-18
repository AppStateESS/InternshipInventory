<?php

namespace Intern;

class Student {
    // Defines for Internship Inventory student Data
    const UNDERGRAD = 'ugrad';
    const GRADUATE  = 'grad';

    const MAIN_CAMPUS = 'main_campus';
    const DISTANCE_ED = 'distance_ed';

    // Basic demographics
    private $studentId;
    private $username;

    private $firstName;
    private $middleName;
    private $lastName;
    private $preferredName;
    private $birthDate;
    private $gender;

    private $confidential;

    // Academic info
    private $campus;
    //private $college;
    //private $department;
    private $level;
    private $majors; // Array holding multiple major objects
    private $gpa;
    private $gradDate;
    private $holds;

    // Person type flags
    private $isStaff;
    private $isStudent;

    // Contact info
    private $phone;
    private $address;
    private $city;
    private $state;
    private $zip;

    public function __construct()
    {
        $this->majors = array();
    }

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

    public function getLegalName() {
        return $this->getFirstName() . ' ' . $this->getLastName();
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

    public function getPreferredName() {
        return $this->preferredName;
    }

    public function setPreferredName($name) {
        $this->preferredName = $name;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    /**
     * Sets birth date
     * @param $date String - Date, formatted as mm/dd/yyyy, ex: 6/20/1995
     */
    public function setBirthDateFromString($date) {
        $this->birthDate = strtotime($date);
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getConfidentialFlag() {
        return $this->confidential;
    }

    public function setConfidentialFlag($flag)
    {
        $this->confidential = $flag;
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

    public function getMajors() {
        return $this->majors;
    }

    /**
     * Adds a major to the array of majors for this student.
     * Exepcts a stdClass object from the student info web service
     * @param \stdClass $major A major object
     */
    public function addMajor(AcademicMajor $major) {
        $this->majors[] = $major;
    }

    // TODO: test for valid values ('grad', 'ugrad')
    public function setLevel($level) {
        $this->level = $level;
    }

    public function getGpa() {
        return $this->gpa;
    }

    public function setGpa($gpa) {
        $this->gpa = $gpa;
    }

    /**
     * Sets graduation date
     * @param $date String - Date, formatted as mm/dd/yyyy, ex: 6/20/1995
     */
    public function setGradDateFromString($date) {
        $this->gradDate = strtotime($date);
    }

    public function getGradDate()
    {
        return $this->gradDate;
    }

    /**
     * @param $flag bool
     */
    public function setStudentFlag($flag) {
        $this->isStudent = $flag;
    }

    /**
     * @param $flag bool
     */
    public function setStaffFlag($flag) {
        $this->isStaff = $flag;
    }


    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getZip() {
        return $this->zip;
    }

    public function setZip($zip) {
        $this->zip = $zip;
    }
}
