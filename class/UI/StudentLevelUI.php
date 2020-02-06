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

/**
*
* @author Cydney Caldwell
*
*/
class StudentLevelUI implements UI {

    public function display()
    {
        /* Permission check */
        if(!\Current_User::allow('intern', 'edit_level')){
            \NQ::simple('intern', NotifyUI::ERROR, "You do not have permission to edit student levels.");
            return ;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editLevel');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_level.tpl');
    }

}
