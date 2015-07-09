<?php

/**
 * Class for handling UI for Admin editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/
class AdminUI implements UI {

    // Show a list of admins and a form to add a new one.
    public static function display() {
        // permissions...
        if(!Current_User::isDeity()) {
            NQ::simple('intern', INTERN_ERROR, 'You cannot edit administrators.');
            return false;
        }


        $tpl = array();

        javascript('jquery');
        javascriptMod('intern', 'searchAdmin');


        return PHPWS_Template::process($tpl, 'intern','edit_admin.tpl');

    }
}
