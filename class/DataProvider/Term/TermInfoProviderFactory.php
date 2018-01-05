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

use Intern\InternSettings;

/**
 * StudentDataProviderFactory
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TermInfoProviderFactory {

    /**
     * Returns a concrete instance of a StudenDataProvider object,
     * which can then be used to create Student object
     *
     * @throws \Exception
     * @return StudentDataProvider
     */
    public static function getProvider(): TermInfoProvider
    {
        if(STUDENT_DATA_TEST){
            return new TestWebServiceTermProvider(\Current_User::getUsername());
        }

        $providerName = InternSettings::getInstance()->getStudentDataSource();

        switch($providerName){
            case 'localDataProvider':
                return new LocalDbTermInfoProvider();
            case 'webServiceDataProvider':
                return new WebServiceTermInfoProvider(\Current_User::getUsername());
            case 'webServiceTestProvider':
                return new TestWebServiceTermInfoProvider(\Current_User::getUsername());
            default:
                throw new \UnexpectedValueException('No term data provider configured.');
        }
    }
}
