<?php
    /**
    * @author Micah Carter <mcarter at tux dot appstate dot edu>
    */

if (!defined('PHPWS_SOURCE_DIR')) {
    include '../../config/core/404.html';
    exit();
}

PHPWS_Core::initModClass('skeleton', 'Skeleton.php');
$skeleton = new Skeleton;

if (isset($_REQUEST['aop'])) {
    $skeleton->adminMenu();
} elseif (isset($_REQUEST['uop'])) {
    $skeleton->userMenu();
} elseif (isset($_REQUEST['id']) && isset($_REQUEST['bone_id'])) {
    $skeleton->userMenu('view_bone');
} elseif (isset($_REQUEST['id'])) {
    $skeleton->userMenu('view_skeleton');
} elseif (isset($_REQUEST['skeleton']) && isset($_REQUEST['bone'])) {
    $skeleton->userMenu('view_bone');
} elseif (isset($_REQUEST['skeleton'])) {
    $skeleton->userMenu('view_skeleton');
} else {
    PHPWS_Core::home();
}


?>
