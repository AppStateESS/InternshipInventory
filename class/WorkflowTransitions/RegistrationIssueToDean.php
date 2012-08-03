<?php

class RegistrationIssueToDean extends WorkflowTransition {
    const sourceState = 'RegistrationIssueState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Return for Dean Approval';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('register');
    }
}

?>