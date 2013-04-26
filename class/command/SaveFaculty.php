<?php

/**
 * Controller for saving a Faculty object.
 *
 * @author jbooker
 * @package intern
 */
class SaveFaculty {

    public function __construct()
    {

    }

    public function execute()
    {
        PHPWS_Core::initModClass('intern', 'Faculty.php');

        $postarray = json_decode(file_get_contents('php://input'), true);

        $faculty = new FacultyDB();

        $faculty->setId($postarray['id']);
        $faculty->setUsername($postarray['username']);
        $faculty->setFirstName($postarray['first_name']);
        $faculty->setLastName($postarray['last_name']);

        $faculty->setPhone($postarray['phone']);
        $faculty->setFax($postarray['fax']);

        $faculty->setStreetAddress1($postarray['street_address1']);
        $faculty->setStreetAddress2($postarray['street_address2']);
        $faculty->setCity($postarray['city']);
        $faculty->setState($postarray['state']);
        $faculty->setZip($postarray['zip']);

        $a = self::extractVars($faculty);
        
        // Save the faculty object
        PHPWS_Core::initModClass('intern', 'DatabaseStorage.php');
        DatabaseStorage::save($faculty);

        echo json_encode($faculty);

        // Exit, since this is called by JSON
        exit;
    }

    public static function extractVars($o)
    {
        $xary = (array) $o;
        $xarynew = array ();
        foreach ($xary as $k => $v)
        {
            if ($k[0] == "\0")
            {
                // private/protected members have null-delimited prefixes
                // that need to be removed
                $prefix_length = stripos ($k, "\0", 1) + 1;
                $k = substr ($k, $prefix_length, strlen ($k) - $prefix_length);
            }

            // recurse through any objects
            if (is_object ($v))
            {
                $v = object_extractor::get_vars ($v);
            }
            $xarynew[$k] = $v;
        }
        return $xarynew;
    }
}