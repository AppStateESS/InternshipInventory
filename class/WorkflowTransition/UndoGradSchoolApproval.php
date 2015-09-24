<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class UndoGradSchoolApproval extends WorkflowTransition {
    
    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Return for Graduate School Approval';
    
    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('register', 'grad_school_approve');
    }
    
    public function isApplicable(Internship $i)
    {
        if($i->isGraduate()){
            return true;
        }else{
            return false;
        }
    }
}