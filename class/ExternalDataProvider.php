<?php

namespace Intern;

/**
 * StudentProvider
 *
 * Abstract class to define the interface for various ways
 * to get Student objects.
 */
abstract class ExternalDataProvider {

    /**
     * Returns a Student object corresponding to the given studentId.
     *
     * @abstract
     * @param string studentId
     * @param string term
     * @return Student
     */
    public abstract function getStudent($studentId, $term);

    /**
     * Returns the number of credit hours the given student is currently
     * enrolled for in the given term
     * @abstract
     * @param string StudentId
     * @param string StudentId
     * @return int
     */
    public abstract function getCreditHours($studentId, $term);

    /**
     * Returns a stdClass object representing a faculty member, or throws an exception if not Found
     *
     * @abstract
     * @param string facultyId
     * @return stdClass
     */
    public abstract function getFacultyMember($facultyId);
}
