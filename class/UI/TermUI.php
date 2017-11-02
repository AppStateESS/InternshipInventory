<?php

namespace Intern\UI;

use \Intern\AssetResolver;

/**
 * Class for handing UI for Term editing and creation
 * @author Olivia Perugini <peruginioc at appstate dot edu>
 **/
 class TermUI implements UI {

    public function display() {

        // permissions, if needed ?
        if(!\Current_User::allow('intern', 'edit_terms')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit terms.');
            return false;
        }

        $tpl = array();
        //$tpl['S']

        return \PHPWs_Template::process($tpl, 'intern', 'edit_terms.tpl');
    }
 }
