<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\ExpectedCourseFactory;
use Intern\Email\UnusualCourseEmail;
use \Intern\InternSettings;
use Intern\Exception\MissingDataException;

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function checkRequiredFields(Internship $i){
        // Course number is required so we can check against the expected insurance list in doNotification()
        if($i->getCourseNumber() === null || $i->getCourseNumber() === ''){
            throw new MissingDataException("Please enter a course number.");
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $settings = \Intern\InternSettings::getInstance();

        // If this is an undergrad internship, then send the Registrar an email
        // Graduate level internships have another workflow state to go through before we alert the Registrar
        if($i->isUndergraduate()){
            $email = new \Intern\Email\ReadyToRegisterEmail($settings, $i);
            $email->send();
        }

        // If this is a graduate email, send the notification email to the grad school office
        if($i->isGraduate()){
            $email = new \Intern\Email\GradSchoolNotificationEmail($settings, $i);
            $email->send();
        }

        // If the subject and course number are not registered with InternshipInventory,
        // send an email to the appropriate receiver.
        if (!ExpectedCourseFactory::isExpectedCourse($i->getSubject(), $i->getCourseNumber())) {
            $email = new UnusualCourseEmail(InternSettings::getInstance(), $i);
            $email->send();
        }
    }
}
