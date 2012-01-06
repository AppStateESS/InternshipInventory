<?php

// This file contains the testing defines.  It has been moved out of defines.php
// due to the obvious issue of what to do every time prod is exported.  This
// file is in subversion is inc/hms_defines.php, and should live in phpWebSite's
// root as /inc/hms_defines.php.
require_once(PHPWS_SOURCE_DIR . 'inc/intern_defines.php');


/**
* Name & Email address info - Used for sending out emails
*/
define('SYSTEM_NAME', 'Intern Inventory'); // Used as "from" name in emails
define('EMAIL_ADDRESS', 'noreply'); // user name of email account to send email from
define('DOMAIN_NAME', 'tux.appstate.edu'); // domain name to send email from
define('FROM_ADDRESS', EMAIL_ADDRESS . '@' . DOMAIN_NAME); // fully qualified "from" address
define('TO_DOMAIN', '@'. DOMAIN_NAME); // Default domain to send email to, beginning with '@'

define('REGISTRAR_EMAIL_ADDRESS', 'infoserv@appstate.edu');
?>