<?php

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
