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
    protected function plugValues(&$student, \stdClass $data)
    {
        $student->setStudentId($data->banner_id);
        $student->setUsername($data->user_name);

        // Basic demographics
        $student->setFirstName($data->first_name);
        $student->setLastName($data->last_name);
        $student->setMiddleName($data->middle_name);
        $student->setBirthDateFromString($data->birth_date);

        // Contact info
        $student->setPhone($data->phone);

        // Academic info
        $student->setLevel($data->level);
        $student->setCampus($data->campus);
        $student->setGpa($data->gpa);

        //TODO more here as it bcomes available
    }
}

?>