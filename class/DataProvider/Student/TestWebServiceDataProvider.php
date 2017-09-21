<?php

namespace Intern\DataProvider\Student;

/**
 * TestStudentProvider - Always returns student objects with hard-coded testing data
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TestWebServiceDataProvider extends WebServiceDataProvider {

    public function __construct($currentUserName) {
        $this->currentUserName = $currentUserName;
    }

    protected function sendRequest(Array $params)
    {
        return $this->getFakeResponse();
        //return $this->getFakeErrorResponse();
    }

    public function getCreditHours(string $studentId, string $term)
    {
        return 16;
    }

    public function getFacultyMember($facultyId)
    {
        $response = $this->getFakeResponse();

        return $response->GetInternInfoResult->DirectoryInfo;
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

        // Phone number
        $obj->phone = '828 123 4567';

        // Address
        $obj->addr1 = '123 Rivers Street';
        $obj->addr2 = 'John Thomas Hall';

        $obj->city  = 'Hickory';
        $obj->state = 'NC';
        $obj->zip   = '28602';

        // Academic Info
        //$obj->level     = WebServiceDataProvider::UNDERGRAD;   // 'U' or 'G'
        $obj->level     = WebServiceDataProvider::GRADUATE2;
        $obj->campus    = WebServiceDataProvider::MAIN_CAMPUS; // TODO verify values in SOAP
        //$obj->gpa       = '3.8129032260';
        $obj->gpa       = '1.8129032260';

        $obj->grad_date = '';
        //$obj->grad_date = '12/23/2015'; // Can be empty, or format 12/12/2015 (MM/DD/YYYY)

        $obj->term_code = '201540';

        // Majors - Can be an arry of objects, or a single object
        $major1 = new \stdClass();
        $major1->major_code = '355*';
        $major1->major_desc = 'Management';
        $major1->program_admit = '';

        $major2 = new \stdClass();
        $major2->major_code = '219A';
        $major2->major_desc = 'Computer Science';
        $major2->program_admit = '';

        $obj->majors = array($major1, $major2);

        // Holds
        // TODO verify what is returned
        $obj->holds = '';

        // Confidential flag
        $obj->confid = 'N'; //TODO verify this is 'N' or 'Y'

        // Assume there was no error
        $obj->error_num = 0;

        $parentObj = new \stdClass();
        $parentObj->GetInternInfoResult = new \stdClass();
        $parentObj->GetInternInfoResult->DirectoryInfo = $obj;

        return $parentObj;
    }

    private function getFakeErrorResponse()
    {
        $obj = new \stdClass();

        $obj->banner_id = '900123456';

        // User does not have Banner permissions
        //$obj->error_num = 1002;
        //$obj->error_desc = 'InvalidUserName';

        // Web service had an unknown error
        //$obj->error_num = 1;
        //$obj->error_desc = 'SYSTEM';

        // Student was not found
        $obj->error_num = 1101;
        $obj->error_desc = 'LookupBannerID';

        // No data?
        //$obj = new \stdClass();

        $parentObj = new \stdClass();
        $parentObj->GetInternInfoResult = new \stdClass();
        $parentObj->GetInternInfoResult->DirectoryInfo = $obj;
        return $parentObj;
    }
}
