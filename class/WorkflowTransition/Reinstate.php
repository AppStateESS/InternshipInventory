<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email;

class Reinstate extends WorkflowTransition {
    const sourceState = 'CancelledState';
    const destState   = 'NewState';
    const actionName  = 'Reinstate';

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'dean_approve');
    }

    public function doNotification(Internship $i, $note = null)
    {
        if($i->isInternational()){
            $agency = $i->getAgency();

            Email::sendOIEDReinstateEmail($i, $agency);
        }
    }
}
