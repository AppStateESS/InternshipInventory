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
        $searchForm = new PHPWS_Form('search');
        $searchForm->addText('name');
        $searchForm->setLabel('name', "Student's Name");
        $searchForm->addText('banner');
        $searchForm->setLabel('banner', 'Banner ID');
        $terms = Term::getTermsAssoc();

        // Add a null term. User doesn't have to search by term.
        /* This is a ghetto version of array_unshift(). Problem is that array_unshift re-indexes
         * the array that is passed. The indexes in our array is the ID of the associated department
         * name. so there. 
         */ 
        $terms[0] = 'N/A';
        ksort($terms);
        $searchForm->addSelect('term', $terms);
        $searchForm->setLabel('term', 'Term');

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

        $searchForm->addMultiple('deptName', $depts);
        $searchForm->setLabel('deptName', 'Department');
        $searchForm->setAction('index.php?module=intern&action=search');
        $searchForm->addSubmit('submit', 'Search');
        $searchForm->mergeTemplate($tpl);

        $name = null;
        $banner = null;
        $deptName = null;
        $term = null;

        // Check for search items in request.
        // If a search item is set then fill in the text field 
        // or select with the value they searched for.
        if(isset($_REQUEST['name'])){
            $name = $_REQUEST['name'];
            $searchForm->setValue('name', $name);
        }
        if(isset($_REQUEST['banner'])){
            $banner = $_REQUEST['banner'];
            $searchForm->setValue('banner', $banner);
        }
        if(isset($_REQUEST['deptName'])){
            $deptName = $_REQUEST['deptName'];
            if(isset($deptName[0]) && $deptName[0] == '0')
                unset($deptName[0]);
            $searchForm->setMatch('deptName', $deptName);
        }
        if(isset($_REQUEST['term'])){
            $term = $_REQUEST['term'];
            $searchForm->setMatch('term', $term);
        }
        
        $pager = self::getPager($name, $banner, $deptName, $term);
        $pager->addPageTags($searchForm->getTemplate());

        // Automatically open the row with the matching ID.
        $o = -1;
        if(isset($_REQUEST['o'])){
            $o = $_REQUEST['o'];
        }
        // javascript...
        javascript('/jquery/');
        javascript('/modules/intern/hider', array('OPEN' => $o));
        javascript('/modules/intern/csv');
        javascript('open_window');
        javascript('confirm');

        return $pager->get();
    }

    /**
     * Get the DBPager object. Search strings can be passed in too.
     */
    public static function getPager($name=null, $banner=null, $deptId=null, $term=null){
        $pager = new DBPager('intern_internship', 'Internship');
        $pager->setModule('intern');
        
        $pager->db->addWhere('intern_internship.student_id', 'intern_student.id');
        $pager->db->addColumn('intern_internship.*');

        if($name != null && $name != ''){
            $pager->addWhere('intern_student.last_name', "%$name%", 'ILIKE');
        }
        if($banner != null && $banner != ''){
            $pager->addWhere('intern_student.banner', "%$banner%", 'ILIKE');
        }
        if($deptId != null && sizeof($deptId) > 0)
            $pager->addWhere('department_id', $deptId);
        if($term != null && $term != '0'){
            $pager->addWhere('term', $term);
        }
        
        $pager->setTemplate('intern_search.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setEmptyMessage('No Results');
        $pager->setReportRow('getCSV');

        return $pager;
    }
}

?>