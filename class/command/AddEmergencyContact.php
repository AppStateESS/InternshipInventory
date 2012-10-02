<?php

/**
 * Controller class for handling a request to add an emergency contact
 * to a particular internship.
 * 
 * @author jbooker
 * @package intern
 * 
 */
class AddEmergencyContact {

    public function __construct(){
        
    }
    
    public function execute()
    {
        // Get data from request
        $internshipId = $_REQUEST['internshipId'];
        $name         = $_REQUEST['emergency_contact_name'];
        $relation     = $_REQUEST['emergency_contact_relation'];
        $phone        = $_REQUEST['emergency_contact_phone'];
        
        // Sanity checking
        if(is_null($internshipId) || !isset($internshipId)){
            throw new InvalidArgumentException('Missing internship ID.');
        }
        
        if(is_null($name) || !isset($name)){
            throw new InvalidArgumentException('Missing contact name.');
        }
        
        if(is_null($relation) || !isset($relation)){
            throw new InvalidArgumentException('Missing contact relationship.');
        }
        
        if(is_null($phone) || !isset($phone)){
            throw new InvalidArgumentException('Missing contact phone number.');
        }
        
        PHPWS_Core::initModClass('intern', 'EmergencyContact.php');
        PHPWS_Core::initModClass('intern', 'InternshipFactory.php');

        // Get an Internship object based on the ID
        $internship = InternshipFactory::getInternshipById($internshipId);
        
        // Create the emergency contact
        $contact = new EmergencyContact($internship, $name, $relation, $phone);
        
        // Save the emergency contact object
        PHPWS_Core::initModClass('intern', 'DatabaseStorage.php');
        DatabaseStorage::save($contact);
        
        echo json_encode($contact);
        
        // Exit, since this is called by JSON
        //TODO
        exit;
    }
    
}

?>