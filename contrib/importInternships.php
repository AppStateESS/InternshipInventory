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

$args = array('input_file'=>'','department_id'=>'','term'=>'');
$switches = array();
check_args($argc, $argv, $args, $switches);

// Open input and output files
$inputFile = fopen($args['input_file'], 'r');
$term = $args['term'];
$department_id = $args['department_id'];

if($inputFile === FALSE){
    die("Could not open input file.\n");
    exit;
}

$db = connectToDb();

if(!$db){
    die('Could not connect to database.\n');
}

$values = array();
$values['term'] = $term;
$values['department_id'] = $department_id;
$values['state'] = 'RegisteredState'; // Since these are past internships
$values['level'] = 'U'; // Assuming undergraduate
$values['campus'] = 'main_campus';
$values['multi_part'] = 0;
$values['secondary_part'] = 0;
$values['domestic'] = 1;
$values['major_code'] = '809A';
$values['major_description'] = 'Nursing';
// Parse CSV input into fields line by line
while(($line = fgetcsv($inputFile, 0, ',')) !== FALSE) {
    foreach($line as $key=>$element){
        $line[$key] = pg_escape_string($element);
    }

    $bannerId = $line[2];

    if($bannerId == ''){
        continue;
    }

    $values['last_name'] = $line[0];
    $values['first_name'] = $line[1];
    $values['banner'] = $bannerId;
    $values['gpa'] = $line[3];
    $values['start_date'] = strtotime($line[11]);
    $values['end_date'] = strtotime($line[12]);
    $email = explode('@',$line[4]);
    $values['faculty_id'] = $line[10];
    $values['email'] = $email[0];
    $values['host_id'] = $host_id;
    $values['supervisor_id'] = $supervisor_id;
    $values['loc_state']   = 'NC'; // Data sheet does not have a good location field. They need to fix this
    $values['clinical_practica'] = 1;
    $values['course_no'] = $line[13];
    $values['credits'] = $line[14];

    $intern_result = createInternship($db, $values);

    if($intern_result === false){
        echo pg_last_error() . "\n\n";
    }
}

pg_close($db);
fclose($inputFile);

function createInternship($db, $values) {
  $query = "SELECT NEXTVAL('intern_internship_seq')";
  $id_result = pg_query($query);

  // create new organization
  if($id_result){
    $id_result = pg_fetch_row($id_result);
    $id = $id_result[0];
    $values['id'] = $id;
    $result = pg_insert($db, 'intern_internship', $values);
    return $result;
  }else{
      return false;
  }
}
