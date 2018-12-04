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

namespace Intern;

class Student {
    // Defines for Internship Inventory student Data

    const MAIN_CAMPUS = 'main_campus';
    const DISTANCE_ED = 'distance_ed';

    // Basic demographics
    private $studentId;
    private $username;

    private $firstName;
    private $middleName;
    private $lastName;
    private $preferredName;

    private $confidential;

    // Academic info
    private $campus;
    private $level;
    private $majors; // Array holding multiple major objects
    private $gpa;
    private $gradDate;
    private $holds;

    // Person type flags
    private $isStaff;
    private $isStudent;

    public function __construct() {
        $this->majors = array();
    }

    /**
     * Determines if a student will be over the semester credit hour limit
     * Returns true if this internship's credit hours, plus the student's existing
     * credit hours would put them over the semester's limit. Limits vary for regular
     * terms (i.e. Fall/Spring) vs Summer terms, and Undergraduate vs Graduate levels.
     *
     * @param $internHours integer Number of credit hours the internship will be worth
     * @param $term integer The term the internship will be in. Used to check existing credit hours.
     * @return boolean
     */
    public function isCreditHourLimited(int $internHours, int $existingHours, Term $term): bool {
        $totalHours = $existingHours + $internHours;
        $semester = $term->getSemesterType();
        $code = $this->getLevel();
        $limit = 0;

        if ($level == self::UNDERGRAD) {
            $limit = $term->getUndergradOverloadHours();
        } else {
            $limit = $term->getGradOverloadHours();
        }

        if($totalHours > $limit){
            return true;
        } else {
            return false;
        }
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

    public function getConfidentialFlag() {
        return $this->confidential;
    }

    public function setConfidentialFlag($flag) {
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

    /**
    * Returns an array of AcademicMajor objects corresponding to this
    * student's majors.
    *
    * @return Array<AcademicMajor> Array of AcademicMajor objects for this student's majors.
    */
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

    public function getGradDate() {
        return $this->gradDate;
    }

    /*
    public function getCreditHours() {
        return $this->creditHours;
    }

    public function setCreditHours($hours) {
        $this->creditHours = $hours;
    }
    */

    /**
    * @param $flag bool
    */
    public function setStudentFlag($flag) {
        $this->isStudent = $flag;
    }

    public function getStudentFlag() {
        return $this->isStudent;
    }

    /**
    * @param $flag bool
    */
    public function setStaffFlag($flag) {
        $this->isStaff = $flag;
    }

    public function getStaffFlag() {
        return $this->isStaff;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }
}
