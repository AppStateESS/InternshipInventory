<?php
/**
 * Class for displaying departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_DepartmentUI {

    function display() {
        
        // Check permissions.  Non-deities should never see this page
        // unless they're trying to be sneaky, since the link to it would
        // be hidden.
        if(!Current_User::isDeity()){
            return "Uh Uh Uh! You didn't say the magic word!";
        }

        // Set extra page tags
        

        // Form for adding new department
        $form = &new PHPWS_Form('add_department');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=sysinventory&action=edit_departments');
        $form->addHidden('addDep',TRUE);

        $tpl['PAGER'] = Sysinventory_DepartmentUI::doPager();
        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'sysinventory', 'edit_department.tpl');
    }

    function doPager() {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');

        $pager = &new DBPager('sysinventory_department','Sysinventory_Department');
        $pager->setModule('sysinventory');
        $pager->setTemplate('department_pager.tpl');
        $pager->addRowTags('get_row_tags');
        $pager->setEmptyMessage('No Departments Found.');
        return $pager->get();
    }
 }
?>
