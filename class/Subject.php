<?php

namespace Intern;

class Subject extends Model {

    public $id;
    public $abbreviation;
    public $description;
    public $active;

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
    
    public function getName(){
        return $this->description;
    }
    
    public function getActive()
    {
    	return $this->active;
    }
    
    public static function getSubjects($mustIncludeId = null)
    {
        $subjects = array('-1'=>'Select a subject...');

        $db = new PHPWS_DB('intern_subject');
        $db->addWhere('active', 1, '=', 'OR');
        if(!is_null($mustIncludeId)) {
            $db->addWhere('id', $mustIncludeId, '=', 'OR');
        }
        
        $db->addOrder('abbreviation ASC');
        
        $results = $db->select();

        foreach($results as $row){
            $subjects[$row['id']] = $row['abbreviation'] . ' - ' . $row['description'];
        }
        
        return $subjects;
    }
}

?>
