<?php
/**
 * This handles the login for the module
 *
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_Login {
     
    // Show a login page using LoginUI class.
    function showLogin($error_msg = NULL) {
    PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_LoginUI.php');
    $loginUI = &new Sysinventory_LoginUI();
    $loginUI->setError($error_msg);

    Layout::addStyle('sysinventory');
    Layout::addStyle('controlpanel');
    Layout::add($loginUI->display());
    }
    
    // Main login function
    function doLogin() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {
            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];

            if(!PHPWS_Text::isValidInput($username)) {
                $error_msg = _('Invalid username format.');
                Sysinventory_Login::showLogin($error_msg);
                return;
            }
            
            // Verify password input (jbooker's code)
            if(!preg_match("/^[\.\!\w\s]+$/i",$password)) {
                $error_msg = _('Invalid password format.');
                Sysinventory_Login::showLogin($error_msg);
                return;
            }
        }
    }
 }
     
?>
