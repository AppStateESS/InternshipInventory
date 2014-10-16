<?php

namespace Intern\Command;

use Intern\Internship;
use Intern\DepartmentFactory;
use Intern\Agency;
use Intern\StudentProviderFactory;

use Intern\DatabaseStorage;

/**
 * AddInternship Class
 *
 * Controller class for creating an new internship.
 *
 * @author Jeremy Booker
 * @package hms
 */
class AddInternship {

    public function __construct()
    {

    }

    public function execute()
    {
        // Check permissions
        if(!\Current_User::allow('intern', 'create_internship')){
            \NQ::simple('intern', NotifyUI::ERROR, 'You do not have permission to create new internships.');
            \NQ::close();
            \PHPWS_Core::home();
        }

        // Get a list of any missing input the user didn't fill in
        $missingFieldList = $this->checkForMissingInput();

        // If there are missing fields, redirect to the add internship interface
        // and highlight the fields
        if(!empty($missingFieldList)) {
            $this->redirectToForm($missingFieldList, $_POST);
        }

        // Check that the student Id looks valid
        $studentId = $_POST['studentId'];

        // Create the student object
        $student = StudentProviderFactory::getProvider()->getStudent($studentId);

        // Get the department ojbect
        $department = DepartmentFactory::getDepartmentById($_POST['department']);

        // Create and save the agency object
        $agency = new Agency($_POST['agency']);
        DatabaseStorage::save($agency);

        // Get the term
        // TODO Double check that this is set and that it is reasonable
        $term = $_POST['term'];

        // Get the location
        //TODO double check that this is set and is reasonable
        $location = $_POST['location'];

        // Create a new internship object
        $intern = new Internship($student, $term, $location, $department, $agency);

        // Save it!!
        $intern->save();

        // Show a success notice and redirect to the edit page
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Created internship for {$intern->getFullName()}");
        \NQ::close();

        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $intern->getId());
    }

    /**
     * Check all the input fields for missing values.
     * @return Array List of missing field names
     */
    private function checkForMissingInput()
    {
        // Check for missing data
        $missingFieldList = array();

        // Check student ID
        if (!isset($_POST['studentId']) || (isset($_POST['studentId']) && $_POST['studentId'] == '')) {
            $missingFieldList[] = 'studentId';
        }

        // Check term
        if (!isset($_POST['term'])) {
            $missingFieldList[] = 'term';
        }

        // Check Locations
        if (!isset($_POST['location']) || ($_POST['location'] != 'domestic' && $_POST['location'] != 'international')) {
            $missingFieldList[] = 'location';
        }

        // Check state, if domestic
        if ($_POST['location'] == 'domestic' && (!isset($_POST['state']) || $_POST['state'] == '-1')) {
            $missingFieldList[] = 'state';
        }

        // Check county, if international
        if ($_POST['location'] == 'international' && (!isset($_POST['country']) || $_POST['country'] == '-1')) {
            $missingFieldList[] = 'country';
        }

        // Check Department
        if (!isset($_POST['department']) || (isset($_POST['department']) && $_POST['department'] === '-1')) {
            $missingFieldList[] = 'department';
        }

        // Check Agency
        if (!isset($_POST['agency']) || (isset($_POST['agency']) && $_POST['agency'] == '')) {
            $missingFieldList[] = 'agency';
        }

        return $missingFieldList;
    }

    /**
     * Redirect to the add internship interface and highlight any missing fields
     */
    private function redirectToForm(Array $missingFields, Array $previousValues)
    {
        $url = 'index.php?module=intern&action=ShowAddInternship';

        if(!empty($missingFields)) {
            $url .= '&missing=' . implode('+', $missingFields);
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Please complete the highlighted fields.");
        }

        unset($previousValues['module']);
        unset($previousValues['action']);

        // Restore the values in the fields the user already entered
        foreach ($previousValues as $key => $val){
            if($key != 'module' && $key != 'action') {
                $url .= "&$key=$val";
            }
        }

        \NQ::close();
        return \PHPWS_Core::reroute($url);
    }
}

?>