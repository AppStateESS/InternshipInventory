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

/**
 * Connects to a DB by prompting the user for user name, password, and db name.
 * @returns <Resource> $db  Database connection handle
 */
function connectToDB()
{

    $dbname = trim(readline("Database name: "));

    $dbuser = trim(readline("User name: "));

    // A bit of hackery here to avoid echoing the password
    echo "Database Password: ";
    system('stty -echo');
    $dbpasswd = trim(fgets(STDIN));
    system('stty echo');
    // add a new line since the users CR didn't echo
    echo "\n";


    // Connect to the database
    //$db = pg_connect("host=$host dbname=$database user=$dbuser password=$dbpasswd");
    $db = pg_connect("user=$dbuser password=$dbpasswd dbname=$dbname");

    if(!$db){
        die('Could not connect to database.\n');
    }

    return $db;
}
