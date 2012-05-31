<?php

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function doNotification(Internship $i)
    {
        $agency = $i->getAgency();

        PHPWS_Core::initModClass('intern', 'Email.php');
        Email::sendRegistrarEmail($i, $agency);
    }

    public function onTransition(Internship $i)
    {

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
        return 'DeanApprove';
    }
}

?>