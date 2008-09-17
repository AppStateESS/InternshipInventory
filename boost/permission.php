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
    * @version $Id: permission.php 6147 2008-08-27 20:13:56Z matt $
    * @author Verdon Vaillancourt <verdonv at users dot sourceforge dot net>
*/

$use_permissions  = true;
$item_permissions = true;

$permissions['edit_skeleton']   = dgettext('skeleton', 'Add/Edit skeleton');
$permissions['delete_skeleton'] = dgettext('skeleton', 'Delete skeleton');
$permissions['edit_bone']       = dgettext('skeleton', 'Add/Edit bone');
$permissions['delete_bone']     = dgettext('skeleton', 'Delete bone');
$permissions['settings']        = dgettext('skeleton', 'Change settings (Unrestricted only)');

?>