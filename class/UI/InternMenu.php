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

/**
 * Display the menu page based on what the current (logged in) user can do
 *
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 * @author Jeremy Booker <jbooker at tux dot appstate dot edu>
 * @package intern
 */
class InternMenu implements UI {

    /**
     * Main display method
     */
    public function display()
    {
        javascript('jquery');

        // housekeeping
        if (isset($_SESSION['query']))
            unset($_SESSION['query']);

        $tags = array();

        // Total number of internships for Diety users
        if (\Current_User::isDeity()) {
            $tags['GRAND_TOTAL_LABEL'] = _('Total Internships in Database: ');

            $db = new \PHPWS_DB('intern_internship');
            $gt = $db->select('count');
            $tags['GRAND_TOTAL'] = $gt;
        }

        // Example form link
        $tags['EXAMPLE_LINK'] = \PHPWS_Text::secureLink('Example Contract', 'intern', array('action' => 'example_form'));


        return \PHPWS_Template::process($tags, 'intern', 'menu.tpl');
    }
}
