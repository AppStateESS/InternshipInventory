<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class RegisterAfterIssue extends WorkflowTransition {
    const sourceState = 'RegistrationIssueState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete';

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

    public function doNotification(Internship $i, $note = null)
    {
        $email = new \Intern\Email\RegistrationConfirmationEmail(\Intern\InternSettings::getInstance(), $i);
        $email->send();
    }
}
