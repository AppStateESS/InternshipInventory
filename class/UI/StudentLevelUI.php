<?php

namespace Intern\UI;

use \Intern\AssetResolver;

/**
 *
 * @author Cydney Caldwell
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class StudentLevelUI implements UI {

    public function display()
    {
        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editLevel');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_level.tpl');
    }

}
