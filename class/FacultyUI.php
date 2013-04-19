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
		javascriptMod('intern', 'facultyEdit');
		
		$tpl = array();
		
		$form = new PHPWS_Form('facultyEdit');
		
		$form->addDropBox('department_drop', $departments);
		
		$form->mergeTemplate($tpl);
		$tpl = $form->getTemplate();
		
		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}

?>