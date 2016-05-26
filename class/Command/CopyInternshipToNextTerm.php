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
        $internship = InternshipFactory::getInternshipById($_REQUEST['internship_id']);

        // Clear values from the existing internship that must be reset
        $internship->setId(null);

        $internship->setStartDate(null);
        $internship->setEndDate(null);

        $state = WorkflowStateFactory::getState('CreationState');
        $internship->setState($state); // Set this initial value

        // Calculate the new term and set it
        $newTerm = Term::getNextTerm($internship->getTerm());
        $internship->setTerm($newTerm);

        $copyId = $internship->save();

        // Show message if user edited internship
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Continued internship for ' . $internship->getFullName() . ' to ' . Term::rawToRead($newTerm) . '.');
        \NQ::close();
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $copyId);
    }
}
