<?php

class UndoSigAuthApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'SigAuthReadyState';
    const actionName  = 'Send back to Signature Authority';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('sig_auth','registrar');
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