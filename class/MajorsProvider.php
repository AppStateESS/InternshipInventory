<?php

namespace Intern;


abstract class MajorsProvider {

    const LEVEL_UNDERGRAD   = 'U';
    const LEVEL_GRADUATE    = 'G';

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * @param $term
     * @return AcademicMajorList
     */
    public abstract function getMajors($term);

}
