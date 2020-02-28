#!/usr/bin/php
<?php
/**
 * Used to import internships from a csv file. There are some assumed values here because the data file
 * we get is incomplete. A human will need to evaluate the format of the csv file and make adjustments
 * as needed.
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

$values = array();

// Parse CSV input into fields line by line
while(($line = fgetcsv($inputFile, 0, ',')) !== FALSE) {
    foreach($line as $key=>$element){
        $line[$key] = pg_escape_string($element);
    }

    $values['main_host_id'] = $line[0];
    $values['sub_name'] = $line[1];
    $values['address'] = $line[2];
    $values['city'] = $line[3];
    $values['state'] = $line[4];
    $values['zip'] = $line[5];
    $values['province'] = $line[6];
    $values['country'] = $line[7];
    $values['sub_approve_flag'] = 1;

    $intern_result = createSubHost($db, $values);

    if($intern_result === false){
        echo pg_last_error() . "\n\n";
    }
}

pg_close($db);
fclose($inputFile);


function createSubHost($db, $values){
    $query = "SELECT NEXTVAL('intern_sub_host_seq')";
    $id_result = pg_query($query);

    // create new sub host
    if($id_result){
      $id_result = pg_fetch_row($id_result);
      $id = $id_result[0];
      $values['id'] = $id;
      $result = pg_insert($db, 'intern_sub_host', $values);
      return $result;
    }else{
        return false;
    }
}
