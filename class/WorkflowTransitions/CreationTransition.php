<?php

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
            PHPWS_Core::initModClass('intern', 'Email.php');
            Email::sendIntlInternshipCreateNotice($i);
            Email::sendIntlInternshipCreateNoticeStudent($i);
        }
    }
}
