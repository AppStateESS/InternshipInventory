<?php

namespace Intern\UI;


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

        // set up some stuff for the page template
        $tpl                     = array();

        // TODO: Add Javascript autocomplete for usernames.
        javascript('jquery');
        javascriptMod('intern', 'editCourses');


        return \PHPWS_Template::process($tpl, 'intern','edit_courses.tpl');

    }
}
