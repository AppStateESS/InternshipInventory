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
use Intern\Exception\MissingDataException;
use Intern\TermFactory;

class GraduateRegistration extends WorkflowTransition {

    const sourceState = 'GradSchoolApprovedState';
    const destState   = 'RegisteredState';
    const actionName  = 'Mark as Registered / Enrollment Complete (grad)';

    public function getAllowedPermissionList(){
        return array('register');
    }

    public function isApplicable(Internship $i){
        if($i->isGraduate()){
            return true;
        } else{
            return false;
        }
    }

    public function allowed(Internship $i){
        if($i->isDistanceEd()) {
            if(\Current_User::allow('intern', 'distance_ed_register')) {
    		    return true;
    		} else{
    		    return false;
            }
    	} else{
    		return parent::allowed($i);
    	}
    }

    public function doNotification(Internship $i, $note = null){
        $term = TermFactory::getTermByTermCode($i->getTerm());

        $email = new \Intern\Email\RegistrationConfirmationEmail(\Intern\InternSettings::getInstance(), $i, $term);
        $email->send();
    }

    public function checkRequiredFields(Internship $i){
        if (!$i->isSecondaryPart()) {
            // Check the course subject
            $courseSubj = $i->getSubject();
            if (!isset($courseSubj) || $courseSubj == '' || $courseSubj->id == 0){
                throw new MissingDataException("Please select a course subject.");
            }

            // Check the course number
            $courseNum = $i->getCourseNumber();
            if (!isset($courseNum) || $courseNum == ''){
                throw new MissingDataException("Please enter a course number.");
            }

            // Check the course section number
            $sectionNum = $i->getCourseSection();
            if (!isset($sectionNum) || $sectionNum == ''){
                throw new MissingDataException("Please enter a course section number.");
            }

            // Check the course credit hours field
            $creditHours = $i->getCreditHours();
            if (!isset($creditHours) || $creditHours === ''){
                throw new MissingDataException("Please enter the number of course credit hours.");
            }
        }
    }
}
