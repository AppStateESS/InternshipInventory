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
    } 
}
