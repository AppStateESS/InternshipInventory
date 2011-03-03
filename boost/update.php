<?php

/**
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

function sysinventory_update(&$content, $currentVersion)
{
    switch ($currentVersion) {
        case version_compare($currentVersion, '0.0.2', '<'):
            $files = array();
            $files[] = 'templates/add_system.tpl';
            
            PHPWS_Boost::updateFiles($files, 'sysinventory');

        case version_compare($currentVersion, '0.0.3', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                                      'mod/sysinventory/boost/updates/update_0_0_3.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.4', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                                      'mod/sysinventory/boost/updates/update_0_0_4.sql');
            if(PEAR::isError($result)){
                return $result;
            }
    }
    return TRUE;
}
