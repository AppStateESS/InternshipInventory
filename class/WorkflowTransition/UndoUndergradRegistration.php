<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class UndoUndergradRegistration extends WorkflowTransition {
    const sourceState = 'RegisteredState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Not Registered';

    const sortIndex = 6;

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function isApplicable(Internship $i)
    {
        if($i->isUndergraduate()){
            return true;
        }else{
            return false;
        }
    }
}
