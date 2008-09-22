<?php
/**
 * class for building queries, running them and displaying the result
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */
class Sysinventory_Query {
    var $allowedLocations = NULL;

    function showQueryBuilder() {
        // if a user is a deity, they get everything...
        if(isset(Current_User::isDeity())){
            $db = &new PHPWS_DB('sysinventory_location');
            $db->addColumn('description');
            $allowedLocations = $db->select();

            if (PEAR::isError($allowedLocations)){
                PHPWS_Error::log($allowedLocations);
            }
        }else if(Current_User::allow('sysinventory','admin'){
            $db = &new PHPWS_DB('sysinventory_admin');
            $db->addColumn('description');
            $db->addColumn('location_id');
            $db->addWhere('username',Current_User::getUsername(),'ILIKE');
        }
    }

}
?>
