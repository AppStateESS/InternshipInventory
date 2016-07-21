<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email\SendOIEDCancellationEmail;

class CancelTransition extends WorkflowTransition {
    //const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';

    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register', 'dean_approve');
    }

    public function getSourceState(){
        return array('NewState', 'SigAuthReadyState', 'SigAuthApprovedState', 'DeanApprovedState', 'GradSchoolApprovedState', 'RegistrationIssueState');
    }

    public function doNotification(Internship $i, $note = null)
    {
        if($i->isInternational()){
            $agency = $i->getAgency();

            new SendOIEDCancellationEmail($i, $agency);
        }
    }
}
