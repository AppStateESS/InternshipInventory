<?php

class DepartmentApprove extends WorkflowTransition {
    const sourceState = 'NewState';
    const destState   = 'SigAuthReadyState';
    const actionName  = 'Forward to Signature Authority';

    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
    }
}

?>