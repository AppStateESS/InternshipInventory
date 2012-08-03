<?php

PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
PHPWS_Core::initModClass('intern', 'ChangeHistory.php');

class WorkflowController {
    
    private $internship;
    private $t;
    
    public function __construct(Internship $i, WorkflowTransition $t)
    {
        $this->internship = $i;
        $this->t = $t;
    }
    
    public function doTransition($note = null)
    {
        // Make sure the transition makes sense based on the current state of the internship
        $currStateName = $this->internship->getStateName();

        $sourceStateName = $this->t->getSourceState();
        
        if(is_array($sourceStateName)){
            if(!in_array($currStateName, $sourceStateName)){
                throw new InvalidArgumentException('Invalid transition source state.');
            }
        }else if($sourceStateName != '*' && $sourceStateName != $currStateName){
            throw new InvalidArgumentException('Invalid transition source state.');
        }
        
        if(!$this->t->allowed($this->internship)){
            throw new Exception("You do not have permission to set the internship to the requested status.");
        }
        
        $sourceState = WorkflowStateFactory::getState($currStateName);
        
        $destStateName = $this->t->getDestState();
        if($destStateName == null){
            // No destination state, so see if we need to add a note (no state change)
            if(!is_null($note)){
                $changeHistory = new ChangeHistory($this->internship, Current_User::getUserObj(), time(), $sourceState, $sourceState, $note);
                $changeHistory->save();
            }
            return;
        }

        $destState = WorkflowStateFactory::getState($destStateName);
        
        $this->t->onTransition($this->internship);
        
        $this->internship->setState($destState);
        $this->internship->save();

        $changeHistory = new ChangeHistory($this->internship, Current_User::getUserObj(), time(), $sourceState, $destState, $note);
        $changeHistory->save();
    }
    
    public function doNotification($note = null){
        $this->t->doNotification($this->internship, $note);
    }
}

?>