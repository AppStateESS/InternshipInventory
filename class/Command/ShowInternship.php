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

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\HostFactory;
use \Intern\SupervisorFactory;
use \Intern\InternshipView;
use \Intern\DataProvider\Student\StudentDataProviderFactory;
use \Intern\TermFactory;

class ShowInternship {

    public function execute()
    {
        // Make sure an 'internship_id' key is set on the request
        if(!isset($_REQUEST['internship_id'])) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'No internship ID was given.');
            \NQ::close();
            \PHPWS_Core::reroute('index.php');
        }

        // Load the Internship
        try{
            $intern = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        }catch(\Intern\Exception\InternshipNotFoundException $e){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            return;
        }

        if($intern === false) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            //TODO redirect to the search interface
            return;
        }

        // Load a fresh copy of the student data from the web service
        try {
            $student = StudentDataProviderFactory::getProvider()->getStudent($intern->getBannerId());
        } catch(\Intern\Exception\StudentNotFoundException $e) {
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a student with an ID of {$studentId} in Banner. This probably means this person is not an active student.");
        }

        try {
            $existingCreditHours = StudentDataProviderFactory::getProvider()->getCreditHours($intern->getBannerId(), $intern->getTerm());
        } catch(\Exception $e){
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't get the credit hours for {$studentId}. This probably means this person is not an active student.");
        }

        // Load the WorkflowState
        $wfState = $intern->getWorkflowState();

        // Load the host & sup
        $host = HostFactory::getHostById($intern->getHostId());
        $supervisor = SupervisorFactory::getSupervisorById($intern->getSupervisorId());

        // Load the term info for this internship
        $term = TermFactory::getTermByTermCode($intern->getTerm());

        $view = new InternshipView($intern, $student, $wfState, $host, $supervisor, $term, $existingCreditHours);

        return $view->display();
    }
}
