<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email;

class CancelTransition extends WorkflowTransition {
    //const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';
    
    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register');
    }
    
    public function getSourceState(){
        return array('NewState', 'SigAuthReadyState', 'SigAuthApprovedState', 'DeanApprovedState', 'GradSchoolApprovedState', 'RegistrationIssueState');
    }

    public function doNotification(Internship $i, $note = null)
    {
        // Should always be a grad-level internship if we've made it to this transition, but double check
        if($i->isInternational()){
            $agency = $i->getAgency();

            Email::sendOIEDCancellationEmail($i, $agency);
        }
    }
}

?>