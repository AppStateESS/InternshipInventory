#!/usr/bin/php
<?php

require_once 'cliCommon.php';
require_once 'dbConnect.php';

ini_set('display_errors', 1);
ini_set('ERROR_REPORTING', E_WARNING);
error_reporting(E_ALL);

$args = array('input_file'=>'');
$switches = array();
check_args($argc, $argv, $args, $switches);

// Open input file
$inputFile = fopen($args['input_file'], 'r');

if($inputFile === FALSE){
    die("Could not open input file.\n");
    exit;
}

$pdo = connectPDO();

$query = "INSERT INTO intern_local_student_data (
                student_id,
                user_name,
                email,
                first_name,
                middle_name,
                last_name,
                preferred_name,
                confidential,
                birth_date,
                gender,
                level,
                campus,
                gpa,
                credit_hours,
                major_code,
                major_description,
                grad_date,
                phone,
                address,
                address2,
                city,
                state,
                zip
            ) VALUES (
                :studentId,
                :username,
                :email,
                :firstName,
                :middleName,
                :lastName,
                :preferredName,
                :confidential,
                :birthDate,
                :gender,
                :level,
                :campus,
                :gpa,
                :creditHours,
                :majorCode,
                :majorDesc,
                :gradDate,
                :phone,
                :address,
                :address2,
                :city,
                :state,
                :zip
            ) ON CONFLICT (student_id) DO UPDATE SET
                student_id = :studentId,
                user_name = :username,
                email = :email,
                first_name = :firstName,
                middle_name = :middleName,
                last_name = :lastName,
                preferred_name = :preferredName,
                confidential = :confidential,
                birth_date = :birthDate,
                gender = :gender,
                level = :level,
                campus = :campus,
                gpa = :gpa,
                credit_hours = :creditHours,
                major_code = :majorCode,
                major_description = :majorDesc,
                grad_date = :gradDate,
                phone = :phone,
                address = :address,
                address2 = :address2,
                city = :city,
                state = :state,
                zip = :zip
            WHERE intern_local_student_data.student_id = :studentId;";

$stmt = $pdo->prepare($query);

$skipfirst = true;

// Parse CSV input into fields line by line
while(($line = fgetcsv($inputFile)) !== FALSE) {

    if($skipfirst === true){
        $skipfirst = false;
        continue;
    }


    $params = array();

    $params['studentId'] = $line[0];

    $params['email'] = $line[7];
    $emailParts = explode("@", $line[7]);
    $params['username'] = $emailParts[0];

    $params['lastName'] = $line[1];
    $params['firstName'] = $line[2];
    $params['middleName'] = ''; // TODO?
    $params['preferredName'] = ''; // TODO?

    $params['confidential'] = 'N'; // TODO?
    $params['birthDate'] = $line[6];
    $params['gender'] = $line[14];

    $params['level'] = 'U'; // TODO
    $params['campus'] = 'main_campus'; // Hard coded
    $params['gpa'] = $line[5];
    $params['creditHours'] = 0; // TODO?

    $params['majorCode'] = ''; // TODO?
    //$params['programDesc'] = $line[3]; // Unused
    $params['majorDesc'] = $line[4];

    $params['gradDate'] = ''; // TODO?
    $params['phone'] = $line[13];

    $params['address'] = $line[8];
    $params['address2'] = $line[9];
    $params['city'] = $line[10];
    $params['state'] = $line[11];
    $params['zip'] = $line[12];

    $stmt->execute($params);
}
