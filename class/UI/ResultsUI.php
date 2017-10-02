<?php

namespace Intern\UI;

use Intern\SubselectPager;
use Intern\SubselectDatabase;
use Intern\Student;

/**
 * ResultsUI
 *
 * This is the second half to the search procedure. (Starts in SearchUI.php)
 * ResultsUI shows the pager with search fields taken into account.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
class ResultsUI implements UI {

    public function display()
    {
        javascript('jquery');

        \Layout::addPageTitle('Search Results');

        // Initalize variables
        $dept = null;
        $term = null;
        $name = null;
        $ugradMajor = null;
        $gradProg = null;
        $level = null;
        $campus = null;
        $type = null;
        $loc = null;
        $state = null;
        $country = null;
        $workflowState = null;
        $courseSubject = null;
        $courseNum = null;
        $courseSect = null;
        $oied = null;
        $faculty = null;


        /**
         * Check if any search fields are set.
         * This is a pretty nasty block of code...
         */
        if (isset($_REQUEST['department']))
            $dept = $_REQUEST['department'];
        if (isset($_REQUEST['term_select']))
            $term = $_REQUEST['term_select'];
        if (isset($_REQUEST['name']))
            $name = $_REQUEST['name'];
        if (isset($_REQUEST['ugrad']))
            $ugradMajor = $_REQUEST['ugrad'];
        if (isset($_REQUEST['grad']))
            $gradProg = $_REQUEST['grad'];
        if (isset($_REQUEST['level']) && $_REQUEST['level'] != '-1')
            $level = $_REQUEST['level'];
        if (isset($_REQUEST['type']))
            $type = $_REQUEST['type'];
        if (isset($_REQUEST['campus']))
            $campus = $_REQUEST['campus'];
        if (isset($_REQUEST['location']))
            $loc = $_REQUEST['location'];
        if (isset($_REQUEST['state']))
            $state = $_REQUEST['state'];
        if (isset($_REQUEST['country']))
            $country = $_REQUEST['country'];
        if (isset($_REQUEST['workflow_state']))
            $workflowState = $_REQUEST['workflow_state'];
        if (isset($_REQUEST['course_subj']))
            $courseSubject = $_REQUEST['course_subj'];
        if (isset($_REQUEST['course_no']))
            $courseNum = $_REQUEST['course_no'];
        if (isset($_REQUEST['course_sect']))
            $courseSect = $_REQUEST['course_sect'];
        if (isset($_REQUEST['oied']))
            $oied = $_REQUEST['oied'];
        if (isset($_REQUEST['faculty_id']))
            $faculty = $_REQUEST['faculty_id'];

            /* Get Pager */
        $pager = self::getPager($name, $dept, $term, $ugradMajor, $gradProg, $level, $type, $campus, $loc, $state, $country, $workflowState, $courseSubject, $courseNum, $courseSect, $oied, $faculty);

        $pagerContent = $pager->get();

        // If there were no results, send the user back to the search interface
        if (sizeof($pager->display_rows) == 0) {
            \NQ::simple('intern', NotifyUI::WARNING, "There were no internships that matched your search criteria. If you're looking for a specific student double check the student's name, id number, or email address. Otherwise, try selecting less search criteria and then search again.");
            \NQ::close();

            // Rebuild the URL
            $url = 'index.php?module=intern&action=search&';
            unset($_REQUEST['action']);
            unset($_REQUEST['module']);

            $url .= http_build_query($_REQUEST);

            return \PHPWS_Core::reroute($url);
        }

        return $pagerContent;
    }

    /**
     * Get the DBPager object.
     * Search strings can be passed in too.
     */
    private static function getPager($name = null, $deptId = null, $term = null, $ugradMajor = null, $gradProg = null, $level = null, $type = null, $campus = null, $loc = null, $state = null, $country = null, $workflowState = null, $courseSubject = null, $courseNum = null, $courseSect = null, $oied = null, $faculty = null)
    {
        $pager = new SubselectPager('intern_internship', '\Intern\InternshipRestored');

        // Pager Settings
        $pager->setModule('intern');
        $pager->setTemplate('results.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setReportRow('getCSV');
        $pager->setEmptyMessage('No matching internships found.');

        $pager->db->tables = array();
        $pager->db->addTable('intern_internship', 'fuzzy');

        // If the current user is not a deity and doesn't have the 'all_departments' permission,
        // then add a join to limit the results to just the allowed departments
        if (!\Current_User::isDeity() && !\Current_User::allow('intern', 'all_departments')) {
            $pager->db->addJoin('', 'fuzzy', 'intern_admin', 'department_id', 'department_id');
            $pager->addWhere('intern_admin.username', \Current_User::getUsername());
        }

        // Limit to requested department
        if (!is_null($deptId) && $deptId != -1) {
            $pager->addWhere('department_id', $deptId);
        }

        // Limit to requested term
        if (!is_null($term) && $term != -1) {
            $pager->addWhere('fuzzy.term', $term);
        }

        // Trim text input, if any
        if (!is_null($name)) {
            $name = trim($name);
        }

        // Check to see if name is set and looks like a valid Banner ID
        if (!is_null($name) && preg_match("/\d{8}/", $name)) {
            $pager->addWhere('fuzzy.banner', $name);

            // Else, check to see if name is set
        } else if (!is_null($name) && $name != '') {

            // Prevent SQL Injection and syntax errors, since we're going to be using the addColumnRaw() method.
            $name = addslashes($name);

            /**
             * *
             * Fuzzy Search Settings
             */
            $tokenLimit = 2; // Max number of tokens

            // The fields (db column names) to fuzzy match against, in decreasing order of importance
            // $fuzzyFields = array('last_name', 'first_name', 'middle_name'); //NB: Unused
            $fuzzyTolerance = 3; // Levenshtein distance allowed between the metaphones of a token and a $fuzzyField

            // Initalization
            $orderByList = array();

            // Tokenize the passed in string
            $tokenCount = 0;
            $tokens = array();
            $token = strtok($name, "\n\t, "); // tokenize on newline, tab, comma, space

            while ($token !== false && $tokenCount < $tokenLimit) {
                $tokenCount++;
                $tokens[] = trim(strtolower($token)); // NB: must be lowercase!
                                                      // tokenize on newline, tab, comma, space
                                                      // NB: Don't pass in the string to strtok after the first call above
                $token = strtok("\n\t, ");
            }

            $fuzzyDb = new SubselectDatabase('intern_internship');
            $fuzzyDb->addColumnRaw('intern_internship.*');

            // Foreach token
            for ($i = 0; $i < $tokenCount; $i++) {

                $fuzzyDb->addColumnRaw("LEAST(levenshtein('{$tokens[$i]}', lower(last_name)),levenshtein('{$tokens[$i]}', lower(first_name))) as t{$i}_lev");
                $fuzzyDb->addColumnRaw("LEAST(levenshtein(metaphone('{$tokens[$i]}', 10), last_name_meta),levenshtein(metaphone('{$tokens[$i]}', 10), first_name_meta)) as t{$i}_metalev");

                $pager->db->addWhere("fuzzy.t{$i}_lev", 3, '<', 'OR', 'lev_where');
                $pager->db->addWhere("fuzzy.t{$i}_metalev", $fuzzyTolerance, '<', 'OR', 'metaphone_where');

                // Add order for this token's *_metalev fields
                $orderByList[] = "fuzzy.t{$i}_lev";
                $orderByList[] = "fuzzy.t{$i}_metalev";
            }

            $pager->db->addOrder($orderByList);

            $pager->db->addColumnRaw('fuzzy.*');

            $pager->db->addSubSelect($fuzzyDb, 'fuzzy');
        }

        $pager->db->addJoin('LEFT OUTER', 'fuzzy', 'intern_faculty', 'faculty_id', 'id');
        $pager->db->addJOIN('LEFT OUTER', 'fuzzy', 'intern_department', 'department_id', 'id');

        // Student level
        if (isset($level)) {
            if($level == Student::UNDERGRAD){
                $pager->addWhere('level', Student::UNDERGRAD);
            } else if ($level == Student::GRADUATE || $level == Student::DOCTORAL || $level == Student::POSTDOC) {
                $pager->addWhere('level', Student::GRADUATE, null, 'OR', 'grad_level');
                $pager->addWhere('level', Student::GRADUATE2, null, 'OR', 'grad_level');
                $pager->addWhere('level', Student::DOCTORAL, null, 'OR', 'grad_level');
                $pager->addWhere('level', Student::POSTDOC, null, 'OR', 'grad_level');
            }

            // Major
            if ($level == 'ugrad' && isset($ugradMajor) && $ugradMajor != -1) {
                // Undergrad major
                $pager->addWhere('major_code', $ugradMajor);
            } else if ($level == 'grad' && isset($gradProg) && $gradProg != -1) {
                // Graduate program
                $pager->addWhere('major_code', $gradProg);
            }
        }

        // Experience type
        if (!is_null($type)) {
            $pager->addWhere('experience_type', $type);
        }

        // Course Info
        if (!is_null($courseSubject) && $courseSubject != '-1') {
            $pager->addWhere('course_subj', $courseSubject);
        }

        if (!is_null($courseNum) && $courseNum != '') {
            $pager->addWhere('course_no', $courseNum);
        }

        if (!is_null($courseSect) && $courseSect != '') {
            $pager->addWhere('course_sect', $courseSect);
        }

        // Location
        if (!is_null($loc)) {
            if ($loc == 'domestic') {
                $pager->addWhere('domestic', 1);
            } else if ($loc == 'internat') {
                $pager->addWhere('international', 1);
            }
        }

        // Campus
        if (isset($campus) && $campus != '-1') {
            $pager->addWhere('campus', $campus);
        }

        // Domestic state
        if (!is_null($state) && $state != '-1') {
            $pager->addWhere('loc_state', "%$state%", 'ILIKE');
        }

        // International
        if (!is_null($country) && $country != '-1') {
            $pager->addWhere('loc_country', $country);
        }

        // Workflow state/status
        if (isset($workflowState)) {
            foreach ($workflowState as $s) {
                $path = explode('\\', $s);
                $pager->db->addWhere('state', $path[2], '=', 'OR', 'workflow_group');
            }
        }

        // OIED Certification
        if (isset($oied) && $oied != '-1') {
            $pager->db->addWhere('oied_certified', $oied, '=');
        }

        if (!empty($faculty)){
            $pager->addWhere('faculty_id', $faculty);
        }

        //$pager->db->setTable(array('fuzzy'));

        //var_dump($pager);exit;
        //$pager->db->setTestMode();
		//$pager->db->select();

        /**
         * * Sort Headers **
         */
        $pager->setAutoSort(false);
        $pager->addSortHeader('term', 'Term');

        // $pager->joinResult('student_id', 'intern_student', 'id', 'last_name', 'student_last_name');
        $pager->addSortHeader('last_name', 'Student\'s Name');

        // $pager->joinResult('student_id', 'intern_student', 'id', 'banner');
        $pager->addSortHeader('banner', 'Banner ID');

        $pager->joinResult('department_id', 'intern_department', 'id', 'name');
        $pager->addSortHeader('intern_department.name', 'Department Name');

        //$pager->joinResult('faculty_id', 'intern_faculty', 'id', 'last_name', 'faculty_last_name');
        $pager->addSortHeader('intern_faculty.last_name', 'Supervisor');

        $pager->addSortHeader('state', 'Status');

        /**
         * *** Other Page Tags *****
         */
        $pageTags = array();
        $pageTags['BACK_LINK_URI'] = \PHPWS_Text::linkAddress('intern', array('action' => 'search'));


        $pager->addPageTags($pageTags);

        return $pager;
    }
}
