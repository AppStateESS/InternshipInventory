<?php

class CreationTransition extends WorkflowTransition {
    const sourceState = 'CreationState';
    const destState   = 'NewState';
    const actionName  = 'New Internship';
    
    public function getAllowedPermissionList(){
        return array('dept_approver','sig_auth');
    }
    
    public function doNotification(Internship $i)
    {
        $student = $i->getStudent();
    
        PHPWS_Core::initModClass('intern', 'Email.php');
        Email::sendIntlInternshipCreateNotice($student, $i);
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
        return 'CreationTransition';
    }
}

?>