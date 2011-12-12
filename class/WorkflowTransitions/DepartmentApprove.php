<?php

class DepartmentApprove extends WorkflowTransition {
    const sourceState = 'NewState';
    const destState   = 'DeptApprovedState';
    const actionName  = 'Approve (department)';

    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
    }
}

?>