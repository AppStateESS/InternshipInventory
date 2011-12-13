<?php

abstract class WorkflowTransition {
    const sourceState = ''; // Name of Source/Dest states
    const destState   = '';
    const actionName  = '';
    const sortIndex   = 5;
    
    protected $source;
    
    public function __construct(){

    }
    
    abstract function getAllowedPermissionList();
    
    public function allowed()
    {
        $perms = $this->getAllowedPermissionList();
        
        foreach($perms as $p){
            if(Current_User::allow('intern', $p)){
                return true;
            }
        }
        
        return false;
    }
    
    public function setSourceState(WorkflowState $state)
    {
        $this->source = $state;
    }

    public function onTransition(Internship $i)
    {
        // Do nothing by default.
    }
    
    public function doNotification(Internship $i)
    {
        // Do nothign by default. Send notifications here.
    }
    
    public function getName()
    {
        return get_called_class();
    }
    
    public function getActionName()
    {
        $class = get_called_class();
        return $class::actionName;
    }
    
    public function getSourceState()
    {
        $class = get_called_class();
        return $class::sourceState;
    }
    
    public function getDestState()
    {
        $class = get_called_class();
        return $class::destState;
    }
    
    public function getSortIndex()
    {
        $class = get_called_class();
        return $class::sortIndex;
    }
}

?>