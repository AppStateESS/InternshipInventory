<?php

class UndoSigAuthApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeptApprovedState';
    const actionName  = 'Send back to Signature Authority';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('sig_auth','registrar');
    }
}

?>