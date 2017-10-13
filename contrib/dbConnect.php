<?php

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

function connectPDO()
{
    $dbhost = trim(readline("Host name: "));
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
    return new PDO("pgsql:host=$dbhost;dbname=$dbname;user=$dbuser;password=$dbpasswd", null, null, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
}
