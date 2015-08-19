<?php

/**
 * Controller class to save a copy of an Internship for the next term
 *
 * @author jbooker
 * @package intern
 */
class CopyInternship {

    public function __construct()
    {

    }

    public function execute()
    {
        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'Faculty.php');

        $internship = InternshipFactory::getInternshipById($_REQUEST['internship_id']);


        $internship->setId(null);

        $internship->setStartDate(null);
        $internship->setEndDate(null);

        PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
        $state = WorkflowStateFactory::getState('CreationState');
        $internship->setState($state); // Set this initial value



        $validTerm = $internship->advanceTerm();



        if($validTerm)
        {
          $copyId = $internship->save();

          // Show message if user edited internship
          NQ::simple('intern', INTERN_SUCCESS, 'Saved new internship for ' . $internship->getFullName());
          NQ::close();
          return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&internship_id=' . $copyId);
        }
        else
        {
          // Show message if user edited internship
          NQ::simple('intern', INTERN_ERROR, 'Failed to Save new internship for ' . $internship->getFullName() . ' next term not yet available.');
          NQ::close();
          return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&internship_id=' . $_REQUEST['internship_id']);
        }

    }
}
