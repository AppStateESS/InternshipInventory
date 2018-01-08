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

use \PHPWS_Core;

class WorkflowTransitionFactory {

    private static $dir = 'WorkflowTransition';

    /**
     * Get the list of transitions currently available for a given state
     *
     * @param WorkflowState $state State object to get transitions for
     * @param Internship    $i Internship object that this state applies to
     * @return Array   Array of available transitions
     */
    public static function getTransitionsFromState(WorkflowState $state, Internship $i)
    {
        $stateName = $state->getName();

        // Strip namespace from state name by matching the last word character in the path
        preg_match('/\w*$/', $stateName, $matches);

        $stateName = $matches[0];

        $transitions = self::getAllTransitions(); // Get all transitions
        $outgoingTrans = array();

        // Filter the list of all transitions by finding the transitions which have this state
        // in their list of potential source states
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
            throw new \InvalidArgumentException('Missing transition name.');
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
