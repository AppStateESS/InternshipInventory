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

namespace Intern\Email;

use \Intern\InternSettings;

/**
 * Exception email to site admin that includes backtrack and debugging info.
 * Sent anytime there's an unhandled exception.
 *
 * @author jbooker
 * @package Intern
 */
class ExceptionEmail extends Email {

    private $exception;

    /**
     * Constructor
     *
     * @param InternSettings $emailSettings
     * @param Exception $e
     */
    public function __construct(InternSettings $emailSettings, \Exception $e)
    {
        parent::__construct($emailSettings);

        $this->exception = $e;
    }

    protected function getTemplateFileName(){
        return 'email/UncaughtException.tpl';
    }

    protected function buildMessage()
    {
        $this->to = explode(',', $this->emailSettings->getExceptionEmail());
        $this->subject = 'Uncaught Exception';

        ob_start();
        echo "Ohes Noes!  Intern Inventory threw an exception that was not caught!\n\n";
        echo "Host: {$_SERVER['SERVER_NAME']}({$_SERVER['SERVER_ADDR']})\n";
        echo 'Request time: ' . date("D M j G:i:s T Y", $_SERVER['REQUEST_TIME']) . "\n";
        if(isset($_SERVER['HTTP_REFERER'])){
            echo "Referrer: {$_SERVER['HTTP_REFERER']}\n";
        }else{
            echo "Referrer: (none)\n";
        }
        echo "Remote addr: {$_SERVER['REMOTE_ADDR']}\n\n";

        $user = \Current_User::getUserObj();
        if(isset($user) && !is_null($user)){
            echo "User name: {$user->getUsername()}\n\n";
        }else{
            echo "User name: (none)\n\n";
        }

        echo "Here is the exception:\n\n";
        print_r($this->exception);

        echo "\n\nHere is REQUEST:\n\n";
        print_r($_REQUEST);

        echo "\n\nHere is CurrentUser:\n\n";
        print_r(\Current_User::getUserObj());

        $this->tpl['MESSAGE'] = ob_get_contents();
        ob_end_clean();
    }
}
