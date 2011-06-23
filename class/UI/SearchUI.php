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
        $form = new PHPWS_Form();
        $form->setMethod('get');
        $form->addHidden('module', 'intern');
        $form->addHidden('action', 'results');

        $form->addText('name');
        $form->setLabel('name', "Name or Banner ID");
        $terms = Term::getTermsAssoc();
        $form->addSelect('term_select', $terms);
        $form->setLabel('term_select', 'Term');

        // Deity can search for any department. Other users are restricted.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }
        $form->addSelect('dept', $depts);
        $form->setLabel('dept', 'Department');
        
        // Major
        $majors = Major::getMajorsAssoc();
        $form->addSelect('major', $majors);
        $form->setLabel('major', 'Major');
        
        // Grad. Program
        $grad = GradProgram::getGradProgsAssoc();
        $form->addSelect('grad', $grad);
        $form->setLabel('grad', 'Graduate Program');
        
        // Internship types.
        $types = Internship::getTypesAssoc();
        $form->addCheckAssoc('type', $types);
        $form->addText('other_type');
        $form->setLabel('other_type', 'Other Internship Type:');

        // Location
        $loc = array('domestic' => 'Domestic',
                     'internat' => 'International');
        $form->addRadioAssoc('loc',$loc);
        /* State search */
        $form->addSelect('state', Agency::$UNITED_STATES);
        $form->setLabel('state', 'State');
        /* Province search */
        $form->addText('prov');

        $form->setLabel('prov', 'Provine/Territory');
        

        $form->addSubmit('submit', 'Search');

        // Javascript...
        javascript('/jquery/');
        javascript('/intern/resetSearch');

        return PHPWS_Template::process($form->getTemplate(), 'intern', 'search.tpl');
    }

}

?>