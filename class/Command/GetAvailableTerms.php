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

use \Intern\Term as Term;
use \Intern\DataProvider\Term\TermInfoProviderFactory;
use Intern\TermFactory;

/**
 * JSON endpoint for getting begin/end date and census date information for a term.
 * Uses the TermInfoProviderFactory to load TermInfo objects.
 *
 * @author jbooker
 * @package Intern
 */
class GetAvailableTerms {

    public function execute()
    {
        $availableTerms = TermFactory::getAvailableTerms();

        $termList = array();

        foreach ($availableTerms as $term){
            $termList[$term->getTermCode()] = $term;
        }

        echo json_encode($termList);
        exit;
    }
}
