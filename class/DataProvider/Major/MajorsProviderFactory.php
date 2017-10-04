<?php

namespace Intern\DataProvider\Major;

use Intern\InternSettings;

/**
 * Majors Provider Factor - Creates a Majors Provider based on settings.
 *
 * @author Jeremy Booker
 * @package Intern
 */
class MajorsProviderFactory {

    /**
     * Returns a concrete instance of a MajorsProvider object,
     * which can be then be used to fetch the array of Major objects
     *
     * @return MajorsProvider
     */
    public static function getProvider()
    {
        if(STUDENT_DATA_TEST){
            return new TestMajorsProvider(\Current_User::getUsername());
        }

        $providerName = InternSettings::getInstance()->getStudentDataSource();

        switch($providerName){
            case 'localDataProvider':
                return new LocalDbMajorsProvider();
            case 'webServiceDataProvider':
                return new BannerMajorsProvider(\Current_User::getUsername());
            case 'webServiceTestProvider':
                return new TestMajorsProvider(\Current_User::getUsername());
            default:
                throw new \UnexpectedValueException('No majors provider configured.');
        }

    }
}
