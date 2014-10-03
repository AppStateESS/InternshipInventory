<?php

namespace Intern;

/**
 * StudentDataProviderFactory
 *
 * @author Jeremy Booker
 * @package Intern
 */
class StudentProviderFactory {

    /**
     * Returns a concrete instance of a StudenDataProvider object,
     * which can then be used to create Student object
     *
     * @return StudentDataProvider
     */
    public static function getProvider()
    {
        if(STUDENT_DATA_TEST){
            return new TestStudentProvider();
        }

        // Other data providers could be used here..

        return new BannerStudentProvider(Current_User::getUsername());
    }
}

?>