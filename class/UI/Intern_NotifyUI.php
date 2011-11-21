<?php

  /**
   * Intern_NotifyUI
   *
   * Displays all notifications pushed on intern's NQ.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

define('INTERN_SUCCESS', 0);
define('INTERN_ERROR',   1);
define('INTERN_WARNING', 2);
define('INTERN_UNKNOWN', 3);

PHPWS_Core::initModClass('notification', 'NQ.php');
PHPWS_Core::initModClass('intern', 'UI/UI.php');

class Intern_NotifyUI implements UI
{
    /**
     * Pop all notifications from NQ. Get the type for use with CSS.
     * @return - Properly styled notifications.
     */
    public static function display()
    {
        $notifications = NQ::popAll('intern');
        $tags = array();

        foreach($notifications as $notification)
        {
            $type = self::getType($notification);
            $tags['NOTIFICATIONS'][][$type] = $notification->toString();
        }

        $content = PHPWS_Template::process($tags, 'intern', 'notification.tpl');
        javascript('jquery');
        
        return $content;
    }

    private static function getType(Notification $n)
    {
        switch($n->getType()){
            case INTERN_SUCCESS:
                return 'SUCCESS';
            case INTERN_ERROR:
                return 'ERROR';
            case INTERN_WARNING:
                return 'WARNING';
            default:
                return 'UNKNOWN';
        }
    }
}
?>