<?php

class RegistrationIssueGrad extends WorkflowTransition {
    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'RegistrationIssueState';
    const actionName  = 'Mark as Registration Issue';
    
    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('register');
    }
    
    public function isApplicable(Internship $i)
    {
        if($i->isGraduate()){
            return true;
        }else{
            return false;
        }
    }
    
    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();
    
        Email::sendRegistrationIssueEmail($i, $agency, $note);
    }
}

?>
