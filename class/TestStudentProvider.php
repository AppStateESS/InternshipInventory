<?php

namespace Intern;

/**
 * TestStudentProvider - Always returns student objects with hard-coded testing data
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TestStudentProvider extends StudentProvider {

    /**
     * Returns a Student object with hard-coded data
     * @return Student
     */
    public function getStudent($studentId)
    {
        $student = new Student();

        $response = $this->getFakeResponse();

        $this->plugValues($student, $response);

        return $student;
    }

    private function getFakeResponse()
    {
        $obj = new \stdClass();

        $obj->banner_id = '900123456';
        $obj->user_name = 'jb67803';
        $obj->email = 'jb67803@appstate.edu';
        $obj->first_name = 'Jeremy';
        $obj->last_name = 'Booker';
        $obj->middle_name = 'Awesome';
        $obj->preferred_name = 'j-dogg';
        $obj->gender = 'M';

        return $obj;
    }
}

?>