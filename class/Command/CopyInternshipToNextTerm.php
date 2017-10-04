<?php
namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\WorkflowStateFactory;
use \Intern\TermFactory;

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

        // Get the requested destination term and set it
        $destTermCode = $_REQUEST['destinationTerm'];
        $newTerm = TermFactory::getTermByTermCode($destTermCode);

        if($newTerm === null || $newTerm === false){
            throw new \InvalidArgumentException('Requested term does not exist: ' . $destTermCode);
        }

        $internship->setTerm($newTerm->getTermCode());

        // Save the new internship
        $copyId = $internship->save();

        // Show message if user edited internship
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Continued internship for ' . $internship->getFullName() . ' to ' . $newTerm->getDescription() . '.');
        \NQ::close();

        // Redirect to the new internship
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $copyId);
    }
}
