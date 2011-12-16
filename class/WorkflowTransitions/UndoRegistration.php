<?php

class UndoRegistration extends WorkflowTransition {
    const sourceState = 'RegisteredState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as not registered';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('registrar');
    }
}

?>