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
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern', 'Internship.php');

        $dept  = null;
        $term  = null;
        $name  = null;
        $major = null;
        $grad  = null;
        $type  = null;
        $loc   = null;
        $state = null;
        $prov  = null;
        $other = null;
        $approved = null;

        /**
         * Check if any search fields are set.
         */
        if(isset($_REQUEST['dept']))
            $dept = $_REQUEST['dept'];
        if(isset($_REQUEST['term_select']))
            $term = $_REQUEST['term_select'];
        if(isset($_REQUEST['name']))
            $name = $_REQUEST['name'];
        if(isset($_REQUEST['major']))
            $major = $_REQUEST['major'];
        if(isset($_REQUEST['grad']))
            $grad = $_REQUEST['grad'];
        if(isset($_REQUEST['type']))
            $type = $_REQUEST['type'];
        if(isset($_REQUEST['loc']))
            $loc = $_REQUEST['loc'];
        if(isset($_REQUEST['state']))
            $state = $_REQUEST['state'];
        if(isset($_REQUEST['prov']))
            $prov = $_REQUEST['prov'];
        if(isset($_REQUEST['other_type']))
            $other_type = $_REQUEST['other_type'];
        if(isset($_REQUEST['approved']))
            $approved = $_REQUEST['approved'];
        
        /* Get Pager */
        $pager = self::getPager($name, $dept, $term, $major, $grad, $type, $loc, $state, $other_type, $prov, $approved);
        /* Get all results first. For use with CSV Exporting. Then we the limit again. Sorry */
        $pager->limit = null;
        $csvResult = $pager->get();

        /* Javascript */
        javascript('/jquery/');
        javascript('open_window');
        javascript('confirm');
        javascriptMod('intern', 'hider');

        if(!is_null($pager->display_rows)){
            /* Build up the link for exporting rows to CSV. */
            $ids = array();
            foreach($pager->display_rows as $i){
                $ids[] = $i->id;
            }
            /* Add link to page. */
            javascriptMod('intern', 'csv', 
                       array('link' => PHPWS_Text::moduleLink('Download Spreadsheet', 'intern', array('action' => 'csv', 'ids' => $ids))));
        }

        if(isset($_GET['limit'])){
            $pager->limit = $_GET['limit'];
            $pager->display_rows = null;
        }

        $result = $pager->get();
        return $result;
    }

    /**
     * Get the DBPager object. Search strings can be passed in too.
     */
    private static function getPager($name=null, $deptId=null, $term=null,
                                     $major=null, $grad=null, $type=null,
                                     $loc=null, $state=null, $other_type=null,
                                     $prov=null, $approved=null)
    {
        $pager = new DBPager('intern_internship', 'Internship');
        $pager->setModule('intern');
        
        $pager->db->addJoin('LEFT', 'intern_internship', 'intern_student', 'student_id', 'id');
        $pager->db->addJoin('LEFT', 'intern_internship', 'intern_admin', 'department_id', 'department_id');
        $pager->db->addJoin('LEFT', 'intern_internship', 'intern_agency', 'agency_id', 'id');
        if(!Current_User::isDeity())
            $pager->addWhere('intern_admin.username', Current_User::getUsername());

        /* Add Where clauses for each seach field */
        if(!is_null($deptId) && $deptId != -1)
            $pager->addWhere('department_id', $deptId);
        if(!is_null($term) && $term != -1){
            $pager->addWhere('term', $term);
        }
        if(!is_null($name) && $name != ''){
            $pager->addWhere('intern_student.first_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.middle_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.last_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.banner', "%$name%", 'ILIKE', 'OR', 'namez');
        }
        if(!is_null($major) && $major != -1){
            $pager->addWhere('intern_student.ugrad_major', $major);
        }
        if(!is_null($grad) && $grad != -1){
            $pager->addWhere('intern_student.grad_prog', $grad);
        }
        if(!is_null($type)){
            foreach($type as $t){
                switch($t){
                    case 'internship':
                        $pager->addWhere('internship', 1);
                        break;
                    case 'service_learn':
                        $pager->addWhere('service_learn', 1);
                        break;
                    case 'independent_study':
                        $pager->addWhere('independent_study', 1);
                        break;
                    case 'research_assistant':
                        $pager->addWhere('research_assist', 1);
                        break;
                    case 'student_teaching':
                        $pager->addWhere('student_teaching', 1);
                        break;
                    case 'clinical_practica':
                        $pager->addWhere('clinical_practica', 1);
                        break;
                    case 'special_topics':
                        $pager->addWhere('special_topics', 1);
                        break;
                }
            }
        }// End type
        if(!is_null($other_type) && $other_type != ''){
            $pager->addWhere('other_type', "%$other_type%", 'ILIKE');
        }
        if(!is_null($state) && $state != '-1'){
            $pager->addWhere('intern_agency.state', "%$state%", 'ILIKE');
        }
        if(!is_null($prov) && $prov != ''){
            $pager->addWhere('intern_agency.state', "%$prov%", 'ILIKE');
        }
        if(!is_null($loc)){
            if($loc == 'domestic')
                $pager->addWhere('domestic', 1);
            else if($loc == 'internat')
                $pager->addWhere('international', 1);
        }

        if(!is_null($approved)){
            switch($approved){
                case 'approved':
                    $pager->addWhere('intern_internship.approved', 1);
                    break;
                case 'nonapproved':
                    $pager->addWhere('intern_internship.approved', 0);
                    break;
            }
        }
        
        $pager->setTemplate('results.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setEmptyMessage('No Results');

        // Sort headers
        // TODO
        
        $pageTags = array();
        $pageTags['BACK_LINK'] = PHPWS_Text::moduleLink('&laquo; Back to Search', 'intern', array('action' => 'search'));

        $pager->addPageTags($pageTags);

        return $pager;
    }
}
?>
