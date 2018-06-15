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

namespace Intern\DataProvider\Major;
use Intern\AcademicMajorList;
use Intern\AcademicMajor;
use Intern\PdoFactory;

class LocalDbMajorsProvider extends MajorsProvider {

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * NB: The $term param is unused in this provider.
     *
     * @param string $term
     * @return AcademicMajorList
     */
    public function getMajors($term): AcademicMajorList
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_major');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        $majorsList = new AcademicMajorList();

        foreach ($results as $row){
            $majorsList->addMajor(new AcademicMajor($row['code'], $row['description'], $row['level']));
        }

        return $majorsList;
    }
}
