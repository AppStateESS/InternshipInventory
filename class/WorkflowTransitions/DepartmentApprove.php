<?php

class DepartmentApprove extends WorkflowTransition {
    const sourceState = 'NewState';
    const destState   = 'SigAuthReadyState';
    const actionName  = 'Forward to Signature Authority';

    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
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
        return 'DepartmentApprove';
    }
}

?>