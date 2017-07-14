<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class CancelTransition extends WorkflowTransition {
    //const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';

    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register', 'dean_approve');
    }

    public function getSourceState(){
        return array('NewState', 'SigAuthReadyState', 'SigAuthApprovedState', 'DeanApprovedState', 'DeanApprovedGradPendingState', 'GradSchoolApprovedState', 'RegistrationIssueState');
    }

    public function doNotification(Internship $i, $note = null)
    {
        $settings = \Intern\InternSettings::getInstance();

        $email = new \Intern\Email\CancelInternshipNotice($settings, $i);
        $email->send();

        if($i->isInternational()){
            $email = new \Intern\Email\IntlInternshipCancelNotice($settings, $i);
            $email->send();
        }
    }
}
