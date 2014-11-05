<?php

namespace Intern;

/**
 * StudentProvider
 *
 * Abstract class to define the interface for various ways
 * to get Student objects.
 */
abstract class StudentProvider {

    /**
     * Returns a Student object corresponding to the given studentId.
     *
     * @abstract
     * @param string StudentId
     * @return Student
     */
    public abstract function getStudent($studentId);

    /**
     * Takes a reference to a Student object and a SOAP response,
     * Plugs the SOAP values into Student object.
     *
     * @param Student $student
     * @param stdClass $data
     */
    protected abstract function plugValues(&$student, \stdClass $data);
}

?>