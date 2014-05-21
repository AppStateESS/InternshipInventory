<?php

class RegisteredCancelTransition extends WorkflowTransition {
    const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';
    
    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register');
    }
    
    public function getSourceState(){
        return array('RegisteredState');
    }
    
    public function doNotification(Internship $i, $note = null)
    {
            PHPWS_Core::initModClass('intern', 'Email.php');
            Email::sendIntlInternshipCancelNoticeStudent($i);
    }
}

?>