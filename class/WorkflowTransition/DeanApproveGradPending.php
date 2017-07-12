<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class DeanApproveGradPending extends WorkflowTransition {

    const sourceState = 'SigAuthApprovedState';
    const destState = 'DeanApprovedGradPendingState';
    const actionName = 'Mark as Dean Approved / Needs Graduate School Approval';

    public function getAllowedPermissionList()
    {
        return array('dean_approve');
    }

    public function isApplicable(Internship $i)
    {
        if ($i->isGraduate()) {
            return true;
        } else {
            return false;
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $settings = \Intern\InternSettings::getInstance();

        //Send the notification email to the grad school office.
        $email = new \Intern\Email\GradSchoolNotificationEmail($settings, $i);
        $email->send();
    }
}
