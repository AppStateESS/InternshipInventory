<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Email;

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function doNotification(Internship $i, $note = null)
    {
        $agency = $i->getAgency();

        // If this is an undergrad internship, then send the Registrar an email
        // Graduate level internships have another workflow state to go through before we alert the Registrar
        if($i->isUndergraduate()){
            //Email::sendRegistrarEmail($i, $agency);
        }

        // If this is a graduate email, send the notification email to the grad school office
        if($i->isGraduate()){
            //Email::sendGradSchoolNotification($i, $agency);
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

?>
