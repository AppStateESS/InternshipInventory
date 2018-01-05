<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern;

class WorkflowTransitionView {

    private $state;
    private $form;
    private $internship;

    public function __construct(Internship $i, \PHPWS_Form &$form){
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

        if($this->state instanceof \Intern\WorkflowState\CreationState){
            // New Internship, only option is 'create' transitions
            $this->form->setMatch('workflow_action', 'Intern\WorkflowTransition\CreationTransition');
        }else{
            // Existing internship, default is to leave in current state
            $this->form->setMatch('workflow_action', 'Intern\WorkflowTransition\LeaveTransition');
        }
    }
}
