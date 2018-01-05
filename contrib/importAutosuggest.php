#!/usr/bin/php
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


require_once('cliCommon.php');
require_once('dbConnect.php');

ini_set('display_errors', 1);
ini_set('ERROR_REPORTING', E_WARNING);
error_reporting(E_ALL);

$args = array('input_file'=>'');
$switches = array();
check_args($argc, $argv, $args, $switches);

//$host = 'localhost';

// Open input and output files
$inputFile = fopen($args['input_file'], 'r');

if($inputFile === FALSE){
    die("Could not open input file.\n");
    exit;
}

$db = connectToDb();

if(!$db){
    die('Could not connect to database.\n');
}

// Parse CSV input into fields line by line
while(($line = fgetcsv($inputFile, 0, '|')) !== FALSE) {
    foreach($line as $key=>$element){
        $line[$key] = pg_escape_string($element);
    }

    $bannerId = $line[0];

    if($bannerId == ''){
        continue;
    }

    $firstName  = $line[2];
    $middleName = $line[3];
    $lastName   = $line[1];

    $firstLower  = strtolower($firstName);
    $middleLower = strtolower($middleName);
    $lastLower   = strtolower($lastName);

    $startTerm = $line[4];
    $endTerm   = isset($line[5])&&!empty($line[5])?$line[5]:'NULL';
    $username = $line[6]; //TODO

    $sql = "INSERT INTO intern_student_autocomplete (banner_id, username, first_name, middle_name, last_name, first_name_meta, middle_name_meta, last_name_meta, first_name_lower, middle_name_lower, last_name_lower, start_term, end_term) VALUES ($bannerId, '$username', '$firstName', '$middleName', '$lastName', METAPHONE('$firstName', 4), METAPHONE('$middleName', 4), METAPHONE('$lastName', 4), '$firstLower', '$middleLower', '$lastLower', $startTerm, $endTerm)";


    $result = pg_query($sql);

    if($result === false){
        echo $sql . "\n\n";
        echo pg_last_error() . "\n\n";
    }
}

pg_close($db);
fclose($inputFile);
