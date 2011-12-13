<?php

PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');

class WorkflowController {
    
    private $internship;
    private $t;
    
    public function __construct(Internship $i, WorkflowTransition $t)
    {
        $this->internship = $i;
        $this->t = $t;
    }
    
    public function doTransition()
    {
        // Make sure the transition makes sense based on the current state of the internship
        $stateName = $this->internship->getStateName();

        $sourceState = $this->t->getSourceState();
        
        if($sourceState != '*' && $sourceState != $stateName){
            throw new InvalidArgumentException('Invalid transition source state.');
        }
        
        if(!$this->t->allowed()){
            throw new Exception("You do not have permission to set the internship to the requested status.");
        }
        
        
        
        $destStateName = $this->t->getDestState();
        if($destStateName == null){
            // No destination state, so we're done here.
            return;
        }

        $destState = WorkflowStateFactory::getState($destStateName);
        
        $this->t->onTransition($this->internship);
        
        $this->internship->setState($destState);
        $this->internship->save();
    }
    
    public function doNotification(){
        $this->t->doNotification($this->internship);
    }
}

?>