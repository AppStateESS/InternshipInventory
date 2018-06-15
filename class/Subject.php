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

class Subject extends Model {

    public $id;
    public $abbreviation;
    public $description;
    public $active;

    public function getDB(){
        return new \PHPWS_DB('intern_subject');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCSV()
    {
        return array();
    }

    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    public function getName(){
        return $this->description;
    }

    public function getActive()
    {
    	return $this->active;
    }

    public static function getSubjects($mustIncludeId = null)
    {
        $db = PdoFactory::getPdoInstance();

        $params = array();

        $query = 'SELECT * from intern_subject WHERE active = 1';

        if(!is_null($mustIncludeId)) {
            $query .=' OR id = :mustIncludeId';
            $params['mustIncludeId'] = $mustIncludeId;
        }

        $query .= ' ORDER BY abbreviation ASC';

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $subjects = array();
        foreach($results as $row){
            $subjects[$row['id']] = $row['abbreviation'] . ' - ' . $row['description'];
        }

        return $subjects;
    }
}
