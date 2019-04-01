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

class CancelTransition extends WorkflowTransition {
    //const sourceState = '*';
    const destState   = 'CancelledState';
    const actionName  = 'Cancel';

    const sortIndex = 10;

    public function getAllowedPermissionList(){
        return array('dept_approve', 'sig_auth_approve', 'register', 'dean_approve');
    }

    public function getSourceState(){
        return array('NewState', 'SigAuthReadyState', 'SigAuthApprovedState', 'DeanApprovedState', 'GradSchoolApprovedState', 'RegistrationIssueState');
    }

    public function doNotification(Internship $i, $note = null){
        $settings = \Intern\InternSettings::getInstance();

        $term = TermFactory::getTermByTermCode($i->getTerm());

        $email = new \Intern\Email\CancelInternshipNotice($settings, $i, $term);
        $email->send();

        if($i->isInternational()){
            $email = new \Intern\Email\IntlInternshipCancelNotice($settings, $i, $term);
            $email->send();
        }
    }
}
