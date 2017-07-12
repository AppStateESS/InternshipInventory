<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class UndoDeanApprovalGradPending extends WorkflowTransition {

    const sourceState = 'DeanApprovedGradPendingState';
    const destState = 'SigAuthApprovedState';
    const actionName = 'Return for Dean Approval';

    const sortIndex = 6;

    public function getAllowedPermissionList() {
        return array('dean_approve','register');
    }

    public function isApplicable(Internship $i) {
        if ($i->isGraduate()) {
            return true;
        } else {
            return false;
        }
    }
}
