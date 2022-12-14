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

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\TermFactory;

class RegistrationIssueUgrad extends WorkflowTransition {
    const sourceState = 'DeanApprovedState';
    const destState   = 'RegistrationIssueState';
    const actionName  = 'Mark as Registration Issue';

    const sortIndex = 6;

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function allowed(Internship $i)
    {
        if($i->isDistanceEd()){
            return \Current_User::allow('intern', 'distance_ed_register');
        }else{
            return parent::allowed($i);
        }
    }

    public function isApplicable(Internship $i)
    {
        return $i->isUndergraduate();
    }

    public function doNotification(Internship $i, $note = null)
    {
        $term = TermFactory::getTermByTermCode($i->getTerm());

        $email = new \Intern\Email\RegistrationIssueEmail(\Intern\InternSettings::getInstance(), $i, $term, $note);
        $email->send();
    }
}
