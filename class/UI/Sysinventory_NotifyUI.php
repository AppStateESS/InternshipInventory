<?php

  /**
   * Sysinventory_NotifyUI
   *
   * Displays all notifications pushed on sysinventory's NQ.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

define('SYSI_SUCCESS', 0);
define('SYSI_ERROR',   1);
define('SYSI_WARNING', 2);
define('SYSI_UNKNOWN', 3);

PHPWS_Core::initModClass('notification', 'NQ.php');

class Sysinventory_NotifyUI
{
    /**
     * Pop all notifications from NQ. Get the type for use with CSS.
     * @return - Properly styled notifications.
     */
    public function display()
    {
        $notifications = NQ::popAll('sysinventory');
        $tags = array();

        foreach($notifications as $notification)
        {
            $type = self::getType($notification);
            $tags['NOTIFICATIONS'][][$type] = $notification->toString();
        }

        $content = PHPWS_Template::process($tags, 'sysinventory', 'notification.tpl');

        Layout::add($content);
    }

    private static function getType(Notification $n)
    {
        switch($n->getType()){
            case SYSI_SUCCESS:
                return 'SUCCESS';
            case SYSI_ERROR:
                return 'ERROR';
            case SYSI_WARNING:
                return 'WARNING';
            default:
                return 'UNKNOWN';
        }
    }
}
?>