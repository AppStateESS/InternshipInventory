#!/usr/bin/php
<?php
/**
 * Used to import internships from a csv file. There are some assumed values here because the data file
 * we get is incomplete. A human will need to evaluate the format of the csv file and make adjustments
 * as needed.
 */
 use \Intern\EmergencyContactFactory;
 use \Intern\EmergencyContact;
 use \Intern\DatabaseStorage;

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
$emergency = array();
$values['term'] = $term;
$values['department_id'] = $department_id;
$values['state'] = 'RegisteredState'; // Since these are past internships
$values['campus'] = 'main_campus';
$values['domestic'] = 1;
$values['clinical_practica'] = 1;

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
    $values['major_code'] = $line[3];
    $values['major_description'] = $line[4];
    $values['gpa'] = $line[5];
    $values['email'] = $line[6]; //$email = explode('@',$line[5]);
    $values['level'] = $line[7]; //change to letter

    $emergency['name'] = $line[8];
    $emergency['relation'] = $line[9];
    $emergency['phone'] = $line[10];

    $values['remote'] = $line[11]; //change to num
    $values['remote_state'] = $line[12];
    $values['host_id'] = $line[13]; //change to num
    $values['host_sub_id'] = $line[14]; //change to num
    $values['loc_state']   = $line[15];
    $values['loc_phone'] = $line[16];
    $values['faculty_id'] = $line[17]; //make sure fac in inventory
    $values['start_date'] = strtotime($line[18]);
    $values['end_date'] = strtotime($line[19]);
    $values['course_subj'] = $line[20]; //change to num
    $values['course_no'] = $line[21];
    $values['credits'] = $line[22];
    $values['multi_part'] = $line[23];
    $values['secondary_part'] = $line[24];

    $values['supervisor_id'] = createSupervisor();

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

function createEmergency(){
    $newContact = new EmergencyContact($internship, $name, $relation, $phone, $email);
    DatabaseStorage::save($newContact);
}

function createSupervisor(){
  $query = "SELECT NEXTVAL('intern_supervisor_seq')";
  $id_result = pg_query($query);

  // create new supervisor
  if($id_result){
    $id_result = pg_fetch_row($id_result);
    $id = $id_result[0];
    $sql = "INSERT INTO intern_supervisor (id) VALUES ($id)";
    $result = pg_query($sql);
    if($result === false){
        echo "failed to insert supervisor\n\n";
        return false;
    }else{
        return $id;
    }
  }
}
