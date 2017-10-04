<?php

namespace Intern;

abstract class WorkflowState {

    const friendlyName = '';
    const sortIndex    = 5;

    /**
     * Returns an array of the valid WorkflowTransitions for this State.
     * @return Array<WorkflowTransition>
     */
    public function getTransitions(Internship $i)
    {
        return WorkflowTransitionFactory::getTransitionsFromState($this, $i);
    }

    public function getName(){
        return get_called_class();
    }

    /**
     * Returns the class name *without* the namespace
     */
    public function getClassName()
    {
        preg_match('/\w*$/', get_called_class(), $matches);

        return $matches[0];
    }

    public function getFriendlyName(){
        $class = $this->getName();
        return $class::friendlyName;
    }

    public function getSortIndex()
    {
        $class = get_called_class();
        return $class::sortIndex;
    }
}
