<?php

class Registration extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Registered';

    public function getAllowedPermissionList(){
        return array('registrar');
    }
    
    public function onTransition(Internship $i)
    {
        
    }
    
    public function doNotification(Internship $i)
    {
        $student = $i->getStudent();
        
        $agency = $i->getAgency();
        
        PHPWS_Core::initModClass('intern', 'Email.php');
        Email::sendRegistrarEmail($student, $i, $agency);
    }
}

?>