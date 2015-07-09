<?php
	class GetAdminView
	{
		const MODULE = 'intern';
		const FILE = 'edit_admin.tpl';

		public function display()
		{
			
			javascriptMod('intern', 'searchAdmin');
			

			$tpl = array();

			return PHPWS_Template::process($tpl, self::MODULE, self::FILE);
		}
	}
?>