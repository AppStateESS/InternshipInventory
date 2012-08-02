<?php

class UndoSigAuthApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'SigAuthReadyState';
    const actionName  = 'Send back to Signature Authority';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('sig_auth_approve','register');
    }
}

?>