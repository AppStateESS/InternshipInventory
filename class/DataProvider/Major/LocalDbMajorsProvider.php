<?php

namespace Intern\DataProvider\Major;
use Intern\AcademicMajorList;

class LocalDbMajorsProvider extends MajorsProvider {

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * @param $term
     * @return AcademicMajorList
     */
    public function getMajors($term)
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term where term = :termCode');
        $stmt->execute(array('termCode' => $termCode));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        return $stmt->fetchAll();
    }
}
