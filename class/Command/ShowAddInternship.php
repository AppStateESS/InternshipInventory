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
use \Intern\Department;
use \Intern\State;
use \Intern\CountryFactory;
use \Intern\UI\NotifyUI;

/*
 * ShowAddInternship
 *
 * Controller for showing the Add Internship view.
 */

class ShowAddInternship {

    public function __construct() {

    }

    public function execute()
    {
        // Check permissions
        if(!\Current_User::allow('intern', 'create_internship')){
            \NQ::simple('intern', NotifyUI::ERROR, 'You do not have permission to create new internships.');
            \NQ::close();
            \PHPWS_Core::home();
        }


        $view = new \Intern\AddInternshipView();

        return $view->render();
    }
}
