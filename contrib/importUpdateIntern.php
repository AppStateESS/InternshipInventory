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
    $arrIn = $line[0];
    $new = explode(',', $arrIn);
    $len = count($new);
    $host         = $new[$len-2];
    $subHost      = $new[$len-1];
    array_pop($new);
    array_pop($new);
    $internships = implode("','",$new);
    $internship = "'".$internships."'";

    $sql = "UPDATE intern_internship SET host_id=$host, host_sub_id=$subHost WHERE id IN ($internship)";

    $result = pg_query($sql);

    if($result === false){
        echo $sql . "\n\n";
        echo pg_last_error() . "\n\n";
    }
}

pg_close($db);
fclose($inputFile);
