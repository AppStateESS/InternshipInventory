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

        
        
        return $form;
    }
}

?>