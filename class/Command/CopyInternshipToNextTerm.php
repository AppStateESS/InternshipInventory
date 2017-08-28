<?php
namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\WorkflowStateFactory;
use \Intern\Term;

/**
 * Controller class to save a copy of an Internship for the next term
 *
 * @author csdetsch
 * @author jbooker
 * @package intern
 */
class CopyInternshipToNextTerm {

    public function __construct()
    {

    }

    public function execute()
    {
        // Load the existing internship using its ID
        $internship = InternshipFactory::getInternshipById($_REQUEST['internshipId']);

        // Clear the ID so that insert a new internship into the database the
        // next time we call save()
        $internship->setId(null);

        // Clear/reset additional values from the existing internship
        $internship->setStartDate(null);
        $internship->setEndDate(null);

        $state = WorkflowStateFactory::getState('CreationState');
        $internship->setState($state); // Set initial WorkflowState


        // Get the requested destination term
        $destTerm = $_REQUEST['destinationTerm'];

        // Check if the destination term exists
        if(!Term::termExists($destTerm)){
            throw new \InvalidArgumentException('Requested term does not exist: ' . $destTerm);
        }
        $internship->setTerm($destTerm);

        // Save the new internship
        $copyId = $internship->save();

        // Show confirmation message
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Continued internship for ' . $internship->getFullName() . ' to ' . Term::rawToRead($destTerm) . '.');
        \NQ::close();

        // Redirect to the new internship
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $copyId);
    }
}
