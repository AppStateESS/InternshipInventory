<?php

namespace Intern;

use \PHPWS_Core;

class WorkflowTransitionFactory {

    private static $dir = 'WorkflowTransition';

    public static function getTransitionsFromState(WorkflowState $state, Internship $i)
    {
        $stateName = $state->getName();

        $transitions = self::getAllTransitions();
        $outgoingTrans = array();

        foreach($transitions as $t){
            // Set the actual source state
            $t->setSourceState($state);

            if(is_array($t->getSourceState()) && in_array($stateName, $t->getSourceState()) && $t->isApplicable($i)){
                $outgoingTrans[] = $t;
            }else if(($t->getSourceState() == $stateName || $t->getSourceState() == '*') && $t->isApplicable($i)){
                $outgoingTrans[] = $t;
            }
        }

        uasort($outgoingTrans, array('self', 'sortTransitions'));

        return $outgoingTrans;
    }

    public static function getTransitionByName($name)
    {
        if(!isset($name)){
            throw new InvalidArgumentException('Missing transition name.');
        }

        return new $name;
    }

    public static function getAllTransitions()
    {
        $dir = PHPWS_SOURCE_DIR . 'mod/intern/class/' . self::$dir;

        // Get the directory listing and filter out anything that doesn't look right
        $files = scandir("{$dir}/");
        $transitions = array();
        foreach($files as $f){
            // Look for directories that don't start with '.'
            if(!is_dir($dir . '/' . $f) && substr($f, 0, 1) != '.'){
                // Include each one
                PHPWS_Core::initModClass('intern', self::$dir . '/' . $f);
                $className = preg_replace('/\.php/', '', $f);

                $className = '\Intern\WorkflowTransition\\' . $className;

                // Instanciate each one
                $transitions[] = new $className;
            }
        }

        return $transitions;
    }

    /**
     * Call-back function for sorting transitions by their priority. Lower sort index => lower priority.
     * @param WorkflowTransition $a
     * @param WorkflowTransition $b
     */
    private static function sortTransitions(WorkflowTransition $a, WorkflowTransition $b)
    {
        if($a->getSortIndex() == $b->getSortIndex()){
            return 0;
        }
        return ($a->getSortIndex() < $b->getSortIndex()) ? -1 : 1;
    }
}

?>
