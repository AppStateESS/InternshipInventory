<?php

/**
 * Faculty
 * 
 * Represents a faculty member for a department.
 * 
 * @author Jeremy Booker jbooker@tux.appstate.edu
 * @package Hms
 */

class Faculty implements DbStorable {
	
	private $id;
	private $username;
	
	private $firstName;
	private $lastName;
	
	private $phone;
	private $fax;
	
	private $streetAddress1;
	private $streetAddress2;
	private $city;
	private $state;
	private $zip;
	
	/**
	 * Constructor
	 */
	public function __construct($id, $username, $firstName, $lastName, $phone, $fax, $streetAddress1, $streetAddress2, $city, $state, $zip)
	{
		$this->setId($id);
		$this->setUsername($username);
		
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		
		$this->setPhone($phone);
		$this->setFax($fax);
		
		$this->setStreetAddress1($streetAddress1);
		$this->setStreetAddress2($streetAddress2);
		$this->setCity($city);
		$this->setState($state);
		$this->setZip($zip);
	}
	
	/**
     * Returns the database table name for this class.
     * @see DbStorable::getTableName()
     */
    public static function getTableName(){
        return 'intern_faculty';
    }
	
	/**
	 * Returns an array of columns to be used in a CSV export.
	 * @return Array
	 */
	public function getCSV()
	{
		$csv = array();
		
		$csv['Faculty Super. First Name'] = $this->getFirstName();
		$csv['Faculty Super. Last Name']  = $this->getLastName();
		$csv['Faculty Super. Phone']      = $this->getPhone();
		$csv['Faculty Super. Email']      = $this->getUsername();
		
		return $csv;
	}
	
	/**
	 * Shortcut method for getting first and last name
	 * concatenated together with a space
	 * @return string
	 */
	public function getFullName()
	{
		return $this->getFirtName() . ' ' . $this->getLastName();
	}
	
	/***************************
	 * Getter / Setter Methods *
	 */
	
	/**
	 * Returns this objects database id
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Sets this objects database id.
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	 * Returns the username portion of the faculty member's
	 * email address.
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * Sets the username portion of the faculty member's
	 * email addres.
	 * @param string $user
	 */
	public function setUsername($user)
	{
		$this->username = $user;
	}
	
	/**
	 * Returns the first name
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	/**
	 * Sets the first name
	 * @param string $first
	 */
	public function setFirstName($first)
	{
		$this->firstName = $first;
	}
	
	/**
	 * Returns the last name
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	
	/**
	 * Sets the last name
	 * @param string $last
	 */
	public function setLastName($last)
	{
		$this->lastName = $last;
	}
	
	/**
	 * Returns the faculty member's phone number.
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}
	
	/**
	 * Sets the faculty member's phone number.
	 * @param string $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}
	
	/**
	 * Returns this faculty member's fax number.
	 * @return string
	 */
	public function getFax()
	{
		return $this->fax;
	}
	
	/**
	 * Sets this faculty member's fax number
	 * @param string $fax
	 */
	public function setFax($fax)
	{
		$this->fax = $fax;
	}
	
	/**
	 * Returns line 1 of this faculty member's address.
	 * @return string
	 */
	public function getStreetAddress1()
	{
		return $this->streetAddress1;
	}
	
	/**
	 * Sets line 1 of this faculty member's address.
	 * @param string $addr
	 */
	public function setStreetAddress1($addr)
	{
		$this->streetAddress1 = $addr;
	}
	
	/**
	 * Returns line 2 of this faculty member's address.
	 * @return string
	 */
	public function getStreetAddress2()
	{
		return $this->streetAddress2;
	}
	
	/**
	 * Sets line 2 of this faculty member's address.
	 * @param string $addr
	 */
	public function setStreetAddress2($addr)
	{
		$this->streetAddress2 = $addr;
	}
	
	/**
	 * Returns the city portion of this faculty member's address.
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}
	
	/**
	 * Sets the city portion of this faculty member's address.
	 * @param string $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}
	
	/**
	 * Retunrs the state portion of this faculty member's address.
	 * @return string
	 */
	public function getState()
	{
		return $this->state;
	}
	
	/**
	 * Sets the state portion of this faculty member's address.
	 * @param string $state
	 */
	public function setState($state)
	{
		$this->state = $state;
	}
	
	/**
	 * Returns the zip code portion of this faculty member's address.
	 * @return string
	 */
	public function getZip()
	{
		return $this->zip;
	}
	
	/**
	 * Sets the zip code portion of this faculty member's address.
	 * @param integer $zip
	 */
	public function setZip($zip)
	{
		$this->zip = $zip;
	}
}

class FacultyDB extends Faculty {
	public function __construct(){
		// override parent and don't call parent::__construct(), so we can have an empty constructor for loading from DB
	}
}