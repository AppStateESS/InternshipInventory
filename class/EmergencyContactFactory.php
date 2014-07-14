<?php

namespace Intern;

class EmergencyContactFactory {

    /**
     * Returns an array of EmergencyContact objects for the given Internship.
     * 
     * @param Internship $i
     * @return Array<EmergencyContact> Array of EmergencyContact objects for the given Internship, or an empty array if none exist.
     * @throws InvalidArgumentException
     * @throws Exception
     * @see EmergencyContactDB
     */
    public static function getContactsForInternship(Internship $i)
    {
        $internshipId = $i->getId();
        
        if(is_null($internshipId) || !isset($internshipId)){
            throw new InvalidArgumentException('Internship ID is required.');
        }
        
        $db = new PHPWS_DB('intern_emergency_contact');
        $db->addWhere('internship_id', $internshipId);
        $db->addOrder('id ASC'); // Get them in order of ID, so earliest contacts come first
        
        $result = $db->getObjects('EmergencyContactDB');
        
        if(PHPWS_Error::logIfError($result)){
            throw new Exception($result->toString());
        }
        
        if(sizeof($result) <= 0){
            return array(); // Return an empty array
        }
        
        return $result;
    }
    
    /**
     * Returns the EmergencyContact object with the given id from the database.
     * 
     * @param int $id
     * @return EmergencyContact
     * @throws InvalidArgumentException
     * @throws Exception
     * @see EmergencyContactDB
     */
    public static function getContactById($id)
    {
        if(is_null($id) || !isset($id)){
            throw new InvalidArgumentException('Missing contact id.');
        }
        
        $db = new PHPWS_DB('intern_emergency_contact');
        $db->addWHere('id', $id);
        
        $contact = new EmergencyContactDB();
        
        $result = $db->loadObject($contact);
        
        if(PHPWS_Error::logIfError($result)){
            throw new Exception($result->toString());
        }
        
        return $contact;
    }
    
    /**
     * Deletes the passed in EmergencyContact from the database.
     * @param EmergencyContact $contact
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function delete(EmergencyContact $contact)
    {
        $contactId = $contact->getId();
        
        if(is_null($contactId) || !isset($contactId)){
            throw new InvalidArgumentException('Missing contact id.');
        }
        
        $db = new PHPWS_DB('intern_emergency_contact');
        $db->addWhere('id', $contactId);
        
        $result = $db->delete();
        
        if(PHPWS_Error::logIfError($result)){
            throw new Exception($result->toString());
        }
        
        return true;
    }
}
?>
