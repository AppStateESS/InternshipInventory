<?php
/**
 * Pretty display for showing/adding/deleting default systems
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/
class Sysinventory_DefaultUI {
    function showDefaults() {
       if(!Current_User::isDeity()) {
           PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
           $error = 'Uh Uh Uh! You didn\'t say the magic word!';
           Sysinventory_Menu::showMenu($error);
           return;
       }
       PHPWS_Core::initModClass('sysinventory','Sysinventory_Default.php');

       $tpl                 = array();
       $tpl['PAGE_TITLE']   = 'Edit Default Systems';
       $tpl['HOME_LINK']    = PHPWS_Text::moduleLink('Back to menu','sysinventory');
       $tpl['PAGER']        = Sysinventory_Default::doPager();
       $tpl['JSON']         = Sysinventory_Default::getJSON();

       // Set up the form for adding a new default
       $form = new PHPWS_Form('add_default');
       $form->addSubmit('submit','Add Default System');
       $form->setAction('index.php?module=sysinventory');
       $form->addHidden('action','edit_default');
       $form->addHidden('newdefault','yes');
       $form->addText('name');
       $form->setLabel('name','Name for new default system:');
       $form->addText('model');
       $form->setLabel('model','Model:');
       $form->addText('hdd');
       $form->setLabel('hdd','Hard Disk:');
       $form->addText('proc');
       $form->setLabel('proc','Processor:');
       $form->addText('ram');
       $form->setLabel('ram','RAM:');
       $form->addCheck('dual_mon','yes');
       $form->setLabel('dual_mon','Dual Monitor:');
        
       $form->mergeTemplate($tpl);
       $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','default.tpl');
       
       javascript('/jquery/');
       Layout::addStyle('sysinventory','style.css');
       Layout::add($template);

    }
}
?>
