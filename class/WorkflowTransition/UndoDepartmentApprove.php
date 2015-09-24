<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class UndoDepartmentApprove extends WorkflowTransition {
    const sourceState = 'SigAuthReadyState';
    const destState   = 'NewState';
    const actionName  = 'Send back to advisor';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('dept_approve','sig_auth_approve');
    }
}

?>