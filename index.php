<?php
/**
    * skeleton - phpwebsite module
    *
    * See docs/AUTHORS and docs/COPYRIGHT for relevant info.
    *
    * This program is free software; you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation; either version 2 of the License, or
    * (at your option) any later version.
    * 
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    * 
    * You should have received a copy of the GNU General Public License
    * along with this program; if not, write to the Free Software
    * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    *
    * @version $Id: index.php 6150 2008-08-28 14:19:40Z verdon $
    * @author Verdon Vaillancourt <verdonv at users dot sourceforge dot net>
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