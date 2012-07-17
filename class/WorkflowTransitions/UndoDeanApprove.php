<?php

class UndoDeanApprove extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Return for Dean Approval';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('dean_approve','register');
    }
    
    public function onTransition(Internship $i)
    {
        
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
    
    public function getName(){
        return 'UndoDeanApprove';
    }
}

?>