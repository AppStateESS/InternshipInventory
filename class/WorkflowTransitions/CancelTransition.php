<?php

class CancelTransition extends WorkflowTransition {
    //const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';
    
    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register');
    }
    
    public function getSourceState(){
        return array('NewState', 'SigAuthReadyState', 'SigAuthApprovedState', 'DeanApprovedState', 'GradSchoolApprovedState', 'RegistrationIssueState');
    }
}

?>