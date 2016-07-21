<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email\SendRegistrationConfirmationEmail;
use Intern\Exception\MissingDataException;

class UndergradRegistration extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete';

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function isApplicable(Internship $i)
    {
        if($i->isUndergraduate()){
            return true;
        }else{
            return false;
        }
    }

    public function allowed(Internship $i)
    {
        if($i->isDistanceEd()){
            if(\Current_User::allow('intern', 'distance_ed_register')){
                return true;
            }else{
                return false;
            }
        }
        else
        {
            return parent::allowed($i);
        }

    }

    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();

        new SendRegistrationConfirmationEmail($i, $agency);
    }

    public function checkRequiredFields(Internship $i)
    {
        if (!$i->isSecondaryPart()) {
            // Check the course subject
            $courseSubj = $i->getSubject();
            if (!isset($courseSubj) || $courseSubj == '' || $courseSubj->id == 0) {
                throw new MissingDataException("Please select a course subject.");
            }

            // Check the course number
            $courseNum = $i->getCourseNumber();
            if (!isset($courseNum) || $courseNum == '') {
                throw new MissingDataException("Please enter a course number.");
            }

            // Check the course section number
            $sectionNum = $i->getCourseSection();
            if (!isset($sectionNum) || $sectionNum == '') {
                throw new MissingDataException("Please enter a course section number.");
            }

            // Check the course credit hours field
            $creditHours = $i->getCreditHours();
            if (!isset($creditHours) || $creditHours === '') {
                throw new MissingDataException("Please enter the number of course credit hours.");
            }

            /*
            if(!\Current_User::isDeity() && $creditHours <= 0){
                throw new MissingDataException("The number of course credit hours should be greater than zero.");
            }
            */
        }
    }
}
