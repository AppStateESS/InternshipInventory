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
        $db = \Database::newDB();
        $pdo = $db->getPDO();
        $sql = "SELECT id FROM intern_courses
                WHERE subject_id=:subject_id and course_num=:cnum";
        $sth = $pdo->prepare($sql);
        
        $sth->execute(array('subject_id'=>$i->getSubject()->getId(), 'cnum'=>$i->getCourseNumber()));
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if (sizeof($result) == 0)
        {
            Email::sendUnusualCourseEmail($i, $agency);
        }
    }
}
