<?php
class CreationState extends WorkflowState {
    const friendlyName = 'New Internship';
    
    public function getTransitions(Internship $i)
    {
        PHPWS_Core::initModClass('intern', 'WorkflowTransitions/CreationTransition.php');
        $creationTrans = new CreationTransition();
        return array($creationTrans);
    }
}
?>