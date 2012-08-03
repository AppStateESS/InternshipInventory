<?php

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function doNotification(Internship $i, $note = null)
    {
        
        // If this is an undergrad internship, then send the Registrar an email
        // Graduate level internships have another workflow state to go through before we alert the Registrar
        if($i->isUndergraduate()){
            $agency = $i->getAgency();

            PHPWS_Core::initModClass('intern', 'Email.php');
            Email::sendRegistrarEmail($i, $agency);
        }
    }
}

?>