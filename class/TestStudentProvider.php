<?php

namespace Intern;

/**
 * TestStudentProvider - Always returns student objects with hard-coded testing data
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TestStudentProvider extends BannerStudentProvider {

    public function __construct($currentUserName) {
        $this->currentUserName = $currentUserName;
    }

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

        // ID & email
        $obj->banner_id = '900123456';
        $obj->user_name = 'jb67803';
        $obj->email     = 'jb67803@appstate.edu';

        // Person type
        $obj->isstaff   = '';
        $obj->isstudent = '1';
        $obj->type      = 'Student';
        $obj->department = '';

        // Basic demographics
        $obj->first_name    = 'Jeremy';
        $obj->middle_name   = 'Awesome';
        $obj->last_name     = 'Booker';
        $obj->preferred_name = 'j-dogg';
        $obj->title         = 'Senior';
        $obj->gender        = 'M';
        $obj->birth_date    = '6/20/1995';

        // Contact info
        $obj->phone = '828 123 4567';
        $obj->addr1 = 'ASU Box 12345';
        $obj->addr2 = 'Boone NC 28608';

        // Academic Info
        $obj->level     = BannerStudentProvider::UNDERGRAD;   // 'U' or 'G'
        $obj->campus    = BannerStudentProvider::MAIN_CAMPUS; // TODO verify values in SOAP
        $obj->gpa       = '3.8129032260';

        //$obj->grad_date = '';
        $obj->grad_date = '12/23/2015'; // Can be empty, or format 12/12/2015 (MM/DD/YYYY)

        $obj->term_code = '201540';

        // Majors - Can be an arry of objects, or a single object
        $major1 = new \stdClass();
        $major1->major_code = '355*';
        $major1->major_desc = 'Management';
        $major1->program_admit = '';

        $major2 = new \stdClass();
        $major2->major_code = '224A';
        $major2->major_desc = 'Computer Science';
        $major2->program_admit = '';

        $obj->majors = array($major1, $major2);

        // Holds
        // TODO verify what is returned
        $obj->holds = '';

        // Confidential flag
        $obj->confid = 'N'; //TODO verify this is 'N' or 'Y'

        return $obj;
    }
}
