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
		
		$tpl = array();
		
		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.php');
	}
}

?>