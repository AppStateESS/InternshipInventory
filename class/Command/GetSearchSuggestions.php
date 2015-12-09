<?php

namespace Intern\Command;

use Intern\StudentProviderFactory;
use Intern\Term;

use Intern\Exception\StudentNotFoundException;
use Intern\Exception\BannerPermissionException;


/**
 * Controller class for getting student search suggestion data in JSON format.
 *
 * @author jbooker
 * @package intern
 */
class GetSearchSuggestions {

    const tokenLimit        = 2;
    const fuzzyTolerance    = 3;
    const resultLimit       = 10;

    public function __construct()
    {

    }

    public function execute()
    {
        $searchString = $_REQUEST['searchString'];

        // If search string is exactly 9 digits, it must be a student id
        // Do an exact lookup and see if we can find the requested student
        if(preg_match('/^([0-9]){9}$/', $searchString)) {
            $students = array();

            try {
                $students = array($this->studentIdSearch($searchString));
            } catch(StudentNotFoundException $e) {
                // TODO Return something more useful here, that says we couldn't find that banner ID.
                $error = array('studentId'=>$searchString, 'error'=>'No matching student found. This student ID may not be valid.');
                echo json_encode(array($error));
                exit;
            } catch(BannerPermissionException $e){
                $error = array('studentId'=>$searchString, 'error'=>'You do not have Banner student data permissions. Please click the \'Get Help\' button in the top navigation bar to open a support request.');
                echo json_encode(array($error));
                exit;
            }

            echo $this->encodeStudents($students);

            exit;
        }


        // Otherwise, try a username lookup and see if we can get an exact match
        $student = $this->userNameSearch($searchString);
        if($student !== false) {
            echo $this->encodeStudents(array($student));
            exit;
        }

        // Otherwise, go to the big database of everyone and try a fuzzy search
        $students = $this->fullNameSearch($searchString);
        echo $this->encodeStudents($students);
        exit;
    }

    /**
     * Attempts to find a student by their student ID. Throws an exception if the student
     * cannot be located.
     *
     * @param $studentId The student's ID number.
     * @throws StudentNotFoundException
     */
    private function studentIdSearch($studentId)
    {
        $student = StudentProviderFactory::getProvider()->getStudent($studentId, Term::timeToTerm(time()));

        return $student;
    }

    /**
     * Searches for suggestions based on username. If an exact match is found, then return
     * a student object. Otherwise return false.
     */
    private function userNameSearch($string)
    {
        $db = \Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_student_autocomplete WHERE username = :username");
        $stmt->execute(array('username' => $string));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result === false) {
            return false;
        }

        $students = array();

        try {
            $students = $this->studentIdSearch($result['banner_id']);
        }catch(\Intern\Exception\StudentNotFoundException $e){
            // Skip any students that are returned from the database, but don't exist
            // in the student info web service
        }

        return $students;
    }

    private function fullNameSearch($string)
    {
        $sql = $this->getFuzzyTextSql($string);
        $results = \PHPWS_DB::getAll($sql);

        $students = array();

        foreach($results as $result) {
            try {
                $students[] = $this->studentIdSearch($result['banner_id']);
            }catch(\Intern\Exception\StudentNotFoundException $e){
                // Skip any students that are returned from the database, but don't exist
                // in the student info web service
                continue;
            }
        }

        return $students;
    }

    private function getFuzzyTextSql($searchString)
    {
        // Initialize arrays for constructing db query
        $columnList  = array();
        $orderByList = array();
        $whereGroups = array();

        // Tokenize the passed in string
        $tokenCount = 0;
        $tokens = array();
        $token = strtok($searchString, "\n\t, "); // tokenize on newline, tab, comma, space

        while($token !== false && $tokenCount < self::tokenLimit){
            $tokenCount++;
            $tokens[] = trim(strtolower($token)); // NB: must be lowercase!
            // tokenize on newline, tab, comma, space
            // NB: Don't pass in the string to strtok after the first call above
            $token = strtok("\n\t, ");
        }

        for($i = 0; $i < $tokenCount; $i++){
            // Add column for least value of (lev-distance between token and first name, lev-distance between token and last name)
            $columnList[] = "LEAST(levenshtein('{$tokens[$i]}', last_name_lower), levenshtein('{$tokens[$i]}', first_name_lower), levenshtein('{$tokens[$i]}', middle_name_lower)) as t{$i}_lev";
            // Add column for least value of (lev-distance between token and metaphone of first name, lev distance between token and metaphone of last name)
            $columnList[] = "LEAST(levenshtein(metaphone('{$tokens[$i]}', 10), last_name_meta), levenshtein(metaphone('{$tokens[$i]}', 10), first_name_meta), levenshtein(metaphone('{$tokens[$i]}', 10), middle_name_meta)) as t{$i}_metalev";

            // Add WHERE clauses for those fields
            $whereGroups['lev_where'][] = "fuzzy.t{$i}_lev < 3";
            $whereGroups['metaphone_where'][] = "fuzzy.t{$i}_metalev < " . self::fuzzyTolerance;

            // Add to ORDER BY list
            $orderByList[] = "fuzzy.t{$i}_lev";
            $orderByList[] = "fuzzy.t{$i}_metalev";
        }

        $subQuery = "SELECT *, " . implode(", ", $columnList) . " FROM intern_student_autocomplete";

        $sql = "SELECT banner_id, username, first_name, last_name, middle_name FROM ($subQuery) as fuzzy WHERE ((" . implode(' OR ', $whereGroups['lev_where']) . ") AND (" . implode(' OR ', $whereGroups['metaphone_where']) . ")) OR username ILIKE '%{$tokens[0]}%' ORDER BY " . implode(', ', $orderByList) . " LIMIT " . self::resultLimit;

        return $sql;
    }

    /**
     * Takes an array of Student objects and encodes them into a
     * json_encoded string.
     */
    private function encodeStudents(Array $students) {
        $studentArray = array();

        foreach($students as $student) {

            // Get the students list of majors
            $majors = $student->getMajors();
            $majorNames = array();
            if(!empty($majors)) {
                foreach($majors as $m) {
                    $majorNames[] = $m->getDescription();
                }
                $major = implode(', ', $majorNames);
            } else {
                $major = 'Unknown Major';
            }

            $studentArray[] = array(
                                   'name' => $student->getLegalName(),
                                   'email' => $student->getUsername() . '@appstate.edu',
                                   'major' => $major,
                                   'studentId' => $student->getStudentId()
                                 );
        }

        return json_encode($studentArray);
    }
}
