<?php

class SigAuthApprove extends WorkflowTransition {
    const sourceState = 'DeptApprovedState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Approved by Signature Authority';

    public function getAllowedPermissionList(){
        return array('sig_auth');
    }
}

?>