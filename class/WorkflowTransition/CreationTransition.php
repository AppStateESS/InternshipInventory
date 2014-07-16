<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class CreationTransition extends WorkflowTransition {
    const sourceState = 'CreationState';
    const destState   = 'NewState';
    const actionName  = 'New Internship';
    
    public function getAllowedPermissionList(){
        return array('create_internship');
    }
    
    public function doNotification(Internship $i, $note = null)
    {
        if(!$i->isDomestic()){
            Email::sendIntlInternshipCreateNotice($i);
            Email::sendIntlInternshipCreateNoticeStudent($i);
        }
    }
}

?>
