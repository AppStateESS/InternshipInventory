<?php

/**
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

function intern_update(&$content, $currentVersion)
{
    switch ($currentVersion) {
        case version_compare($currentVersion, '0.0.2', '<'):
            $files = array();
            $files[] = 'templates/add_system.tpl';

            PHPWS_Boost::updateFiles($files, 'intern');

        case version_compare($currentVersion, '0.0.3', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_03.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.4', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_04.sql');
            if(PEAR::isError($result)){
                return $result;
            }

        case version_compare($currentVersion, '0.0.6', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_06.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.7', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_07.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.8', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_08.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.9', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_09.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.10', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_10.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.11', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_11.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.12', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_12.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.13', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_13.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.14', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_14.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.15', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_15.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.16', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_16.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.17', '<'):
            PHPWS_Core::initModClass('users', 'Permission.php');
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.18', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_18.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.19', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_19.sql');
            test($result);
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.20', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_20.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.21', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_21.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.22', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_22.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.23', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_23.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.24', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_24.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.25', '<'):
            PHPWS_Core::initModClass('users', 'Permission.php');
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.26', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_26.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.27', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_27.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.28', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_28.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.29', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_29.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.30', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_30.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.31', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_31.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.32', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_32.sql');
            if(PEAR::isError($result)){
                return $result;
            }
            PHPWS_Core::initModClass('users', 'Permission.php');
            Users_Permission::registerPermissions('intern', $content);

        case version_compare($currentVersion, '0.0.33', '<'):
            PHPWS_Core::initModClass('users', 'Permission.php');
            Users_Permission::registerPermissions('intern', $content);

        case version_compare($currentVersion, '0.0.35', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_35.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.0.36', '<'):
            PHPWS_Core::initModClass('users', 'Permission.php');
            Users_Permission::registerPermissions('intern', $content);

        case version_compare($currentVersion, '0.0.37', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_0_0_37.sql');
            if(PEAR::isError($result)){
                return $result;
            }

        case version_compare($currentVersion, '0.1.0', '<'):
            Users_Permission::registerPermissions('intern', $content);

        case version_compare($currentVersion, '0.1.1', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_00.01.01.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.1.2', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_00.01.02.sql');
            if(PEAR::isError($result)){
                return $result;
            }
        case version_compare($currentVersion, '0.1.3', '<'):
            $db = new PHPWS_DB;
            $result = $db->importFile(PHPWS_SOURCE_DIR.
                    'mod/intern/boost/updates/update_00.01.03.sql');
            if(PEAR::isError($result)){
                return $result;
            }
    }

    return TRUE;
}
