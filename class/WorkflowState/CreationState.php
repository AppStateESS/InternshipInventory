<?php
namespace Intern\WorkflowState;
use Intern\WorkflowState;
use Intern\Internship;
use Intern\WorkflowTransition\CreationTransition;

class CreationState extends WorkflowState {
    const friendlyName = 'New Internship';
    
    public function getTransitions(Internship $i)
    {
        $creationTrans = new CreationTransition();
        return array($creationTrans);
    }
}
?>
