<?php
class CreationState extends WorkflowState {
    const friendlyName = 'New Internship';
    
    public function getTransitions()
    {
        PHPWS_Core::initModClass('intern', 'WorkflowTransitions/CreationTransition.php');
        $creationTrans = new CreationTransition();
        return array($creationTrans);
    }
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
}
?>