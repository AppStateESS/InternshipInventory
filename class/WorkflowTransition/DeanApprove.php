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
use Intern\ExpectedCourseFactory;
use Intern\Email\UnusualCourseEmail;
use \Intern\InternSettings;
use Intern\TermFactory;

use Intern\Exception\MissingDataException;

class DeanApprove extends WorkflowTransition {
    const sourceState = 'SigAuthApprovedState';
    const destState   = 'DeanApprovedState';
    const actionName  = 'Mark as Dean Approved';

    public function getAllowedPermissionList(){
        return array('dean_approve');
    }

    public function checkRequiredFields(Internship $i){
        // Course number and subject are required so we can check against the expected insurance list in doNotification()
        // NB: Course subject and number are not required for secondary parts of multi-part internships
        if(!$i->isSecondaryPart() && $i->getCourseNumber() === null || $i->getCourseNumber() === ''){
            throw new MissingDataException("Please enter a course number.");
        }

        if(!$i->isSecondaryPart() && $i->getSubject() === null){
            throw new MissingDataException("Please select a course subject.");
        }
        $emergName = $i->getEmergencyContactName();
        if(!isset($emergName)){
            throw new MissingDataException("Please add an emergency contact.");
        }

        if (empty($_POST['start_date']) || empty($_POST['end_date'])){
            throw new MissingDataException("This internship cannot continue without start and end dates.");
        }
    }

    public function doNotification(Internship $i, $note = null){
        $settings = \Intern\InternSettings::getInstance();

        $term = TermFactory::getTermByTermCode($i->getTerm());

        // If this is an undergrad internship, then send the Registrar an email
        // Graduate level internships have another workflow state to go through before we alert the Registrar
        if($i->isUndergraduate()){
            $email = new \Intern\Email\ReadyToRegisterEmail($settings, $i, $term);
            $email->send();
        }

        // If this is a graduate email, send the notification email to the grad school office
        if($i->isGraduate()){
            $email = new \Intern\Email\GradSchoolNotificationEmail($settings, $i, $term);
            $email->send();
        }

        // If the subject and course number are not registered with InternshipInventory,
        // send an email to the appropriate receiver.
        // Does not apply to secondary parts of multi-part internships
        if (!$i->isSecondaryPart() && !ExpectedCourseFactory::isExpectedCourse($i->getSubject(), $i->getCourseNumber())){
            $email = new UnusualCourseEmail(InternSettings::getInstance(), $i, $term);
            $email->send();
        }
    }
}
