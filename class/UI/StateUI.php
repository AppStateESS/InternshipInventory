<?php

/**
 * 
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class StateUI implements UI {

    public static function display()
    {
        /* Check if user can add/edit/hide/delete states. */
        if (!Current_User::allow('intern', 'edit_state') &&
                !Current_User::allow('intern', 'delete_state')) {
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to edit undergraduate states.');
            return false;
        }

        $tpl['PAGER'] = StateUI::doPager();

        javascript('/jquery/');
        javascriptMod('intern', 'editState', array('EDIT_ACTION' => State::getEditAction()));

        /* Form for adding new state */
        $form = new PHPWS_Form('add_state');
        $form->addText('name');
        $form->setLabel('name', 'State Title');
        $form->addSubmit('submit', 'Add State');
        $form->setAction('index.php?module=intern&action=' . STATE_EDIT);
        $form->addHidden('add', TRUE);

        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_state.tpl');
    }

    public static function doPager()
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern', 'State.php');
        $pager = new DBPager('intern_state', 'State');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('state_pager.tpl');
        $pager->setEmptyMessage('No States Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }

}

?>
