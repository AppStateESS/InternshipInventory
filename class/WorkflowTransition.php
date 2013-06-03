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
    
    /**
     * Determines if this transition is applicable to the given Internship. If not,
     * the transition will not be shown in the list of actions for the user.
     * NB: This is distinct from the allowed() method because a return value of false
     * from allowed() will show the action as a disabled (grey'd out) in the list of
     * actions.
     * 
     * Returns true by default, unless overridden by a child class.
     * 
     * @param Internship $i
     * @return boolean
     */
    public function isApplicable(Internship $i){
        return true; // Returns true by default
    }
    
    public function allowed(Internship $i)
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
    
    public function doNotification(Internship $i, $note = null)
    {
        // Do nothing by default. Send notifications here.
    }
    
    public function checkRequiredFields(Internship $i)
    {
        // Do nothing by default.
        // Check for required data here before allowing internship to complete this transition.
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