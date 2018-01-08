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
