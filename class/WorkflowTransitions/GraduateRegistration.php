<?php

class GraduateRegistration extends WorkflowTransition {
    
    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete (grad)';
    
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
    
        PHPWS_Core::initModClass('intern', 'Email.php');
        Email::sendRegistrationConfirmationEmail($i, $agency);
    }
}