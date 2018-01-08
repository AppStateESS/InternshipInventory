<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern\UI;

\PHPWS_Core::initModClass('notification', 'NQ.php');

/**
 * Intern_NotifyUI
 *
 * Displays all notifications pushed on intern's NQ.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
class NotifyUI implements UI
{
    const SUCCESS   = 0;
    const ERROR     = 1;
    const WARNING   = 2;
    const UNKNOWN   = 3;

    /**
     * Pop all notifications from NQ. Get the type for use with CSS.
     * @return - Properly styled notifications.
     */
    public function display()
    {
        $notifications = \NQ::popAll('intern');
        $tags = array();

        foreach($notifications as $notification)
        {
            $type = self::getType($notification);
            $tags['NOTIFICATIONS'][][$type] = $notification->toString();
        }

        $content = \PHPWS_Template::process($tags, 'intern', 'notification.tpl');

        return $content;
    }

    private static function getType(\Notification $n)
    {
        switch($n->getType()){
            case NotifyUI::SUCCESS:
                return 'SUCCESS';
            case NotifyUI::ERROR:
                return 'ERROR';
            case NotifyUI::WARNING:
                return 'WARNING';
            default:
                return 'UNKNOWN';
        }
    }
}
