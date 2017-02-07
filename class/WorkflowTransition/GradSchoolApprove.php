<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class GradSchoolApprove extends WorkflowTransition {

    const sourceState = 'DeanApprovedState';
    const destState   = 'GradSchoolApprovedState';
    const actionName  = 'Mark as Grad School Approved';

    public function getAllowedPermissionList(){
        return array('grad_school_approve');
    }

    public function isApplicable(Internship $i){
        if($i->isGraduate()){
            return true;
        }else{
            return false;
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $email = new \Intern\Email\ReadyToRegisterEmail(\Intern\InternSettings::getInstance(), $i);
        $email->send();
    }
}
