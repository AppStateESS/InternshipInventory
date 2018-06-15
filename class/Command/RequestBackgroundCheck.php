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

use Intern\TermFactory;

/**
 *
 * @author Olivia Perugini
 */
class RequestBackgroundCheck {

    public function __construct()
    {

    }

    public function execute()
    {

        $i = \Intern\InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        $i->background_check = 1;
        $agency = $i->getAgency();

        $i->save();

        $term = TermFactory::getTermByTermCode($i->getTerm());

        $email = new \Intern\Email\BackgroundCheckEmail(\Intern\InternSettings::getInstance(), $i, $term, $agency, true, false);
        $email->send();

        exit;
    }
}
