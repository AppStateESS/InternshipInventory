<?php

namespace Intern;

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
    public $email;

    private $internship;

    /**
     * Constructor.
     * @param Internship $internship
     * @param String $name
     * @param String $relation
     * @param String $phone
     */
    public function __construct(Internship $i, $name, $relation, $phone, $email)
    {
        $this->internship     = $i;

        $this->internship_id  = $i->getId();
        $this->name           = $name;
        $this->relation       = $relation;
        $this->phone          = $phone;
        $this->email          = $email;
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
        $vars['email']        = $this->getEmail();

        return $vars;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRelation($relation)
    {
        $this->relation = $relation;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}
