<?php

class SigAuthApprove extends WorkflowTransition {
    const sourceState = 'SigAuthReadyState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Approved by Signature Authority';

    public function getAllowedPermissionList(){
        return array('sig_auth');
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