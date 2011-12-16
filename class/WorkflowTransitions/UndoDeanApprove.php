<?php

class UndoDeanApprove extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Return for Dean Approval';

    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('dean_approver');
    }
    
    public function onTransition(Internship $i)
    {
        
    }
}

?>