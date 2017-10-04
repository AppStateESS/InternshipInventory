<?php

namespace Intern\DataProvider\Major;

use Intern\AcademicMajorList;

abstract class MajorsProvider {

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * @param $term
     * @return AcademicMajorList
     */
    public abstract function getMajors($term): AcademicMajorList;

}
