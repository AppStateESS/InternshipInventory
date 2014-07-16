<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class RegisteredCancelTransition extends WorkflowTransition {
    const sourceState = 'RegisteredState';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';
    
    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('register');
    }
    
    public function getSourceState(){
        return array('RegisteredState');
    }
    
    public function doNotification(Internship $i, $note = null)
    {
        Email::sendInternshipCancelNotice($i);
    }
}

?>
