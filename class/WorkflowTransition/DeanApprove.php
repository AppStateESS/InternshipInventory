<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\ExpectedCourseFactory;
use Intern\Email\UnusualCourseEmail;
use \Intern\InternSettings;

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function isApplicable(Internship $i){
        if ($i->isUndergraduate()){
            return true;
        }else{
            return false;
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $settings = \Intern\InternSettings::getInstance();

        /**
         * Send the Registrar an email.
         * Graduate level internships have another workflow state to go through
         * before we alert the Registrar.
         */
        $email = new \Intern\Email\ReadyToRegisterEmail($settings, $i);
        $email->send();

        // If the subject and course number are not registered with InternshipInventory,
        // send an email to the appropriate receiver.
        if (!ExpectedCourseFactory::isExpectedCourse($i->getSubject(), $i->getCourseNumber())) {
            $email = new UnusualCourseEmail(InternSettings::getInstance(), $i);
            $email->send();
        }
    }
}
