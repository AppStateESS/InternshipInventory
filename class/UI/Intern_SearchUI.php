<?php

  /**
   * Intern_SearchUI
   *
   * Pager. Search/Sort by student names and banners, department name, 
   * grad/undergrad, and term.
   * 
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class Intern_SearchUI implements UI
{
    public static function display()
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');

        $tpl = array();
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Menu', 'intern');
        $tpl['ADD_LINK'] = PHPWS_Text::moduleLink('Add Internship', 'intern', array('action' => 'edit_internship'));

        // Set up search fields
        $searchForm = new PHPWS_Form();
        $terms = Term::getTermsAssoc();
        $searchForm->addMultiple('term_select', $terms);
        $searchForm->setLabel('term_select', 'Term');

        // Deity can search for any department. Other users are restricted.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }

        $searchForm->addMultiple('dept', $depts);
        $searchForm->setLabel('dept', 'Department');

        $searchForm->addText('name');
        $searchForm->setLabel('name', "Name or Banner ID");
        $searchForm->setAction('index.php?module=intern&action=search');
        $searchForm->addSubmit('submit', 'Search');
        $searchForm->mergeTemplate($tpl);

        $dept = null;
        $term = null;
        $name = null;

        if(isset($_REQUEST['dept'])){
            $dept = $_REQUEST['dept'];
            // Remove ID zero from dept search string
            $dept = preg_grep('/[^0]/', $dept);
            if(empty($dept))
                $dept = null;
            else
                $searchForm->setMatch('dept', $dept);
            
        }
        if(isset($_REQUEST['term_select'])){
            $term = $_REQUEST['term_select'];
            // Remove ID zero from term search string
            $term = preg_grep('/[^0]/', $term);
            if(empty($term))
                $term = null;
            else
                $searchForm->setMatch('term_select', $term);
        }
        if(isset($_REQUEST['name'])){
            $name = $_REQUEST['name'];
            $searchForm->setValue('name', $name);
        }

        $pager = self::getPager($name, $dept, $term);

        $pager->addPageTags($searchForm->getTemplate());

        // Automatically open the row with the matching ID.
        $o = -1;
        if(isset($_REQUEST['o'])){
            $o = $_REQUEST['o'];
        }

        // javascript...
        javascript('/jquery/');
        javascript('/modules/intern/hider', array('OPEN' => $o));
        javascript('/modules/intern/resetSearch');
        javascript('open_window');
        javascript('confirm');

        $result = $pager->get();

        if(!is_null($pager->display_rows)){
            // Build up link for export rows to CSV.
            $ids = array();
            foreach($pager->display_rows as $i){
                $ids[] = $i->id;
            }
            // lol hacks
            javascript('/modules/intern/csv', 
                       array('link' => PHPWS_Text::moduleLink('Download CSV', 'intern', array('action' => 'csv', 'ids' => $ids))));
        }

        return $result;
    }

    /**
     * Get the DBPager object. Search strings can be passed in too.
     */
    public static function getPager($name=null, $deptId=null, $term=null)
    {
        $pager = new DBPager('intern_internship', 'Internship');
        $pager->setModule('intern');
        
        $pager->db->addJoin('LEFT', 'intern_internship', 'intern_student', 'student_id', 'id');
        $pager->db->addJoin('LEFT', 'intern_internship', 'intern_admin', 'department_id', 'department_id');
        if(!Current_User::isDeity())
            $pager->addWhere('intern_admin.username', Current_User::getUsername());

        // Search by department, term, and name/banner.
        if(!is_null($deptId) && $deptId != '')
            $pager->addWhere('department_id', $deptId);
        if(!is_null($term) && $term != ''){
            $pager->addWhere('term', $term);
        }
        if(!is_null($name) && $name != ''){
            $pager->addWhere('intern_student.first_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.middle_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.last_name', "%$name%", 'ILIKE', 'OR', 'namez');
            $pager->addWhere('intern_student.banner', "%$name%", 'ILIKE', 'OR', 'namez');
        }
            
        $pager->setTemplate('intern_search.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setEmptyMessage('No Results');

        return $pager;
    }
}

?>