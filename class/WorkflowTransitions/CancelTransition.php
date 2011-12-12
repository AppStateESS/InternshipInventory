<?php

class CancelTransition extends WorkflowTransition {
    const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';
    
    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approver', 'sig_auth', 'registrar');
    }
}

?>