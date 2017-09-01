<?php

namespace Intern\DataProvider\Term;

use Intern\InternSettings;

/**
 * StudentDataProviderFactory
 *
 * @author Jeremy Booker
 * @package Intern
 */
class TermProviderFactory {

    /**
     * Returns a concrete instance of a StudenDataProvider object,
     * which can then be used to create Student object
     *
     * @throws \Exception
     * @return StudentDataProvider
     */
    public static function getProvider(): TermDataProvider
    {
        if(STUDENT_DATA_TEST){
            return new TestWebServiceTermProvider(\Current_User::getUsername());
        }

        $providerName = InternSettings::getInstance()->getStudentDataSource();

        switch($providerName){
            case 'localDataProvider':
                //return new LocalDbTermDataProvider();
                // TODO: Return an actual local data provider based on the db
                return new TestWebServiceTermProvider(\Current_User::getUsername());
            case 'webServiceDataProvider':
                return new WebServiceTermProvider(\Current_User::getUsername());
            default:
                throw new \UnexpectedValueException('No term data provider configured.');
        }
    }
}
