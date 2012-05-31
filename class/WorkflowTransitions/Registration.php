<?php

class Registration extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete';

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function allowed(Internship $i)
    {
        if($i->isDistanceEd()){
            if(Current_User::allow('intern', 'distance_ed_register')){
                return true;
            }
        }else{
            return parent::allowed($i);
        }

        return false;
    }

    public function doNotification(Internship $i)
    {
        $agency = $i->getAgency();

        PHPWS_Core::initModClass('intern', 'Email.php');
        Email::sendRegistrationConfirmationEmail($i, $agency);
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
        return 'Registration';
    }
}

?>