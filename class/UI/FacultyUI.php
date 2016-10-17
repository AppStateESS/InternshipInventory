<?php

namespace Intern\UI;
use \Intern\AssetResolver;

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

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'facultyEdit');

		return \PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}
