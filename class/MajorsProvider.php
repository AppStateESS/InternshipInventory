<?php

namespace Intern;


abstract class MajorsProvider {

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * @param $term
     * @return Array<AcademicMajor>
     */
    public abstract function getMajors($term);

}
