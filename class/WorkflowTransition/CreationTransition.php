<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\TermFactory;

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
            $settings = \Intern\InternSettings::getInstance();

            $term = TermFactory::getTermByTermCode($i->getTerm());

            $email = new \Intern\Email\IntlInternshipCreateNotice($settings, $i, $term);
            $email->send();

            $email = new \Intern\Email\IntlInternshipCreateNoticeStudent($settings, $i, $term);
            $email->send();
        }
    }
}
