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

namespace Intern\DataProvider\Term;

use Intern\TermInfo;
use Intern\PdoFactory;

class LocalDbTermInfoProvider extends TermInfoProvider {

    public function getTermInfo(string $termCode): TermInfo
    {
        $db = PdoFactory::getPdoInstance();

        $query = 'SELECT * FROM intern_local_term_data WHERE term_code = :termCode';

        $stmt = $db->prepare($query);
        $stmt->execute(array('termCode' => $termCode));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        if(sizeof($result) === 0){
            throw new \UnexpectedValueException('No term exists for term code: ' . $termCode);
        }

        // Result size should never be greater than one because of the primary key on the intern_local_term_data table.
        // We'll double check, just in case.
        if(sizeof($result) > 1) {
            throw new \UnexpectedValueException('Multiple terms exist with the same term code for: ' . $termCode);
        }

        $result = $result[0];

        // Plug the results into a TermInfo object
        $termInfo = new TermInfo();

        $termInfo->setTermCode($result['term_code']);
        $termInfo->setTermDesc($result['description']);
        $termInfo->setTermStartDate(date('n/j/Y', $result['start_date']));
        $termInfo->setTermEndDate(date('n/j/Y', $result['end_date']));
        $termInfo->setCensusDate(date('n/j/Y', $result['census_date']));

        // Setup a single part of term inside this term.
        // This is sortof a hack to provide term parts without fully implementing term parts in the local db TermProvider
        $termPart = new \stdClass();
        $termPart->part_term_code = $result['term_code'];
        $termPart->part_term_desc = $result['description'];
        $termPart->part_start_date = date('n/j/Y', $result['start_date']);
        $termPart->part_end_date = date('n/j/Y', $result['end_date']);

        $termInfo->addTermPart($termPart);

        return $termInfo;
    }
}
