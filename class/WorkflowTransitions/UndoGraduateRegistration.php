<?php

class UndoGraduateRegistration extends WorkflowTransition {
    
    const sourceState = 'RegisteredState';
    const destState   = 'GradSchoolApprovedState';
    const actionName  = 'Mark as not registered';
    
    const sortIndex = 6;
    
    public function getAllowedPermissionList(){
        return array('register');
    }
    
    public function isApplicable(Internship $i)
    {
        if($i->isGraduate()){
            return true;
        }else{
            return false;
        }
    }
}

?>