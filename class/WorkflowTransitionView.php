<?php

class WorkflowTransitionView {
    
    private $state;
    private $form;
    private $internship;
    
    public function __construct(Internship $i, PHPWS_Form &$form){
        $this->internship = $i;
        $this->form = $form;
        
        $this->state = $this->internship->getWorkflowState();
        
        $this->form->useRowRepeat();
    }

    public function show()
    {
        $this->form->addTplTag('WORKFLOW_STATE', $this->state->getFriendlyName());
        
        $transitions = $this->state->getTransitions($this->internship);
        
        $radioButtons = array();
        
        foreach($transitions as $t){
            $radioButtons[$t->getName()] = $t->getActionName();
        }
        
        $this->form->addRadioAssoc('workflow_action', $radioButtons);

        $radio = $this->form->grab('workflow_action');
        
        //test($transitions,1);
        
        foreach($transitions as $t){
            if(!$t->allowed($this->internship)){
                // Set disabled
                $radio[$t->getName()]->setDisabled(true);
            }
        }
        
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