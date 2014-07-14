<?php

namespace Intern;

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
		$departments = Department::getDepartmentsAssocForUsername(Current_User::getUsername());

        $renderedDepts = '';
        foreach($departments as $key => $val) {
            $renderedDepts .= PHPWS_Template::process(
                array('ID'=>$key, 'DEPT'=>$val),
                'intern',
                'facultySelectOption.tpl');
        }
		
		$tpl = array();
        $tpl['FACULTY_EDIT'] =
            javascriptMod('intern', 'facultyEdit', array('DEPTS'=>$renderedDepts));
		
		return PHPWS_Template::process($tpl, 'intern', 'editFaculty.tpl');
	}
}

?>
