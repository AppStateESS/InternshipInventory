<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\TermFactory;

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
            $term = TermFactory::getTermByTermCode($i->getTerm());

            $email = new \Intern\Email\IntlInternshipReinstateNotice(\Intern\InternSettings::getInstance(), $i, $term);
            $email->send();
        }
    }
}
