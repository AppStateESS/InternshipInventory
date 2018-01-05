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
 * Class for handling UI for Admin editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/
class CoursesUI implements UI {

    // Show a list of admins and a form to add a new one.
    public function display() {
        // permissions...
        if(!\Current_User::allow('intern', 'edit_courses')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit courses.');
            return false;
        }

        // Grab subjects to be loaded with the page
        $internSubjects =  array("-1" => "Select subject...") + \Intern\Subject::getSubjects();

        foreach($internSubjects as $key => $value)
        {
            // Needed for re-ordering in Chrome
            $internSubjects["_" . $key] = $value;
            unset($internSubjects[$key]);
        }

        $subjects = json_encode($internSubjects);

        $tpl = array();
        $tpl['SUBJECTS'] = $subjects;
        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editExpectedCourses');

        return \PHPWS_Template::process($tpl, 'intern','edit_courses.tpl');

    }
}
