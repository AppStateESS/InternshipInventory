<?php

namespace Intern;

class StudentFactory {

    public static function getStudent($studentId, $term)
    {
        $provider = ExternalDataProviderFactory::getProvider();
        return $provider->getStudent($studentId, $term);
    }
}
