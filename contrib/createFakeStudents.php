#!/usr/bin/php
<?php
/**
 * @license http://opensource.org/licenses/gpl-3.0.html
 * @author Matthew McNaney <mcnaney at gmail dot com>
 */
//completion table don't forget
define('NUMBER_OF_STUDENTS', 20);

define('NICKNAME_CHANCE', 60); // out of 100
define('GRADUATE_CHANCE', 2);

define('TABLE_NAME', 'intern_local_student_data');
define('AUTOCOMPLETE_TABLE_NAME', 'intern_student_autocomplete');
define('EMAIL_DOMAIN', '@appstate.edu');

process($_SERVER['argv']);
echo "\n";

function process($arguments)
{
    $dump_data = false;

    array_shift($arguments);
    if (!isset($arguments[0])) {
        $arguments[0] = '-h';
    }

    $argCount = count($arguments);
    for ($i = 0; $i < $argCount; $i++) {
        if ($arguments[$i] == '-h') {
            print_help();
            exit;
        } elseif ($arguments[$i] == '-f') {
            $i++;
            if (!isset($arguments[$i])) {
                exit("Configuration file not included.\n");
            }
            $file_directory = $arguments[$i];
        } elseif ($arguments[$i] == '-n') {
            $i++;
            if (!isset($arguments[$i])) {
                exit("Student rows not included.\n");
            }
            $number_of_students = $arguments[$i];
        } elseif ($arguments[$i] == '-x') {
            $dump_data = true;
        } else {
            exit("Unknown command\n");
        }
    }

    if (empty($number_of_students)) {
        $number_of_students = NUMBER_OF_STUDENTS;
    }

    include_database_file($file_directory);
    if ($dump_data) {
        $response = readline("Are you sure you want to reset ALL your student tables? (y/N):");
        if ($response == 'y') {
            reset_tables();
        } else {
            echo "Ending script.\n\n";
            exit;
        }
        echo "------------------------------------\n";
        echo "Reset complete.\n";
        echo "------------------------------------\n\n\n";
    }
    echo "------------------------------------\n";
    echo "Creating $number_of_students students.\n\n";
    insert_rows($number_of_students);
    echo "------------------------------------\nStudent creation complete.\n\n";

    echo "Make sure the mod_settings table is set to local data for the student data source.\n";
}

function reset_tables()
{
    $pdo = get_connection();
    echo 'Truncating ' . TABLE_NAME . " table.\n";
    $pdo->exec('truncate ' . TABLE_NAME);
    echo "Truncating intern_student_autocomplete.\n";
    $pdo->exec('truncate intern_student_autocomplete');
}

