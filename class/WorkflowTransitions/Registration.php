<?php

class Registration extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Registered';

    public function getAllowedPermissionList(){
        return array('registrar');
    }
}

?>