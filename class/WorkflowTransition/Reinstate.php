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
        return array('dept_approve', 'sig_auth_approve');
    }

    public function doNotification(Internship $i, $note = null)
    {
        // Should always be a grad-level internship if we've made it to this transition, but double check
        if($i->isInternational()){
            $agency = $i->getAgency();

            Email::sendOIEDReinstateEmail($i, $agency);
        }
    }
}

?>