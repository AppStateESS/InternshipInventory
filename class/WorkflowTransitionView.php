<?php

class WorkflowTransitionView {
    
    private $state;
    private $form;
    
    public function __construct(WorkflowState $state, PHPWS_Form $form){
        $this->state = $state;
        $this->form = $form;
        
        $this->form->useRowRepeat();
    }

    public function show()
    {
        $this->form->addTplTag('WORKFLOW_STATE', $this->state->getFriendlyName());
        
        $transitions = $this->state->getTransitions();
        
        $radioButtons = array();
        
        foreach($transitions as $t){
            $radioButtons[$t->getName()] = $t->getActionName();
        }
        
        $this->form->addRadioAssoc('workflow_action', $radioButtons);
        
        if($this->state->getName() == 'CreationState'){
            // New Internship, only option is 'create' transitions
            $this->form->setMatch('workflow_action', 'CreationTransition');
        }else{
            // Existing internship, default is to leave in current state
            $this->form->setMatch('workflow_action', 'LeaveTransition');
        }
    }
}

?>