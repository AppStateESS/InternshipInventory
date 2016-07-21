<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email\SendRegistrationIssueEmail;

class RegistrationIssueUgrad extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'RegistrationIssueState';
    const actionName  = 'Mark as Registration Issue';

    const sortIndex = 6;

    public function getAllowedPermissionList(){
        return array('register');
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

    public function isApplicable(Internship $i)
    {
        if($i->isUndergraduate()){
            return true;
        }else{
            return false;
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();

        new SendRegistrationIssueEmail($i, $agency, $note);
    }
}
