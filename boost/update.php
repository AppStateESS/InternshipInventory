<?php

function internRunDbMigration($fileName)
{
    $db = new PHPWS_DB();
    $result = $db->importFile(PHPWS_SOURCE_DIR . 'mod/intern/boost/updates/' . $fileName);
    if (PEAR::isError($result)) {
        throw new \Exception($result->toString());
    }
}

/**
 *
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 *
 */
function intern_update(&$content, $currentVersion)
{
    PHPWS_Core::initModClass('users', 'Permission.php');

    switch ($currentVersion) {
        case version_compare($currentVersion, '0.0.3', '<') :
            internRunDbMigration('update_0_0_03.sql');
        case version_compare($currentVersion, '0.0.4', '<') :
            internRunDbMigration('update_0_0_04.sql');
        case version_compare($currentVersion, '0.0.6', '<') :
            internRunDbMigration('update_0_0_06.sql');
        case version_compare($currentVersion, '0.0.7', '<') :
            internRunDbMigration('update_0_0_07.sql');
        case version_compare($currentVersion, '0.0.8', '<') :
            internRunDbMigration('update_0_0_08.sql');
        case version_compare($currentVersion, '0.0.9', '<') :
            internRunDbMigration('update_0_0_09.sql');
        case version_compare($currentVersion, '0.0.10', '<') :
            internRunDbMigration('update_0_0_10.sql');
        case version_compare($currentVersion, '0.0.11', '<') :
            internRunDbMigration('update_0_0_11.sql');
        case version_compare($currentVersion, '0.0.12', '<') :
            internRunDbMigration('update_0_0_12.sql');
        case version_compare($currentVersion, '0.0.13', '<') :
            internRunDbMigration('update_0_0_13.sql');
        case version_compare($currentVersion, '0.0.14', '<') :
            internRunDbMigration('update_0_0_14.sql');
        case version_compare($currentVersion, '0.0.15', '<') :
            internRunDbMigration('update_0_0_15.sql');
        case version_compare($currentVersion, '0.0.16', '<') :
            internRunDbMigration('update_0_0_16.sql');
        case version_compare($currentVersion, '0.0.17', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.18', '<') :
            internRunDbMigration('update_0_0_18.sql');
        case version_compare($currentVersion, '0.0.19', '<') :
            internRunDbMigration('update_0_0_19.sql');
        case version_compare($currentVersion, '0.0.20', '<') :
            internRunDbMigration('update_0_0_20.sql');
        case version_compare($currentVersion, '0.0.21', '<') :
            internRunDbMigration('update_0_0_21.sql');
        case version_compare($currentVersion, '0.0.22', '<') :
            internRunDbMigration('update_0_0_22.sql');
        case version_compare($currentVersion, '0.0.23', '<') :
            internRunDbMigration('update_0_0_23.sql');
        case version_compare($currentVersion, '0.0.24', '<') :
            internRunDbMigration('update_0_0_24.sql');
        case version_compare($currentVersion, '0.0.25', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.26', '<') :
            internRunDbMigration('update_0_0_26.sql');
        case version_compare($currentVersion, '0.0.27', '<') :
            internRunDbMigration('update_0_0_27.sql');
        case version_compare($currentVersion, '0.0.28', '<') :
            internRunDbMigration('update_0_0_28.sql');
        case version_compare($currentVersion, '0.0.29', '<') :
            internRunDbMigration('update_0_0_29.sql');
        case version_compare($currentVersion, '0.0.30', '<') :
            internRunDbMigration('update_0_0_30.sql');
        case version_compare($currentVersion, '0.0.31', '<') :
            internRunDbMigration('update_0_0_31.sql');
        case version_compare($currentVersion, '0.0.32', '<') :
            internRunDbMigration('update_0_0_32.sql');
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.33', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.35', '<') :
            internRunDbMigration('update_0_0_35.sql');
        case version_compare($currentVersion, '0.0.36', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.0.37', '<') :
            internRunDbMigration('update_0_0_37.sql');
        case version_compare($currentVersion, '0.1.0', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.1.1', '<') :
            internRunDbMigration('update_00.01.01.sql');
        case version_compare($currentVersion, '0.1.2', '<') :
            internRunDbMigration('update_00.01.02.sql');
        case version_compare($currentVersion, '0.1.3', '<') :
            internRunDbMigration('update_00.01.03.sql');
        case version_compare($currentVersion, '0.1.4', '<') :
            internRunDbMigration('update_00.01.04.sql');
        case version_compare($currentVersion, '0.1.5', '<') :
            internRunDbMigration('update_00.01.05.sql');
        case version_compare($currentVersion, '0.1.6', '<') :
            internRunDbMigration('update_00.01.06.sql');
        case version_compare($currentVersion, '0.1.7', '<') :
            internRunDbMigration('update_00.01.07.sql');
        case version_compare($currentVersion, '0.1.8', '<') :
            internRunDbMigration('update_00.01.08.sql');
        case version_compare($currentVersion, '0.1.9', '<') :
            internRunDbMigration('update_00.01.09.sql');
        case version_compare($currentVersion, '0.1.10', '<') :
            internRunDbMigration('update_00.01.10.sql');
        case version_compare($currentVersion, '0.1.15', '<') :
            Users_Permission::registerPermissions('intern', $content);
        case version_compare($currentVersion, '0.1.21', '<') :
            internRunDbMigration('update_00.01.21.sql');
        case version_compare($currentVersion, '0.1.22', '<') :
            internRunDbMigration('update_00.01.22.sql');
        case version_compare($currentVersion, '0.2.0', '<') :
            internRunDbMigration('update_00.02.00.sql');
        case version_compare($currentVersion, '0.2.1', '<') :
            internRunDbMigration('update_00.02.01.sql');
    }

    return TRUE;
}
