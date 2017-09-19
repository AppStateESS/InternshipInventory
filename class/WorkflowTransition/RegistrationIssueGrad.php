<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\TermFactory;

class RegistrationIssueGrad extends WorkflowTransition {
    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'RegistrationIssueState';
    const actionName  = 'Mark as Registration Issue';

    const sortIndex = 6;

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function allowed(Internship $i)
    {
        if($i->isDistanceEd()){
            if(\Current_User::allow('intern', 'distance_ed_register')){
                return true;
            }else{
                return false;
            }
        }else{
            return parent::allowed($i);
        }

        return false;
    }

    public function isApplicable(Internship $i)
    {
        if($i->isGraduate()){
            return true;
        }else{
            return false;
        }
    }

    public function doNotification(Internship $i, $note = null)
    {
        $term = InternFactory::getTermByTermCode($i->getTerm());

        $email = new \Intern\Email\RegistrationIssueEmail(\Intern\InternSettings::getInstance(), $i, $term, $note);
        $email->send();
    }
}
