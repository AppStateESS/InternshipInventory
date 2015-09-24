<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email;

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
        // Should always be a grad-level internship if we've made it to this transition, but double check
        if($i->isGraduate()){
            $agency = $i->getAgency();

            Email::sendRegistrarEmail($i, $agency);
        }
    }
}

?>
