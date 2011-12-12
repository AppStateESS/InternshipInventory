<?php

class CreationTransition extends WorkflowTransition {
    const sourceState = 'CreationState';
    const destState   = 'NewState';
    const actionName  = 'New Internship';
    
    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
    }
}

?>