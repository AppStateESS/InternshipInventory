<?php

namespace Intern\DataProvider\Major;
use Intern\AcademicMajorList;
use Intern\AcademicMajor;
use Intern\PdoFactory;

class LocalDbMajorsProvider extends MajorsProvider {

    /**
     * Returns an array of AcademicMajor objects for the given term.
     *
     * NB: The $term param is unused in this provider.
     *
     * @param string $term
     * @return AcademicMajorList
     */
    public function getMajors($term): AcademicMajorList
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_major');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $results = $stmt->fetchAll();

        $majorsList = new AcademicMajorList();

        foreach ($results as $row){
            $majorsList->addMajor(new AcademicMajor($row['code'], $row['description'], $row['level']));
        }

        return $majorsList;
    }
}
