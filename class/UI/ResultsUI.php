<?php

/**
 * ResultsUI
 *
 * This is the second half to the search procedure. (Starts in SearchUI.php)
 * ResultsUI shows the pager with search fields taken into account.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class ResultsUI implements UI
{
    public static function display()
    {
        PHPWS_Core::initCoreClass('SubselectPager.php');
        PHPWS_Core::initModClass('intern', 'Internship.php');

        Layout::addPageTitle('Search Results');

        $dept   = null;
        $term   = null;
        $name   = null;
        $level  = null;
        $major  = null;
        $campus = null;
        $type   = null;
        $loc    = null;
        $state  = null;
        $prov   = null;
        $workflowState = null;

        /**
         * Check if any search fields are set.
         */

        if(isset($_REQUEST['dept']))
            $dept = $_REQUEST['dept'];
        if(isset($_REQUEST['term_select']))
            $term = $_REQUEST['term_select'];
        if(isset($_REQUEST['name']))
            $name = $_REQUEST['name'];
        if(isset($_REQUEST['ugrad_major']))
            $major = $_REQUEST['ugrad_major'];
        if(isset($_REQUEST['grad_major']))
            $major = $_REQUEST['grad_major'];
        if(isset($_REQUEST['student_level']))
            $level = $_REQUEST['student_level'];
        if(isset($_REQUEST['type']))
            $type = $_REQUEST['type'];
        if(isset($_REQUEST['campus']))
            $campus = $_REQUEST['campus'];
        if(isset($_REQUEST['loc']))
            $loc = $_REQUEST['loc'];
        if(isset($_REQUEST['state']))
            $state = $_REQUEST['state'];
        if(isset($_REQUEST['prov']))
            $prov = $_REQUEST['prov'];
        if(isset($_REQUEST['workflow_state']))
            $workflowState = $_REQUEST['workflow_state'];

        /* Get Pager */
        $pager = self::getPager($name, $dept, $term, $major, $level, $type, $campus, $loc, $state, $prov, $workflowState);

        /* Javascript */
        javascript('/jquery/');
        javascript('open_window');
        javascript('confirm');
        javascriptMod('intern', 'hider');

        return $pager->get();
    }

    /**
     * Get the DBPager object. Search strings can be passed in too.
     */
    private static function getPager($name = null, $deptId = null, $term = null,
            $major = null, $level = null, $type = null,
            $campus = null,$loc = null, $state = null,
            $prov = null, $workflowState = null)
    {
        $pager = new SubselectPager('intern_internship', 'Internship');

        // Pager Settings
        $pager->setModule('intern');
        $pager->setTemplate('results.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setReportRow('getCSV');
        $pager->setEmptyMessage('No matching internships found.');

        $pager->db->tables = array();
        $pager->db->addTable('intern_internship', 'fuzzy');
        
        //$pager->db->addJoin('LEFT', 'fuzzy', 'intern_agency', 'agency_id', 'id');
        
        if(!Current_User::isDeity()){
            $pager->db->addJoin('', 'fuzzy', 'intern_admin', 'department_id', 'department_id');
            $pager->addWhere('intern_admin.username', Current_User::getUsername());
            //$pager->db->setTestMode();
            //$pager->db->select();
        }

        // Limit to requested department
        if(!is_null($deptId) && $deptId != -1){
            
            $pager->addWhere('department_id', $deptId);
        }

        // Limit to requested term
        if(!is_null($term) && $term != -1){
            $pager->addWhere('fuzzy.term', $term);
        }

        // Check to see if name is set and looks like a valid Banner ID
        if(!is_null($name) && preg_match("/\d{8}/", $name)){
            $pager->addWhere('fuzzy.banner', $name);
        }
        
        // Check to see if name is set
        if(!is_null($name) && $name != ''){

            /***
             * Fuzzy Search Settings 
             */
            $tokenLimit = 2; // Max number of tokens

            // The fields (db column names) to fuzzy match against, in decreasing order of importance
            $fuzzyFields = array('last_name', 'first_name', 'middle_name');
            $fuzzyTolerance = 2; // Levenshtein distance allowed between the metaphones of a token and a $fuzzyField
            
            // Initalization
            $orderByList = array();
            $whereSet = array();
            
            // Tokenize the passed in string
            $tokenCount = 0;
            $tokens = array();
            $token = strtok($name, "\n\t, "); // tokenize on newline, tab, comma, space
            
            while($token !== false && $tokenCount < $tokenLimit){
                $tokenCount++;
                $tokens[] = trim(strtolower($token)); // NB: must be lowercase!
                // tokenize on newline, tab, comma, space
                // NB: Don't pass in the string to strtok after the first call above
                $token = strtok("\n\t, ");
            }
            
            $fuzzyDb = new SubselectDatabase('intern_internship');
            $fuzzyDb->addColumn('intern_internship.*');
            
            // Foreach token
            for($i = 0; $i < $tokenCount; $i++){
                
                $fuzzyDb->addColumnRaw("LEAST(levenshtein('{$tokens[$i]}', lower(last_name)),levenshtein('{$tokens[$i]}', lower(first_name))) as t{$i}_lev");
                $fuzzyDb->addColumnRaw("LEAST(levenshtein(metaphone('{$tokens[$i]}', 10), last_name_meta),levenshtein(metaphone('{$tokens[$i]}', 10), first_name_meta)) as t{$i}_metalev");
                
                $pager->db->addWhere("fuzzy.t{$i}_lev", 2, '<', 'OR', 'lev_where');
                $pager->db->addWhere("fuzzy.t{$i}_metalev", $fuzzyTolerance, '<', 'OR', 'metaphone_where');
                
                // Add order for this token's *_metalev fields
                $orderByList[] = "fuzzy.t{$i}_lev";
                $orderByList[] = "fuzzy.t{$i}_metalev";
            }

            $pager->db->addOrder($orderByList);
            
            $pager->db->addColumnRaw('fuzzy.*');
            
            $pager->db->addSubSelect($fuzzyDb, 'fuzzy');
        }
        
        //test($pager->db->table_as,1);
        
        $pager->db->addJoin('LEFT OUTER', 'fuzzy', 'intern_faculty_supervisor', 'faculty_supervisor_id', 'id');

        //$pager->db->setTestMode();
        //$pager->db->select();
        
        // Student level
        if(isset($level)){
            $pager->addWhere('level', $level);

            // Major
            if(isset($major) && $major != -1){
                if($level == 'grad'){
                    // Graduate
                    $pager->addWhere('grad_prog', $major);
                }else if($level = 'ugrad'){
                    // Undergraduate
                    $pager->addWhere('ugrad_major', $major);
                }
            }
        }

        // Experience type
        if(!is_null($type)){
            foreach($type as $t){
                switch($t){
                    case 'internship':
                        $pager->addWhere('internship', 1);
                        break;
                    case 'student_teaching':
                        $pager->addWhere('student_teaching', 1);
                        break;
                    case 'clinical_practica':
                        $pager->addWhere('clinical_practica', 1);
                        break;
                }
            }
        }// End type

        // Location
        if(!is_null($loc)){
            if($loc == 'domestic'){
                $pager->addWhere('domestic', 1);
            }else if($loc == 'internat'){
                $pager->addWhere('international', 1);
            }
        }

        // Campus
        if(isset($campus)){
            $pager->addWhere('campus', $campus);
        }

        // Domestic state
        if(!is_null($state) && $state != '-1'){
            $pager->addWhere('loc_state', "%$state%", 'ILIKE');
        }

        // International
        if(!is_null($prov) && $prov != ''){
            $pager->addWhere('loc_country', "%$prov%", 'ILIKE', 'OR', 'intl_loc');
            $pager->addWhere('loc_province', "%$prov%", 'ILIKE', 'OR', 'intl_loc');
        }

        // Workflow state/status
        if(isset($workflowState)){
            foreach($workflowState as $s){
                $pager->db->addWhere('state', $s, '=', 'OR', 'workflow_group');
            }
        }
    

        /*** Sort Headers ***/
        $pager->setAutoSort(false);
        $pager->addSortHeader('term', 'Term');

        //$pager->joinResult('student_id', 'intern_student', 'id', 'last_name', 'student_last_name');
        $pager->addSortHeader('last_name', 'Student\'s Name');

        //$pager->joinResult('student_id', 'intern_student', 'id', 'banner');
        $pager->addSortHeader('banner', 'Banner ID');

        $pager->joinResult('department_id', 'intern_department', 'id', 'name');
        $pager->addSortHeader('name', 'Department Name');

        //$pager->joinResult('faculty_supervisor_id', 'intern_faculty_supervisor', 'id', 'last_name', 'faculty_last_name');
        $pager->addSortHeader('intern_faculty_supervisor.last_name', 'Faculty Advisor');

        $pageTags = array();
        $pageTags['BACK_LINK'] = PHPWS_Text::moduleLink('&laquo; Back to Search', 'intern', array('action' => 'search'));

        $pager->addPageTags($pageTags);

        return $pager;
    }
}
?>
