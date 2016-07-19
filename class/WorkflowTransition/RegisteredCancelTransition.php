<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email;

class RegisteredCancelTransition extends WorkflowTransition {
    const sourceState = 'RegisteredState';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';

    const sortIndex = 10;

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

    public function getSourceState(){
        return array('RegisteredState');
    }

    public function doNotification(Internship $i, $note = null)
    {
        InternshipCancelNotice::sendEmail($i);
    }
}
