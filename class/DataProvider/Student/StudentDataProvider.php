<?php

namespace Intern\DataProvider\Student;

/**
 * StudentProvider
 *
 * Abstract class to define the interface for various ways
 * to get Student objects.
 */
abstract class StudentDataProvider {

    /**
     * Returns a Student object corresponding to the given studentId.
     *
     * @abstract
     * @param string studentId
     * @param string term
     * @return Intern\Student
     */
    public abstract function getStudent($studentId);

    /**
     * Returns the number of credit hours the given student is currently
     * enrolled for in the given term
     * @abstract
     * @param string StudentId
     * @param string StudentId
     * @return int
     */
    public abstract function getCreditHours(string $studentId, string $term);

    /**
     * Returns a stdClass object representing a faculty member, or throws an exception if not Found
     *
     * @abstract
     * @param string facultyId
     * @return stdClass
     */
    public abstract function getFacultyMember($facultyId);
}
