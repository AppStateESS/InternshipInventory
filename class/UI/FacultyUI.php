<?php

namespace Intern\UI;

/**
 * View class for the add/edit faculty menu.
 *
 * @author jbooker
 * @package intern
 */
class FacultyUI implements UI
{
	public function display()
	{
		$tpl = array();
		javascript('jquery_ui');
        javascriptMod('intern', 'facultyEdit');

		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}
