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
    public static function getProvider()
    {
        // First, check if the test flag override is on
        if(STUDENT_DATA_TEST){
            return new TestWebServiceDataProvider(\Current_User::getUsername());
        }

        $provider = InternSettings::getInstance()->getStudentDataSource();

        switch($provider){
            case 'localDataProvider':
                return new LocalDbStudentDataProvider();
            case 'webServiceDataProvider':
                return new WebServiceDataProvider(\Current_User::getUsername());
        }

        // If we're still here, throw an exception
        throw new \InvalidArgumentException('No configuration for student data provider.');
    }
}
