<?php

class UndoRegistration extends WorkflowTransition {
    const sourceState = 'RegisteredState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as not registered';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('registrar');
    }
    
    public function getActionName()
    {
        return self::actionName;
    }
    
    public function getSourceState(){
        return self::sourceState;
    }
    
    public function getDestState(){
        return self::destState;
    }
    
    public function getSortIndex(){
        return self::sortIndex;
    }
}

?>