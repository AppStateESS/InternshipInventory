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
      $term = Term::getNextTerm($internship->getTerm());
      if($_REQUEST['next'] == 'two'){
        $newTerm = Term::getNextTerm($term);
      } else if($_REQUEST['next'] == 'three'){
        $twoTerm = Term::getNextTerm($term);
        $newTerm = Term::getNextTerm($twoTerm);
      }
      else{
        $newTerm = $term;
      }
      $internship->setTerm($newTerm);
      $copyId = $internship->save();

      // Show message if user edited internship
      \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Continued internship for ' . $internship->getFullName() . ' to ' . Term::rawToRead($newTerm) . '.');
      \NQ::close();
      return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $copyId);
    }
}
