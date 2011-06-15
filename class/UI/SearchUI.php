<?php

  /**
   * SearchUI
   *
   * Search/Sort by student names and banners, department name, 
   * grad/undergrad, and term.
   * 
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class SearchUI implements UI
{
    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'Major.php');
        PHPWS_Core::initModClass('intern', 'GradProgram.php');
        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');

        // Set up search fields
        $searchForm = new PHPWS_Form();
        $searchForm->setMethod('get');
        $searchForm->addHidden('module', 'intern');
        $searchForm->addHidden('action', 'results');

        $searchForm->addText('name');
        $searchForm->setLabel('name', "Name or Banner ID");
        $terms = Term::getTermsAssoc();
        $searchForm->addSelect('term_select', $terms);
        $searchForm->setLabel('term_select', 'Term');

        // Deity can search for any department. Other users are restricted.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }
        $searchForm->addSelect('dept', $depts);
        $searchForm->setLabel('dept', 'Department');
        
        // Major
        $majors = Major::getMajorsAssoc();
        $searchForm->addSelect('major', $majors);
        $searchForm->setLabel('major', 'Major');
        
        // Grad. Program
        $grad = GradProgram::getGradProgsAssoc();
        $searchForm->addSelect('grad', $grad);
        $searchForm->setLabel('grad', 'Graduate Program');
        
        // Internship types.
        $types = Internship::getTypesAssoc();
        $searchForm->addCheckAssoc('type', $types);

        // Location
        $loc = array('domestic' => 'Domestic',
                     'internat' => 'International');
        $searchForm->addRadioAssoc('loc',$loc);
        $searchForm->addSelect('state', Agency::$UNITED_STATES);
        $searchForm->setLabel('state', 'State');

        $searchForm->addSubmit('submit', 'Search');

        // Javascript...
        javascript('/jquery/');
        javascript('/modules/intern/resetSearch');

        return PHPWS_Template::process($searchForm->getTemplate(), 'intern', 'search.tpl');
    }

}

?>