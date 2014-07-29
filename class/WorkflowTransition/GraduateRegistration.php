<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Exception\MissingDataException;
use Intern\Email;

class GraduateRegistration extends WorkflowTransition {

    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete (grad)';

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function isApplicable(Internship $i)
    {
        if($i->isGraduate()){
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
    	}else{
    		return parent::allowed($i);
    	}

    	return false;
    }

    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();

        Email::sendRegistrationConfirmationEmail($i, $agency);
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
            if (!isset($creditHours) || $creditHours == '') {
                throw new MissingDataException("Please enter the number of course credit hours.");
            }
        }
    }
}
