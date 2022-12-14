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

use Intern\AcademicMajor;
use Intern\AcademicMajorList;
use Intern\DataProvider\Curl;

class BannerMajorsProvider extends MajorsProvider {

    protected $currentUserName;

    private $apiKey;

    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $this->apiKey = \PHPWS_Settings::get('intern', 'wsdlUri');
    }

    public function getMajors($term): AcademicMajorList
    {
        if($term === null || $term == '') {
            throw new \InvalidArgumentException('Missing term.');
        }

        $termCode = $term->getTermCode();

        $url = 'https://sawarehouse.ess.appstate.edu/api/intern/majors/' . $termCode . '?username=intern&api_token=' . $this->apiKey;
        $curl = new Curl();
        $curl->setUrl($url);
        $result = json_decode($curl->exec());
        $curl->close();

        $majorsList = new AcademicMajorList();

        foreach($result as $major){
            // Makes sure the data from api is an object
            // Skip majors/programs in University College
            if(!is_object($major) || $major->collegeCode === 'GC' || $major->majorLevel === null){
                continue;
            }

            // Add it to the collection if it's not a duplicate
            $majorsList->addIfNotDuplicate(new AcademicMajor($major->majorCode, $major->majorDescription, $major->majorLevel));
        }

        return $majorsList;
    }
}
