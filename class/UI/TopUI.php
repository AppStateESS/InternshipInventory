<?php

  /**
   * TopUI
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class TopUI implements UI
{
    public static function display(){}
   
    public static function plug($content, $notifications)
    {
        if(Current_User::isLogged()){
            /* Okay to show the menu */
            $tpl['NOTIFICATIONS'] = $notifications;
            $tpl['HOME_LINK']    = PHPWS_Text::moduleLink('Menu', 'intern');
            $tpl['ADD_LINK']     = PHPWS_Text::moduleLink('Add Student', 'intern', array('action' => 'edit_internship'));
            $tpl['SEARCH_LINK']  = PHPWS_Text::moduleLink('Search', 'intern', array('action' => 'search'));
            $auth = Current_User::getAuthorization();
            $tpl['LOGOUT'] = "<a href='$auth->logout_link'>Logout</a>";
            /* Plug in main UI */
            $tpl['CONTENT']      = $content;
            return PHPWS_Template::process($tpl, 'intern', 'top.tpl');
        }else{
            return $content;
        }
    }
}

?>