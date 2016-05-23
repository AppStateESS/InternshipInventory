<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class UndoDeanApprove extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Return for Dean Approval';

    const sortIndex = 6;

    public function getAllowedPermissionList(){
        return array('dean_approve','register');
    }
}
