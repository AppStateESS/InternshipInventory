<?php
namespace Intern\Command;

/**
 * Controller class for handling a request to remove an emergency contact
 * from a particular internship.
 * 
 * @author jbooker
 * @package intern
 * 
 */
class RemoveEmergencyContact {

    public function __construct(){
        
    }
    
    public function execute()
    {
        // Check permissions
        //TODO
        
        // Get the contactId parameter
        if(!isset($_REQUEST['contactId'])){
            throw new InvalidArgumentException('Missing contact id.');
        }
        
        $contactId = $_REQUEST['contactId'];
        
        $contact = EmergencyContactFactory::getContactById($contactId);
        
        EmergencyContactFactory::delete($contact);
        
        // Called from AJAX, so just exit with no output
        exit();
    }
    
}

?>
