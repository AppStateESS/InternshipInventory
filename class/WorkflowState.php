<?php

namespace Intern;

abstract class WorkflowState {

    const friendlyName = '';
    const sortIndex    = 5;
    
    /**
     * Returns an array of the valid WorkflowActions for this State.
     * @return Array<WorkflowAction>
     */
    public function getTransitions(Internship $i)
    {
        return WorkflowTransitionFactory::getTransitionsFromState($this, $i);
    }
    
    /**
     * Called when an action causes this state to become the current state.
     */
    public function onEnter(WorkflowAction $action)
    {
        
    }
    
    /**
     * After an action occurs, this is called just before leaving this state. 
     */
    public function onExit(WorkflowAction $action)
    {
        
    }
    
    public function getName(){
        return get_called_class();
    }
    
    public function getFriendlyName(){
        $class = $this->getName();
        return $class::friendlyName;
    }
    
    public function getSortIndex()
    {
        $class = get_called_class();
        return $class::sortIndex;
    }
}

?>
