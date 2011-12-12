<?php

PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');

class WorkflowController {
    
    private $internship;
    
    public function __construct(Internship $i)
    {
        $this->internship = $i;
    }
    
    public function doTransition(WorkflowTransition $t)
    {
        // Make sure the transition makes sense based on the current state of the internship
        $stateName = $this->internship->getStateName();

        $sourceState = $t->getSourceState();
        
        if($sourceState != '*' && $sourceState != $stateName){
            throw new InvalidArgumentException('Invalid transition source state.');
        }
        
        if(!$t->allowed()){
            throw new Exception("You do not have permission to set the internship to the requested status.");
        }
        
        
        
        $destStateName = $t->getDestState();
        if($destStateName == null){
            // No destination state, so we're done here.
            return;
        }
        
        $destState = WorkflowStateFactory::getState($destStateName);
        
        $this->internship->setState($destState);
        
        /* Order is important here. Be sure to try saving the new state before we
         * send any notifications, so that notification don't go out if the save
         * fails (and the save still happens even if the notifications fail). 
         */
        $this->internship->save();
        
        $t->onTransition($this->internship);
    }
}

?>