function get_connection()
{
    static $pdo;
    if (empty($pdo)) {
        //echo get_dsn();
        //echo "\n";
        //echo get_username();
        //echo "\n";
        //echo get_password();
        //echo "\n";
        $pdo = new PDO(get_dsn(), get_username(), get_password());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function get_dsn()
{
    static $dsn_string;

    if (empty($dsn_string)) {
        $dsn_array = dsn_array();
        extract($dsn_array);
        $dsn_string = "$dbtype:dbname=$dbname";
        if (!empty($dbhost)) {
            $dsn_string .= ";host=$dbhost";
        }
        if (!empty($dbport)) {
            $dsn_string .= ";port=$dbport";
        }
    }
    return $dsn_string;
}

function get_username()
{
    $dsn_array = dsn_array();
    return $dsn_array['dbuser'];
}

function get_password()
{
    $dsn_array = dsn_array();
    return $dsn_array['dbpass'];
}

function dsn_array()
{
    static $dsn_array = null;

    if (!empty($dsn_array)) {
        return $dsn_array;
    }

    $dsn = PHPWS_DSN;
    $first_colon = strpos($dsn, ':');
    $second_colon = strpos($dsn, ':', $first_colon + 1);
    $third_colon = strpos($dsn, ':', $second_colon + 1);
    $at_sign = strpos($dsn, '@');
    $first_slash = strpos($dsn, '/');
    $second_slash = strpos($dsn, '/', $first_slash + 1);
    $third_slash = strpos($dsn, '/', $second_slash + 1);

    $dbtype = substr($dsn, 0, $first_colon);
    $dbuser = substr($dsn, $second_slash + 1, $second_colon - $second_slash - 1);
    $dbpass = substr($dsn, $second_colon + 1, $at_sign - $second_colon - 1);
    if ($third_colon) {
        $dbhost = substr($dsn, $at_sign + 1, $third_colon - $at_sign - 1);
    } else {
        $dbhost = substr($dsn, $at_sign + 1, $third_slash - $at_sign - 1);
    }

    $dbname = substr($dsn, $third_slash + 1);

    if ($third_colon) {
        $dbport = substr($dsn, $third_colon + 1, $third_slash - $third_colon - 1);
    } else {
        $dbport = null;
    }

    if ($dbtype == 'mysqli') {
        $dbtype = 'mysql';
    }

    $dsn_array = array('dbname' => $dbname, 'dbtype' => $dbtype, 'dbuser' => $dbuser, 'dbpass' => $dbpass, 'dbhost' => $dbhost, 'dbport' => $dbport);
    return $dsn_array;
}

function include_database_file($file_directory)
{
    if (!is_file($file_directory)) {
        exit("Configuration file not found: $file_directory\n");
    }
    require_once $file_directory;
    if (!defined('PHPWS_DSN')) {
        exit("DSN not found\n");
    }
}

function print_help()
{
    $student_default = NUMBER_OF_STUDENTS;
    echo <<<EOF
Populates the intern_local_student_data table with fake student info for testing.

Usage: createSakeStudents.php -f directory/to/phpwebsite/config/file
       createFakeStudents.php -f directory/to/phpwebsite/config/file -n number-of-students

Commands:
-f      Path to phpWebSite installation's database configuration file.
-n      Number of student records to create. Records are cumulative per script run.
        Defaults to $student_default students.
-x      Truncate the local student data and autocomplete tables.
\n
EOF;
}

function insert_rows($number_of_students)
{
    $db = get_connection();

    for ($i = 0; $i < $number_of_students; $i++) {
        $row = get_row();

        $query = create_soap_query($row);
        $db->exec($query);

        if (!empty($row['preferred_name']) && $row['first_name'] != $row['preferred_name']) {
            $nickname = '"' . $row['preferred_name'] . '" ';
        } else {
            $nickname = null;
        }

        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $middle_name = $row['middle_name'];
        $banner_id = $row['student_id'];
        $username = $row['user_name'];

        echo <<<EOF
$first_name $nickname$middle_name $last_name - $username - $banner_id\n
EOF;
        $ac_query = create_autocomplete_query($row);
        $db->exec($ac_query);
    }
}

function create_soap_query($row)
{
    $db = get_connection();

    foreach ($row as $key => $value) {
        if (is_array($value)) {
            $value = serialize($value);
        }

        $columns[] = $key;
        $values[] = $db->quote($value);
    }
    $query = 'insert into ' . TABLE_NAME . ' (' . implode(',', $columns) . ') values (' . implode(',', $values) . ');';
    return $query;
}

function create_autocomplete_query($row)
{
    extract($row);

    $lfirst = strtolower($first_name);
    $lmiddle = strtolower($middle_name);
    $llast = strtolower($last_name);
    $start_term = '201340';

    $query = <<<EOF
insert into intern_student_autocomplete (
    banner_id,
    username,
    first_name,
    middle_name,
    last_name,
    first_name_lower,
    middle_name_lower,
    last_name_lower,
    first_name_meta,
    middle_name_meta,
    last_name_meta,
    start_term,
    end_term
) values (
    '$student_id',
    '$user_name',
    '$first_name',
    '$middle_name',
    '$last_name',
    '$lfirst',
    '$lmiddle',
    '$llast',
    METAPHONE('$first_name', 4),
    METAPHONE('$middle_name', 4),
    METAPHONE('$last_name', 4),
    '$start_term',
    NULL
);
EOF;
    return $query;
}

function get_row()
{
    $first_name = first_name();
    $middle_name = middle_name($first_name);
    $last_name = last_name();
    $student_level = bool_chance(GRADUATE_CHANCE, 'G', 'U');

    $username = username($last_name, $first_name);
    $email = $username . EMAIL_DOMAIN;

    $address = address();

    $row = array(
        'student_id' => banner_id(),
        'user_name' => username($last_name, $first_name),
        'email' => $email,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'preferred_name' => pref_name($first_name),
        'birth_date' => dob(),
        'gender' => mt_rand(0, 1) ? 'M' : 'F',
        'level' => $student_level,
        'credit_hours' => credit_for_term(),
        'address' => $address['line1'],
        'address2' => $address['line2'],
        'city' => $address['city'],
        'state' => $address['state'],
        'zip' => $address['zip'],
        'phone' => phone_array(),
        'campus' => 'main_campus',
        'gpa' => mt_rand(16, 64) / 16,
        'confidential' => mt_rand(0,1)
    );
    return $row;
}

function phone_array()
{
    $area = mt_rand(0, 1) ? '828' : '704';
    $number = mt_rand(1111111, 9999999);
    //return array(array('area_code' => $area, 'number' => $number, 'ext' => ''));
    return $area . $number;
}

function address()
{
    $box_number = mt_rand(100, 999);
    $aptNumber = mt_rand(1, 30);
    $road = road();
    $city = city();
    $state = 'NC';
    $zip = '28000';

    $add['line1'] = "$box_number $road";
    $add['line2'] = "Apartment $aptNumber";
    $add['city'] = $city;
    $add['county'] = '095'; // not figuring this yet
    $add['state'] = $state;
    $add['zip'] = $zip;

    return $add;
}

function address_array()
{
    $address[1] = address();
    $address[1]['atyp_code'] = 'PS';
    $address[2] = address();
    $address[2]['atyp_code'] = 'PR';
    $address[3]['atyp_code'] = 'AB';
    $address[3]['line1'] = 'ASU Box ' . mt_rand(1000, 40000);
    $address[3]['city'] = 'Boone';
    $address[3]['county'] = '095';
    $address[3]['state'] = 'NC';
    $address[3]['zip'] = '28608';
    return $address;
}

function city()
{
    $cities = array(
        'Boone',
        'Durham',
        'Raleigh',
        'Greensboro',
        'Charlotte',
        'Butner',
        'Fayetteville',
        'Asheville',
        'Asheboro',
        'Cary',
        'Wilmington',
        'Concord',
        'Gastonia',
        'Rocky Mount',
        'Chapel Hill',
        'Burlington',
        'Hickory',
        'Apex',
        'Carrboro'
    );
    $idx = mt_rand(0, count($cities) - 1);
    return $cities[$idx];
}

function road()
{
    $roads = array(
        'Elm',
        'Cactus',
        'Eagle',
        'Oak',
        'Grand',
        'Dale Earnhart',
        'Richard Petty',
        'Melrose',
        '1st',
        'River',
        'Mountain',
        'Chuck Norris',
        'Belview',
        'Corning',
        'Farmers',
        'Smith',
        'Canterbury',
        'Rhubarb',
        'Apple',
        'Orange Grove',
        'Trumpet',
        'Glory',
        'State',
        'Falwell',
        'Georgina',
        'Bayleaf',
        'Umstead',
        'Fairoaks',
        'Russell',
        'Bleak',
        'Redding',
        'Sharon',
        'Critcher',
        'Bamboo',
        'Hospital',
        'Greene',
        'Willowhaven'
    );
    $idx = mt_rand(0, count($roads) - 1);
    $road_name = $roads[$idx];

    $des = array(
        'Street',
        'Road',
        'Avenue',
        'Boulevard'
    );

    $idx = mt_rand(0, count($des) - 1);
    $road_suffix = $des[$idx];

    return "$road_name $road_suffix";
}

function credit_hours($projected_class)
{
    switch ($projected_class) {
        case 'FR':
            return 3 * mt_rand(0, 10);

        case 'SO':
            return 3 * mt_rand(11, 20);

        case 'JR':
            return 3 * mt_rand(21, 30);

        case 'SR':
            return 3 * mt_rand(31, 40);
    }
}

function credit_for_term()
{
    return mt_rand(3, 6) * 3;
}

function projected_class($student_type)
{
    if ($student_type == 'F') {
        return 'FR';
    }

    $pc = mt_rand(1, 100);

    if ($pc <= 60) {
        return 'SO';
    } elseif ($pc < 90) {
        return 'JR';
    } else {
        return 'SR';
    }
}

function bool_chance($threshold, $positive = null, $negative = null)
{
    if (!isset($positive)) {
        $positive = 1;
        $negative = 0;
    }
    return mt_rand(1, 100) <= $threshold ? $positive : $negative;
}

function banner_id()
{
    static $user_ids = array();
    $id = '900' . mt_rand(100000, 999999);
    if (in_array($id, $user_ids)) {
        return banner_id();
    }
    $user_ids[] = $id;
    return $id;
}

// creates student username
function username($last_name, $first_name)
{
    static $all_usernames = array();
    $username = strtolower(substr($last_name, 0, 6) . substr($first_name, 0, 2));
    if (in_array($username, $all_usernames)) {
        return $username . mt_rand(1, 99);
    } else {
        return $username;
    }
}

function pref_name($first_name)
{
    $chance = mt_rand(1, 100);
    if ($chance <= NICKNAME_CHANCE) {
        return nickname($first_name);
    } elseif (mt_rand(1, 15) == 1) {
        // 1 out of 15 chance their first name was entered as preferred
        return $first_name;
    } else {
        return null;
    }
}

function middle_name($first_name)
{
    if (mt_rand(1, 100) == 1) {
        return 'Danger';
    } else {
        $middle_name = first_name();
        if ($middle_name == $first_name) {
            return middle_name($first_name);
        } else {
            return $middle_name;
        }
    }
}

function dob()
{
    $low_range = floor(17 * 86400 * 365.25);
    $high_range = floor(22 * 86400 * 365.25);
    $unix_dob = time() - mt_rand($low_range, $high_range);
    return strftime('%Y-%m-%d', $unix_dob);
}

/**
 * Returns one random first name. Names that are repeated are the most popular names in the US.
 * @staticvar array $first_names
 * @return string
 */
function first_name()
{
    static $first_names = array(
        'Abigail',
        'Abigail',
        'Adina',
        'Adriana',
        'Aiken',
        'Akilah',
        'Alexander',
        'Alexander',
        'Alberto',
        'Alfonzo',
        'Annalisa',
        'Annis',
        'Archer',
        'Arielle',
        'Arnold',
        'Ava',
        'Ava',
        'Ayanna',
        'Barb',
        'Barbie',
        'Bart',
        'Bort',
        'Belkis',
        'Bong',
        'Branden',
        'Brandie',
        'Bree',
        'Brian',
        'Bryon',
        'Carlotta',
        'Carolin',
        'Charlotte',
        'Charlotte',
        'Christy',
        'Cleopatra',
        'Corazon',
        'Crystal',
        'Damian',
        'Daniel',
        'Daniel',
        'Danielle',
        'Danille',
        'Davina',
        'Delmer',
        'Dominque',
        'Elina',
        'Emily',
        'Emily',
        'Emma',
        'Emma',
        'Emilio',
        'Errol',
        'Ethan',
        'Ethan',
        'Fermina',
        'Giselle',
        'Giuseppina',
        'Gregoria',
        'Herta',
        'Jose',
        'Homer',
        'Hwa',
        'Idell',
        'Irma',
        'Isabella',
        'Isabella',
        'Jacob',
        'Jacob',
        'Jame',
        'James',
        'James',
        'Jami',
        'Janean',
        'Janyce',
        'Jaqueline',
        'Jean-Claude',
        'Jen',
        'Jenni',
        'Jennifer',
        'Jeremy',
        'Jim',
        'Jodie',
        'John',
        'Jonathon',
        'Jona',
        'Kesha',
        'Kym',
        'Lacy',
        'Leigha',
        'Leonarda',
        'Liam',
        'Liam',
        'Linnie',
        'Lisa',
        'Lloyd',
        'Loreta',
        'Luella',
        'Luther',
        'Lynsey',
        'Madison',
        'Madison',
        'Marcela',
        'Marge',
        'Mariah',
        'Mariella',
        'Martine',
        'Mary',
        'Masako',
        'Mason',
        'Mason',
        'Matt',
        'Matthew',
        'Mattie',
        'Mia',
        'Mia',
        'Michael',
        'Michael',
        'Modesto',
        'Moses',
        'Natalie',
        'Nicolasa',
        'Noah',
        'Noah',
        'Olivia',
        'Olivia',
        'Otto',
        'Paulita',
        'Queenie',
        'Rachele',
        'Ralph',
        'Renaldo',
        'Richard',
        'Robert',
        'Roselia',
        'Roselyn',
        'Shaina',
        'Shamika',
        'Shanice',
        'Shirley',
        'Sophia',
        'Sophia',
        'Sierra',
        'Stephanie',
        'Summer',
        'Suzann',
        'Suzi',
        'Tarsha',
        'Ted',
        'Tena',
        'Theodore',
        'Todd',
        'Tomasa',
        'Trang',
        'Triet',
        'Trish',
        'Tyra',
        'Ulrike',
        'Ute',
        'Valdemare',
        'Wan',
        'Will',
        'William',
        'William',
        'Willodean',
        'Yi',
        'Zachariah');
    $idx = mt_rand(0, count($first_names) - 1);
    return $first_names[$idx];
}

/**
 * Returns a random last name. Repeats increase odds for most popular names to appear.
 * @staticvar array $last_names
 * @return string
 */
function last_name()
{
    static $last_names = array(
        'Acula',
        'Achenbach',
        'Ahlers',
        'Anderson',
        'Anderson',
        'Arguelles',
        'Bacon',
        'Balentine',
        'Bancroft',
        'Banks',
        'Batten',
        'Beauford',
        'Boehm',
        'Bosley',
        'Brazeal',
        'Brown',
        'Brown',
        'Brown',
        'Carr',
        'Clodfelter',
        'Clubb',
        'Colter',
        'Cowman',
        'Danz',
        'Dawdy',
        'Davis',
        'Davis',
        'Davis',
        'Denn',
        'Drozd',
        'Dumond',
        'Einstein',
        'Enderle',
        'Estep',
        'Evers',
        'Farrah',
        'Fazenbaker',
        'Feid',
        'Ferreira',
        'Flinchum',
        'Fountain',
        'Garcia',
        'Garcia',
        'Garn',
        'Gaskell',
        'Geesey',
        'Goatley',
        'Gonsalez',
        'Gooslin',
        'Graziani',
        'Greenhill',
        'Guyton',
        'Ha',
        'Hailey',
        'Heyne',
        'Holgate',
        'Holmer',
        'Hosea',
        'Hussey',
        'Jared',
        'Jefferson',
        'Johnson',
        'Johnson',
        'Johnson',
        'Johnson',
        'Jones',
        'Jones',
        'Jones',
        'Kettler',
        'Kruger',
        'Kung',
        'Lapointe',
        'Lapp',
        'Larabee',
        'Lincoln',
        'Locklear',
        'Lozoya',
        'Lucchesi',
        'Ludlow',
        'Madrigal',
        'Matter',
        'McCroskey',
        'McGarr',
        'McGuinness',
        'McMath',
        'McNair',
        'McQuire',
        'Mei',
        'Miller',
        'Miller',
        'Moore',
        'Moore',
        'Mullally',
        'Nava',
        'Neagle',
        'Nemec',
        'Nicols',
        'Niebuhr',
        'Oden',
        'Papazian',
        'Patnaude',
        'Phou',
        'Plasse',
        'Polston',
        'Pough',
        'Rodriguez',
        'Rodriguez',
        'Roland',
        'Russum',
        'Sauer',
        'Schick',
        'Sclafani',
        'Searcy',
        'Sells',
        'Shen',
        'Simpson',
        'Siers',
        'Smith',
        'Smith',
        'Smith',
        'Smith',
        'Sparano',
        'Stallone',
        'Sturtz',
        'Sugden',
        'Sumrall',
        'Schwarzenegger',
        'Swindall',
        'Taul',
        'Taylor',
        'Taylor',
        'Theroux',
        'Tiedeman',
        'Tolliver',
        'Tomes',
        'Va Damme',
        'Vento',
        'Vo',
        'Vorhese',
        'Waggoner',
        'Washington',
        'Waye',
        'Whiteford',
        'Whitton',
        'Williams',
        'Williams',
        'Williams',
        'Wilson',
        'Wilson',
        'Winford',
        'Yingling'
    );
    $idx = mt_rand(0, count($last_names) - 1);
    return $last_names[$idx];
}

function nickname($first_name)
{
    $shortened = array(
        'Abigail' => 'Abby',
        'Alexander' => 'Alex',
        'Arnold' => 'Arny',
        'James' => 'Jim',
        'Jennifer' => 'Jenni',
        'Jonathon' => 'Jonny',
        'John' => 'Johnny',
        'Madison' => 'Maddie',
        'Matthew' => 'Matt',
        'Michael' => 'Mike',
        'Richard' => 'Rick',
        'Robert' => 'Rob',
        'Theodore' => 'Ted',
        'William' => 'Billy'
    );

    if (isset($shortened[$first_name])) {
        return $shortened[$first_name];
    }
    // 30 character limit!
    $nicknames = array(
        'Stubby',
        'The Knife',
        'Corndog',
        'The Bard',
        'Dusty',
        'Rickster',
        'LL',
        'Trey',
        'Mr. Fabulous',
        'Pickles',
        'Cthulu',
        'Mork',
        'Fonzie',
        'K.I.T',
        'Animal',
        'Hawk',
        'Heisenberg',
        'Lucky',
        'Peaches',
        'Checkers',
        'Boo Boo',
        'TCB',
        'Elvis',
        'Hayzeus',
        'Achoo',
        'X',
        'Dimples',
        'Knuckles',
        'Flip',
        'Wildone',
        'Gorgeous',
        'Giggles',
        'Lambchop',
        'Chuck',
        'MauMau',
        'Pinky',
        'Yahweh',
        'Snoopy',
        'Puddin',
        'Ducky',
        'Proper Noun',
        'Dynamite',
        'Shorty',
        'Biggy',
        'Average',
        'Duke',
        'Bliss',
        'Gipper',
        'He-Man',
        'Full Auto',
        'Baby',
        'Magnus',
        'Arny',
        'Godrats',
        'Fortuna',
        'T-Bone',
        'Gunny',
        'Jellybean',
        'Peanut',
        'Sloopy',
        'The Cable Guy',
        'Sarge',
        'Salty Dog',
        'Pumpkinhead'
    );
    $idx = mt_rand(0, count($nicknames) - 1);
    return $nicknames[$idx];
}
