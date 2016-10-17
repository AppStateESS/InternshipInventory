<?php

namespace Intern\UI;

use \Intern\AssetResolver;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class StateUI implements UI {

    public function display()
    {
        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'stateList');

        return \PHPWS_Template::process($tpl, 'intern', 'state_list.tpl');
    }

}
