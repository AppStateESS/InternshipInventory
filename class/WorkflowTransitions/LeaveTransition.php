<?php

class LeaveTransition extends WorkflowTransition {
    const sourceState = '*';
    const destState   = null;
    
    const sortIndex = 0;

    public function getActionName(){
        return 'Leave as ' . $this->source->getFriendlyName();
    }
    
    public function allowed(){
        return true;
    }
    
    public function getAllowedPermissionList(){
        return array();
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
        return 'LeaveTransition';
    }
    
}

?>