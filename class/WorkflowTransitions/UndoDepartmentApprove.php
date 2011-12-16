<?php

class UndoDepartmentApprove extends WorkflowTransition {
    const sourceState = 'SigAuthReadyState';
    const destState   = 'NewState';
    const actionName  = 'Send back to advisor';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
    }
}

?>