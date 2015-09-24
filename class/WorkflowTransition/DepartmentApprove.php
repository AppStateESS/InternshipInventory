<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class DepartmentApprove extends WorkflowTransition {
    const sourceState = 'NewState';
    const destState   = 'SigAuthReadyState';
    const actionName  = 'Forward to Signature Authority';

    public function getAllowedPermissionList(){
        return array('dept_approve','sig_auth_approve');
    }
}

?>