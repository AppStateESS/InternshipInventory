<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();
        
        // If this is an undergrad internship, then send the Registrar an email
        // Graduate level internships have another workflow state to go through before we alert the Registrar
        if($i->isUndergraduate()){
            Email::sendRegistrarEmail($i, $agency);
        }
        
        // If this is a graduate email, send the notification email to the grad school office
        if($i->isGraduate()){
            Email::sendGradSchoolNotification($i, $agency);
        }
    }
}

?>
