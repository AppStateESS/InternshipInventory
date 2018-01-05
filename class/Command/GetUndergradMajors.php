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

use Intern\DataProvider\Major\MajorsProviderFactory;
use Intern\TermFactory;
use Intern\AcademicMajor;

class GetUndergradMajors {

    public function execute()
    {
        $terms = TermFactory::getAvailableTerms();

        // A bit of a hack regarding the term. There isn't always a single "current" term, so we'll take whatever
        // the first active term is.
        $majorsList = MajorsProviderFactory::getProvider()->getMajors($terms[0]);
        $majorsList = $majorsList->getMajorsByLevel(AcademicMajor::LEVEL_UNDERGRAD);

        $majorsList = array(array('code'=>'-1', 'description' => 'Select Undergraduate Major')) + $majorsList;

        echo json_encode($majorsList);
        exit;
    }
}
