<?php

namespace Intern\DataProvider\Student;

use Intern\InternSettings;

/**
 * StudentDataProviderFactory
 *
 * @author Jeremy Booker
 * @package Intern
 */
class StudentDataProviderFactory {

    /**
     * Returns a concrete instance of a StudenDataProvider object,
     * which can then be used to create Student object
     *
     * @return StudentDataProvider
     */
    public static function getProvider(): StudentDataProvider
    {
        // First, check if the test flag override is on
        if(STUDENT_DATA_TEST){
            return new TestWebServiceDataProvider(\Current_User::getUsername());
        }

        $providerName = InternSettings::getInstance()->getStudentDataSource();

        switch($providerName){
            case 'localDataProvider':
                return new LocalDbStudentDataProvider();
            case 'webServiceDataProvider':
                return new WebServiceDataProvider(\Current_User::getUsername());
            case 'webServiceTestProvider':
                return new TestWebServiceDataProvider(\Current_User::getUsername());
            default:
                throw new \UnexpectedValueException('No configuration for student data provider.');

        }
    }
}
