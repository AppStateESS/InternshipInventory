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
use \Intern\WorkflowStateFactory;
use \Intern\TermFactory;

/**
 * Controller class to save a copy of an Internship for the next term
 *
 * @author csdetsch
 * @author jbooker
 * @package intern
 */
class CopyInternshipToNextTerm {

    public function __construct()
    {

    }

    public function execute()
    {
        // Load the existing internship using its ID
        $internship = InternshipFactory::getInternshipById($_REQUEST['internshipId']);

        // Clear the ID so that insert a new internship into the database the
        // next time we call save()
        $internship->setId(null);

        // Clear/reset additional values from the existing internship
        $internship->setStartDate(null);
        $internship->setEndDate(null);

        $state = WorkflowStateFactory::getState('CreationState');
        $internship->setState($state); // Set initial WorkflowState

        // Get the requested destination term and set it
        $destTermCode = $_REQUEST['destinationTerm'];
        $newTerm = TermFactory::getTermByTermCode($destTermCode);

        if($newTerm === null || $newTerm === false){
            throw new \InvalidArgumentException('Requested term does not exist: ' . $destTermCode);
        }

        $internship->setTerm($newTerm->getTermCode());

        // Save the new internship
        $copyId = $internship->save();

        // Show message if user edited internship
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Continued internship for ' . $internship->getFullName() . ' to ' . $newTerm->getDescription() . '.');
        \NQ::close();

        // Redirect to the new internship
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $copyId);
    }
}
