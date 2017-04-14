<?php

namespace Intern;

class Subject extends Model {

    public $id;
    public $abbreviation;
    public $description;
    public $active;

    public function getDB(){
        return new \PHPWS_DB('intern_subject');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCSV()
    {
        return array();
    }

    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    public function getName(){
        return $this->description;
    }

    public function getActive()
    {
    	return $this->active;
    }

    public static function getSubjects($mustIncludeId = null)
    {
        $db = PdoFactory::getPdoInstance();

        $params = array();

        $query = 'SELECT * from intern_subject WHERE active = 1';

        if(!is_null($mustIncludeId)) {
            $query .=' OR id = :mustIncludeId';
            $params['mustIncludeId'] = $mustIncludeId;
        }

        $query .= ' ORDER BY abbreviation ASC';

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $subjects = array();
        foreach($results as $row){
            $subjects[$row['id']] = $row['abbreviation'] . ' - ' . $row['description'];
        }

        return $subjects;
    }
}
