<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class Reinstate extends WorkflowTransition {
    const sourceState = 'CancelledState';
    const destState   = 'NewState';
    const actionName  = 'Reinstate';

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve');
    }
}
