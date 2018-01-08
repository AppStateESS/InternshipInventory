<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern\UI;
use \Intern\AssetResolver;

class MajorUI implements UI
{
    public function display()
    {
        /* Check if user can add/edit/hide/delete majors. */
        if(!\Current_User::allow('intern', 'edit_major') &&
           !\Current_User::allow('intern', 'delete_major')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit undergraduate majors.');
            return false;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editMajor');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_major.tpl');
    }
}
