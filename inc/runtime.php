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

/***
 * Commenting this out for now.. Need to test more extensively when we can get r6test updated to latest phpws
if(!Current_User::isLogged() && isset($_SERVER['HTTP_SHIB_EP_PRINCIPALNAME'])) {
    NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "You have successfully signed in, but we have not setup your Internship Inventory account. Please contact the Career Development Center at 828-262-2180.");
    NQ::close();
}
*/

if (PHPWS_Core::atHome() && Current_User::isLogged()) {
    $path = $_SERVER['SCRIPT_NAME'].'?module=intern';

    header('HTTP/1.1 303 See Other');
    header("Location: $path");
    exit();
}
