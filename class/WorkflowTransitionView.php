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
        
        // Generate the array of radio buttons to add (one for each possible transition)
        $radioButtons = array();
        
        foreach($transitions as $t){
            $radioButtons[$t->getName()] = $t->getActionName();
        }

        // Add the radio buttons to the form
        $this->form->addRadioAssoc('workflow_action', $radioButtons);

        // Find and disable any transitions that aren't allowed (but still show them in the list)
        $radio = $this->form->grab('workflow_action');
        
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