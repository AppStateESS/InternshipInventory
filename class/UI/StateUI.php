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
        javascriptMod('intern', 'pick_state');
        $db = new PHPWS_DB('intern_state');
        $db->addOrder('full_name');
        $states = $db->select();
        foreach ($states as $state) {
            extract($state); //abbr, full_name, active
            $row = array('ABBR' => $abbr, 'NAME' => $full_name);
            if (!$active) {
                $tpl['state_row'][] = $row;
            } else {
                $tpl['active_row'][] = $row;
            }
        }

        return PHPWS_Template::process($tpl, 'intern', 'state_list.tpl');
    }

}

?>
