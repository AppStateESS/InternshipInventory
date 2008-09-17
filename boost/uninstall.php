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
    * @version $Id: uninstall.php 6147 2008-08-27 20:13:56Z matt $
    * @author Verdon Vaillancourt <verdonv at users dot sourceforge dot net>
*/

function skeleton_uninstall(&$content) {

    PHPWS_DB::dropTable('skeleton_skeletons');
    PHPWS_DB::dropTable('skeleton_bones');
    $content[] = dgettext('skeleton', 'Skeleton tables dropped.');

    return true;
}
?>