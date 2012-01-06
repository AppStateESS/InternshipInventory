<?php

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }
    
    public function allowed(Internship $i)
    {
        $student = $i->getStudent();
        if($student->campus == 'distance_ed'){
            if(Current_User::allow('intern', 'distance_ed_dean_approve')){
                return true;
            }
        }else{
            $perms = $this->getAllowedPermissionList();
            foreach($perms as $p){
                if(Current_User::allow('intern', $p)){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function onTransition(Internship $i)
    {
        
    }
    
    public function getActionName()
    {
        return self::actionName;
    }
    
    public function getSourceState(){
        return self::sourceState;
    }
    
    public function getDestState(){
        return self::destState;
    }
    
    public function getSortIndex(){
        return self::sortIndex;
    }
    
    public function getName(){
        return 'DeanApprove';
    }
}

?>