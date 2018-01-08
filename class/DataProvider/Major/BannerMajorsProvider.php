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

use \SoapFault;

class BannerMajorsProvider extends MajorsProvider {

    protected $currentUserName;

    private $client;

    public function __construct($currentUserName)
    {
        $this->currentUserName = $currentUserName;

        // Get the WSDL URI from module's settings
        $wsdlUri = \PHPWS_Settings::get('intern', 'wsdlUri');

        // Create the SOAP instance
        $this->client = new \SoapClient($wsdlUri, array('WSDL_CACHE_MEMORY'));
    }

    public function getMajors($term): AcademicMajorList
    {
        if($term === null || $term == '') {
            throw new \InvalidArgumentException('Missing term.');
        }

        $params = array('Term'      => $term->getTermCode(),
                        'UserName'  => $this->currentUserName);

        try {
            $response = $this->client->getMajorInfo($params);
        } catch (SoapFault $e){
            throw $e;
        }

        $results = $response->GetMajorInfoResult->MajorInfo;

        $majorsList = new AcademicMajorList();

        foreach($results as $major){

            // Skip majors/programs in University College
            if($major->college_code === 'GC'){
                continue;
            }

            // Add it to the collection if it's not a duplicate
            $majorsList->addIfNotDuplicate(new AcademicMajor($major->major_code, $major->major_desc, $major->levl));
        }

        return $majorsList;
    }
}
