<?php

namespace Intern;

use \Intern\DataProvider\Student\StudentDataProviderFactory;

class StudentFactory {

    public static function getStudent($studentId, $term)
    {
        $provider = StudentDataProviderFactory::getProvider();
        return $provider->getStudent($studentId, $term);
    }
}
