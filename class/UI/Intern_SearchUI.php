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

        // Add a null term. User doesn't have to search by term.
        /* This is a ghetto version of array_unshift(). Problem is that array_unshift re-indexes
         * the array that is passed. The indexes in our array is the ID of the associated department
         * name. so there. 
         */ 
        $terms[0] = 'N/A';
        ksort($terms);
        $searchForm->addMultiple('term_select', $terms);
        $searchForm->setLabel('term_select', 'Term');

        // Deity can search for any department. Other users are restricted.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }
        // Add a null department. User doesn't have to search by department.
        /* See long comment a few lines up. */
        $depts[0] = 'N/A';
        ksort($depts);

        $searchForm->addMultiple('dept', $depts);
        $searchForm->setLabel('dept', 'Department');

        $searchForm->addText('name');
        $searchForm->setLabel('name', "Name or Banner ID");
        $searchForm->setAction('index.php?module=intern&action=search');
        $searchForm->addSubmit('submit', 'Submit');
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
            
        }
        if(isset($_REQUEST['term_select'])){
            $term = $_REQUEST['term_select'];
            // Remove ID zero from term search string
            $term = preg_grep('/[^0]/', $term);
            if(empty($term))
                $term = null;
        }
        if(isset($_REQUEST['name'])){
            $name = $_REQUEST['name'];
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
        javascript('open_window');
        javascript('confirm');

        $result = $pager->get();

        // Build up link for export rows to CSV.
        $ids = array();
        foreach($pager->display_rows as $i){
            $ids[] = $i->id;
        }
        // lol hacks
        javascript('/modules/intern/csv', 
                   array('link' => PHPWS_Text::moduleLink('Download CSV', 'intern', array('action' => 'csv', 'ids' => $ids))));

        return $result;
    }

    /**
     * Get the DBPager object. Search strings can be passed in too.
     */
    public static function getPager($name=null, $deptId=null, $term=null)
    {
        $pager = new DBPager('intern_internship', 'Internship');
        $pager->setModule('intern');
        
        $pager->db->addWhere('intern_internship.student_id', 'intern_student.id');
        $pager->db->addWhere('intern_internship.department_id', 'intern_department.id');
        $pager->db->addColumn('intern_internship.*');

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