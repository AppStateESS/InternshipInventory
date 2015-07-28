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
	public static function display()
	{
		// Get the list of departments the current user has access to



		$tpl = array();
		javascript('jquery_ui');
        javascriptMod('intern', 'facultyEdit');

		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}
