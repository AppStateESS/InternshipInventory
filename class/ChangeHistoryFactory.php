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

namespace Intern;

class ChangeHistoryFactory {

    public static function getChangesForInternship(Internship $internship)
    {
        $db = new \PHPWS_DB('intern_change_history');
        $db->addWhere('internship_id', $internship->getId());
        $db->addOrder('timestamp ASC');
        $results = $db->getObjects('\Intern\ChangeHistory');

        if(\PHPWS_Error::logIfError($results)){
            throw new \Exception($results->toString());
        }

        return $results;
    }
}
