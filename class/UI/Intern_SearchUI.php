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
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu', 'intern');

        // Set up search fields
        $searchForm = new PHPWS_Form('search');
        $searchForm->addText('lastName');
        $searchForm->setLabel('lastName', 'Student');
        $searchForm->addText('banner');
        $searchForm->setLabel('banner', 'Banner ID');
        $terms = Term::getTermsAssoc();
        // Add a null term. User doesn't have to search by term.
        $terms = array_merge( array(null => 'N/A'), $terms);
        $searchForm->addSelect('term', $terms);
        $searchForm->setLabel('term', 'Select Term');
        // Add a null department. User doesn't have to search by department.
        $depts = Department::getDepartmentsAssoc();
        $depts= array_merge( array(null => 'N/A'), $depts);
        $searchForm->addSelect('deptName', $depts);
        $searchForm->setLabel('deptName', 'Department');
        $searchForm->setAction('index.php?module=intern&action=search');
        $searchForm->addSubmit('submit', 'Search');
        $searchForm->mergeTemplate($tpl);

        $lastName = null;
        $banner = null;
        $deptName = null;
        $term = null;

        // Check for search items in request.
        // If a search item is set then fill in the text field 
        // or select with the value they searched for.
        if(isset($_REQUEST['lastName'])){
            $lastName = $_REQUEST['lastName'];
            $searchForm->setValue('lastName', $lastName);
        }
        if(isset($_REQUEST['banner'])){
            $banner = $_REQUEST['banner'];
            $searchForm->setValue('banner', $banner);
        }
        if(isset($_REQUEST['deptName'])){
            $deptName = $_REQUEST['deptName'];
            $searchForm->setMatch('deptName', $deptName);
        }
        if(isset($_REQUEST['term'])){
            $term = $_REQUEST['term'];
            $searchForm->setMatch('term', $term);
        }
        
        $pager = self::getPager($lastName, $banner, $deptName, $term);
        $pager->addPageTags($searchForm->getTemplate());

        return $pager->get();
    }
    
    public static function getPager($lastName=null, $banner=null, $deptName=null, $term=null){
        $pager = new DBPager('intern_internship', 'Internship');
        $pager->setModule('intern');
        $pager->joinResult('student_id', 'intern_student', 'id', 'last_name');
        $pager->joinResult('student_id', 'intern_student', 'id', 'banner');
        $pager->joinResult('department_id', 'intern_department', 'id', 'name', 'department_name');
        // Search...
        if(!is_null($lastName))
            $pager->addWhere('intern_student.last_name', "%$lastName%", 'ILIKE');
        if(!is_null($banner))
            $pager->addWhere('intern_student.banner', "%$banner%", 'ILIKE');
        if(!is_null($deptName) && $deptName != '')
            $pager->addWhere('intern_department.name', "%$deptName%");
        if(!is_null($term) && $term != '')
            $pager->addWhere('term', "%$term%", 'ILIKE');
            
        $pager->setTemplate('intern_search.tpl');
        $pager->addRowTags('getRowTags');
        $pager->setEmptyMessage('No Results');

        return $pager;
    }
}

?>