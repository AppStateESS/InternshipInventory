<?php

PHPWS_Core::initModClass('intern', 'UI/UI.php');

/**
 * View class for the add/edit faculty menu.
 * 
 * @author jbooker
 * @package intern
 */
class FacultyUI implements UI
{
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see UI::display()
	 */
	public function display()
	{
		if (!Current_User::allow('intern', 'edit_faculty') && !Current_User::isDeity()) {
			throw new PermissionException("You don't have permission to edit faculty members.");
		}
		
		// Get the list of departments the current user has access to
		PHPWS_Core::initModClass('intern', 'Department.php');
		$departments = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
		
		javascript('jquery');
		javascript('jquery_ui');
		javascriptMod('intern', 'backbone');
		javascriptMod('intern', 'facultyEdit');
		
		$tpl = array();
		
		$form = new PHPWS_Form('facultyEdit');
		
		
		// Faculty drop down
		$form->addDropBox('department_drop', $departments);
		
		
		// New facult dialog fields
		$form->addText('bannerId');
		$form->addText('username');
		$form->addText('firstName');
		$form->addText('lastName');
		
		$form->addText('phone');
		$form->addText('fax');
		
		$form->addText('streetAddress1');
		$form->addText('streetAddress2');
		$form->addText('city');
		$form->addText('state');
		$form->addText('zip');
		
		$form->mergeTemplate($tpl);
		$tpl = $form->getTemplate();
		
		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}

?>