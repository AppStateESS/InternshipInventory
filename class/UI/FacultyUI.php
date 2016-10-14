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

		return \PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}
