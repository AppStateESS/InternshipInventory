<?php

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
            
            PHPWS_Core::initModClass('intern', 'Email.php');
            Email::sendRegistrarEmail($i, $agency);
        }
    }
}

?>