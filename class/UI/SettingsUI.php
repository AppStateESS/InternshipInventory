<?php

namespace Intern\UI;
use \Intern\AssetResolver;

/**
 * Class for handling UI for settings editing and creation
 **/
class settingsUI implements UI {

    // Show a list of admins and a form to add a new one.
    public function display() {
        // permissions...
        if(!\Current_User::isDeity()) {
            \NQ::simple('intern', NotifyUI::ERROR, 'You cannot edit settings.');
            return false;
        }

        $tpl = array();
        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json','vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json','adminSettings');

        return \PHPWS_Template::process($tpl, 'intern','settings.tpl');
    }
}
