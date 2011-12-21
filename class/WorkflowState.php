<?php

PHPWS_Core::initModClass('intern', 'WorkflowTransitionFactory.php');

abstract class WorkflowState {

    const friendlyName = '';
    
    /**
     * Returns an array of the valid WorkflowActions for this State.
     * @return Array<WorkflowAction>
     */
    public function getTransitions()
    {
        return WorkflowTransitionFactory::getTransitionsFromState($this);
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
    
    /** Commented out for php 5.1 support, this requires php 5.3
    public function getFriendlyName(){
        $class = $this->getName();
        return $class::friendlyName;
    }
    **/
}

?>