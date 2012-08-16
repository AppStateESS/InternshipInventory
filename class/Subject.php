<?php

class Subject extends Model {

    public $id;
    public $abbreviation;
    public $description;

    public function getDB(){
        return new PHPWS_DB('intern_subject');
    }

    public function getCSV()
    {
        return array();
    }

    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
    
    public static function getSubjects()
    {
        $subjects = array('-1'=>'Select a subject...');

        $db = new PHPWS_DB('intern_subject');
        $db->addOrder('abbreviation ASC');
        $results = $db->select();

        foreach($results as $row){
            $subjects[$row['id']] = $row['abbreviation'] . ' - ' . $row['description'];
        }

        return $subjects;
    }
}

?>