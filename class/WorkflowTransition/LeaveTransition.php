<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;

class LeaveTransition extends WorkflowTransition {
    const sourceState = '*';
    const destState   = null;
    
    const sortIndex = 0;

    public function getActionName(){
        return 'Leave as ' . $this->source->getFriendlyName();
    }
    
    public function allowed(Internship $i){
        return true;
    }
    
    public function getAllowedPermissionList(){
        return array();
    }
}

?>