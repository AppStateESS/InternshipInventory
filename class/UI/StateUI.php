<?php

namespace Intern\UI;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class StateUI implements UI {

    public function display()
    {
        $tpl = array();
        javascript('/jquery/');
        javascriptMod('intern', 'pick_state');

        return \PHPWS_Template::process($tpl, 'intern', 'state_list.tpl');
    }

}

?>
