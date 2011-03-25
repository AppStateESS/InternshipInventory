<?php

  /**
   * This class holds the form for adding an internship.
   */
PHPWS_Core::initModClass('intern', 'UI/UI.php');
class InternshipUI implements UI
{
    public static function display()
    {
        $tpl = array();
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu', 'intern');
        // TODO: PERMISSIONS
        $form = self::getInternshipForm();
  
        $form->mergeTemplate($tpl);
        Layout::addPageTitle('Add Internship');
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'add_internship.tpl');
    }
    
    /**
     * Build the form for adding/editing an internship.
     *
     * If there is an Internship obj passed as parameter
     * then fill in the form with that Internship's fields.
     */
    public static function getInternshipForm(Internship $i=NULL)
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');

        $form = new PHPWS_Form('internship');
        $form->setAction('index.php?module=intern&action=add_internship');
        $form->addSubmit('submit', 'Save');
        
        // Add form fields.
        $form->addSelect('term', Term::getTermsAssoc());
        $form->setLabel('term', 'Select Term');
        
        /**
         * Student fields
         */
        $form->addText('student_first_name');
        $form->setLabel('student_first_name', 'First Name');
        $form->addText('student_middle_name');
        $form->setLabel('student_middle_name', 'Middle Name/Initial');
        $form->addText('student_last_name');
        $form->setLabel('student_last_name', 'Last Name');
        $form->addText('banner');
        $form->setLabel('banner', 'Banner ID');// Digits only
        $form->addText('student_phone');
        $form->setLabel('student_phone', 'Phone');
        $form->addText('student_email');
        $form->setLabel('student_email', 'Email');
        $majors = array('comp sci' => 'comp sci', 'art' => 'art', 'math' => 'math');
        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Major');
        $gradProgs = array('accounting' => 'accounting', 'something' => 'something');
        $form->addSelect('grad_prog', $gradProgs);
        $form->setLabel('grad_prog', 'Graduate Program');
        $form->addCheck('graduated');
        $form->setLabel('graduated', 'Graduated?');

        /**
         * Faculty supervisor info.
         */
        $form->addText('supervisor_first_name');
        $form->setLabel('supervisor_first_name', 'First Name');
        $form->addText('supervisor_middle_name');
        $form->setLabel('supervisor_middle_name', 'Middle Name/Initial');
        $form->addText('supervisor_last_name');
        $form->setLabel('supervisor_last_name', 'Last Name');
        $form->addText('supervisor_email');
        $form->setLabel('supervisor_email', 'Email');
        $form->addText('supervisor_phone');
        $form->setLabel('supervisor_phone', 'Phone');
        $depts = Department::getDepartmentsAssoc();
        $form->addSelect('department', $depts);
        $form->setLabel('department', 'Department');

        /**
         * Agency supervisor info.
         */
        $form->addText('agency_name');
        $form->setLabel('agency_name', 'Name');
        $form->addText('agency_address');
        $form->setLabel('agency_address', 'Address');
        $form->addText('agency_phone');
        $form->setLabel('agency_phone', 'Phone');
        $form->addText('agency_sup_first_name');
        $form->setLabel('agency_sup_first_name', 'First Name');
        $form->addText('agency_sup_last_name');
        $form->setLabel('agency_sup_last_name', 'Last Name');
        $form->addText('agency_sup_phone');
        $form->setLabel('agency_sup_phone', 'Phone');
        $form->addText('agency_sup_email');
        $form->setLabel('agency_sup_email', 'Email');
        $form->addText('agency_sup_address');
        $form->setLabel('agency_sup_address', 'Address');

        /**
         * Internship details.
         */
        $form->addText('start_date');
        $form->setLabel('start_date', 'Start Date');
        $form->addText('end_date');
        $form->setLabel('end_date', 'End Date');
        $form->addText('credits');
        $form->setLabel('credits', 'Credits');
        $form->addText('avg_hours_week');
        $form->setLabel('avg_hours_week', 'Average Hours per Week');
        $form->addCheck('domestic');
        $form->setLabel('domestic', 'Domestic?');
        $form->addCheck('internat');
        $form->setLabel('internat', 'International?');
        $form->addCheck('paid');
        $form->setLabel('paid', 'Paid?');
        $form->addCheck('unpaid', 'Paid?');
        $form->setLabel('unpaid', 'Unpaid?');
        $form->addCheck('stipend');
        $form->setLabel('stipend', 'Stipend?');

        return $form;
    }
}

?>