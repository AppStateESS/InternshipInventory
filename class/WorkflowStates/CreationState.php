<?php
class CreationState extends WorkflowState {
    const friendlyName = 'New Internship';
    
    public function getTransitions(Internship $i)
    {
        $creationTrans = new CreationTransition();
        return array($creationTrans);
    }
}
?>
