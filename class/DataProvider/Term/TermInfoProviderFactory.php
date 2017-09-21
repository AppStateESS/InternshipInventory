<?php

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
