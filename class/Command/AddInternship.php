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

namespace Intern\Command;

use Intern\Internship;
use Intern\DepartmentFactory;
use Intern\SubHost;
use Intern\SubHostFactory;
use Intern\Supervisor;
use Intern\DataProvider\Student\StudentDataProviderFactory;
use Intern\WorkflowStateFactory;
use Intern\ChangeHistory;
use Intern\Department;

use Intern\DatabaseStorage;

/**
 * AddInternship Class
 *
 * Controller class for creating an new internship.
 *
 * @author Jeremy Booker
 * @package intern
 */
class AddInternship {

    public function __construct() {}

    public function execute() {
        // Check permissions
        if(!\Current_User::allow('intern', 'create_internship')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'You do not have permission to create new internships.');
            \NQ::close();
            \PHPWS_Core::home();
            return;
        }

        // Get a list of any missing input the user didn't fill in
        $missingFieldList = $this->checkForMissingInput();

        // If there are missing fields, redirect to the add internship interface
        if(!empty($missingFieldList)) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Banner ID must be in Student box before creating internship.");
            $this->redirectToForm();
            return;
        }

        // Check that the student Id looks valid
        $studentId = $_POST['studentId'];

        // Get the term
        // TODO Double check that this is reasonable
        $term = $_POST['term'];

        // Create the student object
        $student = StudentDataProviderFactory::getProvider()->getStudent($studentId);

        // Double check the student's level field
        if($student->getLevel() == null){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "This student does not have a valid 'level' field in Banner. This usually means the student is not currently enrolled. We recommend contacting the Registrar's Office to check this student's enrollment status.");
            $this->redirectToForm();
            return;
        }

        // Get the department ojbect
        $departmentId = preg_replace("/^_/", '', $_POST['department']); // Remove leading underscore in department id
        $department = DepartmentFactory::getDepartmentById($departmentId);

        if(!($department instanceof Department)){
            throw new \Exception('Could not load department.');
        }

        // Get the host and sub objects
        $sub = SubHostFactory::getSubById($_POST['sub_host']);
        if(!($sub instanceof SubHost)){
            throw new \Exception('Could not load Sub Host.');
        }

        $supervisor = new Supervisor(null,null,null,null,null,null,null,null,null,null,null,null,null);
        DatabaseStorage::save($supervisor);

        // Get the location
        $location = $_POST['location'];
        if ($location == 'international'){
            $state = null;
            $country = $_POST['country'];
        } else {
            $state = $_POST['state'];
            $country = null;
        }

        // Create a new internship object
        $intern = new Internship($student, $term, $location, $state, $country, $department, $sub, $supervisor);

        // Save it!!
        $intern->save();

        if(SubHostFactory::deniedCheck($_POST['sub_host'])){
            $t = \Intern\WorkflowTransitionFactory::getTransitionByName('Intern\WorkflowTransition\DeniedTransition');
            $workflow = new \Intern\WorkflowController($intern, $t);
            $workflow->doTransition(null);
            $workflow->doNotification(null);
        } else{
            $t = \Intern\WorkflowTransitionFactory::getTransitionByName('Intern\WorkflowTransition\CreationTransition');
            $workflow = new \Intern\WorkflowController($intern, $t);
            $workflow->doTransition(null);
            $workflow->doNotification(null);
        }
        // Show a success notice and redirect to the edit page
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Created internship for {$intern->getFullName()}");
        \NQ::close();

        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $intern->getId());
    }

    /**
     * Check all the input fields for missing values.
     * @return Array List of missing field names
     */
    private function checkForMissingInput() {
        // Check for missing data
        $missingFieldList = array();

        // Check student ID
        if (!isset($_POST['studentId']) || (isset($_POST['studentId']) && $_POST['studentId'] == '') || !preg_match('/^([0-9]){9}$/', $_POST['studentId'])) {
            $missingFieldList[] = 'studentId';
        }

        // Check term
        if (!isset($_POST['term'])) {
            $missingFieldList[] = 'term';
        }

        // Check Locations
        if (!isset($_POST['location']) || ($_POST['location'] != 'domestic' && $_POST['location'] != 'international')) {
            $missingFieldList[] = 'location';
        } else{
            // Check state, if domestic
            if ($_POST['location'] == 'domestic' && (!isset($_POST['state']) || $_POST['state'] == '-1')) {
                $missingFieldList[] = 'state';
            }

            // Check county, if international
            if ($_POST['location'] == 'international' && (!isset($_POST['country']) || $_POST['country'] == '-1')) {
                $missingFieldList[] = 'country';
            }
        }

        // Check Department
        if (!isset($_POST['department']) || (isset($_POST['department']) && $_POST['department'] === '-1')) {
            $missingFieldList[] = 'department';
        }

        // Check Host
        if (!isset($_POST['main_host']) || (isset($_POST['main_host']) && $_POST['main_host'] === '-1')) {
            $missingFieldList[] = 'host';
        }
        if (!isset($_POST['sub_host']) || (isset($_POST['sub_host']) && $_POST['sub_host'] === '-1')) {
            $missingFieldList[] = 'sub';
        }

        return $missingFieldList;
    }

    /**
     * Redirect to the add internship interface and highlight any missing fields
     */
    private function redirectToForm()
    {
        \NQ::close();
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowAddInternship');
    }
}
