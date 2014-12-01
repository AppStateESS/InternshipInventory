<?php

namespace Intern\Command;

use Intern\StudentProviderFactory;


/**
 * Controller class for getting student search suggestion data in JSON format.
 *
 * @author jbooker
 * @package intern
 */
class GetSearchSuggestions {

    public function __construct()
    {

    }

    public function execute()
    {
        $searchString = $_REQUEST['searchString'];

        // If search string is exactly 9 digits, it must be a student id
        // Do an exact lookup and see if we can find the requested student
        if(preg_match('/^([0-9]){9}$/', $searchString)) {
            echo json_encode($this->studentIdSearch($searchString));
            exit;
        }


        // Otherwise, try a username lookup and see if we can get an exact match


        // Otherwise, go to the big database of everyone and try a fuzzy search


        exit;
    }

    private function studentIdSearch($studentId)
    {
        $student = StudentProviderFactory::getProvider()->getStudent($studentId);

        $studentArray = array();
        $studentArray[] = array(
                               'name' => $student->getLegalName(),
                               'email' => $student->getUsername() . '@appstate.edu',
                               'major' => 'Computer Science', // TODO: $student->getMajor(),
                               'studentId' => $student->getStudentId()
                             );

        return $studentArray;
    }

    private function userNameSearch($string)
    {

    }

    private function fullNameSearch($string)
    {

    }
}