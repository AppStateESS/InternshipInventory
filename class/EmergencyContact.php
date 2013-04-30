<?php

PHPWS_Core::initModClass('intern', 'DbStorable.php');

/**
 * Model object to represent an emergency contact.
 * 
 * @author jbooker
 * @package intern
 */
class EmergencyContact implements DbStorable {

    public $id;
    public $internship_id;
    public $name;
    public $relation;
    public $phone;

    private $internship;
    
    /**
     * Constructor.
     * @param Internship $internship
     * @param String $name
     * @param String $relation
     * @param String $phone
     */
    public function __construct(Internship $i, $name, $relation, $phone)
    {
        $this->internship     = $i;
        
        $this->internship_id  = $i->getId();
        $this->name           = $name;
        $this->relation       = $relation;
        $this->phone          = $phone;
    }

    /**
     * Returns the database table name for this class.
     * @see DbStorable::getTableName()
     */
    public static function getTableName()
    {
        return 'intern_emergency_contact';
    }
    
    public function extractVars()
    {
        $vars = array();
        
        $vars['id']           = $this->getId();
        $vars['intership_id'] = $this->getInternshipId();
        $vars['name']         = $this->getName();
        $vars['relation']     = $this->getRelation();
        $vars['phone']        = $this->getPhone();
        
        return $vars;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getInternshipId()
    {
        return $this->internship_id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getRelation()
    {
        return $this->relation;
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
}

class EmergencyContactDB extends EmergencyContact {
    public function __construct(){
        // override parent and don't call parent::__construct(), so we can have an empty constructor for loading from DB
    }
}

?>