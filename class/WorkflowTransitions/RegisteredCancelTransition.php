<?php

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
            PHPWS_Core::initModClass('intern', 'Email.php');
            Email::sendInternshipCancelNotice($i);
    }
}

?>
