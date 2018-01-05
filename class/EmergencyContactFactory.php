<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
            throw new \InvalidArgumentException('Internship ID is required.');
        }

        $db = new \PHPWS_DB('intern_emergency_contact');
        $db->addWhere('internship_id', $internshipId);
        $db->addOrder('id ASC'); // Get them in order of ID, so earliest contacts come first

        $result = $db->getObjects('Intern\EmergencyContactDB');

        if(\PHPWS_Error::logIfError($result)){
            throw new \Exception($result->toString());
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
            throw new \InvalidArgumentException('Missing contact id.');
        }

        $db = new \PHPWS_DB('intern_emergency_contact');
        $db->addWHere('id', $id);

        $contact = new EmergencyContactDB();

        $result = $db->loadObject($contact);

        if(\PHPWS_Error::logIfError($result)){
            throw new \Exception($result->toString());
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
            throw new \InvalidArgumentException('Missing contact id.');
        }

        $db = new \PHPWS_DB('intern_emergency_contact');
        $db->addWhere('id', $contactId);

        $result = $db->delete();

        if(\PHPWS_Error::logIfError($result)){
            throw new \Exception($result->toString());
        }

        return true;
    }
}
