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
        PHPWS_Core::initModClass('intern', 'Subject.php');
        PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');

        // Set up search fields
        $form = new PHPWS_Form();
        $form->setMethod('get');
        $form->addHidden('module', 'intern');
        $form->addHidden('action', 'results');
        $form->useRowRepeat();

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
        
        // If the user only has one department, select it for them
        // sizeof($depts) == 2 because of the 'Select Deparmtnet' option
        if(sizeof($depts) == 2){
            $keys = array_keys($depts);
            $form->setMatch('dept', $keys[1]);
        }

        // Student level radio button
        javascript('jquery');
        javascriptMod('intern', 'majorSelector', array('form_id'=>$form->id));
        $levels = array('ugrad' => 'Undergraduate', 'grad' => 'Graduate');
        $form->addRadioAssoc('student_level', $levels);
        //$form->setMatch('student_level', 'ugrad');
        
        // Campus
        $campuses = array('main_campus'=>'Main Campus',
                          'distance_ed'=>'Distance Ed');
        $form->addRadioAssoc('campus', $campuses);

        // Undergrad major drop down
        if (isset($s)){
            $majors = Major::getMajorsAssoc($s->ugrad_major);
        }else{
            $majors = Major::getMajorsAssoc();
        }

        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Majors &amp; Certificate Programs');

        // Graduate major drop down
        if (isset($s)){
            $progs = GradProgram::getGradProgsAssoc($s->grad_prog);
        }else{
            $progs = GradProgram::getGradProgsAssoc();
        }

        $form->addSelect('grad_prog', $progs);
        $form->setLabel('grad_prog', 'Graduate Majors &amp; Certificate Programs');


        /***************
         * Course Info *
         ***************/
        $subjects = Subject::getSubjects();
        $form->addSelect('course_subj', $subjects);
        $form->setLabel('course_subj', 'Subject');
        
        $form->addText('course_no');
        $form->setLabel('course_no', 'Number');
        
        $form->addText('course_sect');
        $form->setLabel('course_sect', 'Section');


        // Internship types.
        $types = Internship::getTypesAssoc();
        $form->addCheckAssoc('type', $types);

        // Location
        $loc = array('domestic' => 'Domestic',
                     'internat' => 'International');
        $form->addRadioAssoc('loc',$loc);

        /* State search */
        $db = new PHPWS_DB('intern_state');
        $db->addWhere('active', 1);
        $db->addColumn('abbr');
        $db->addColumn('full_name');
        $db->setIndexBy('abbr');
        // get backwards because we flip it
        $db->addOrder('full_name desc');
        $states = $db->select('col');
        if (empty($states)) {
        	NQ::simple('intern', INTERN_ERROR, 'The list of allowed US states for internship locations has not been configured. Please use the administrative options to <a href="index.php?module=intern&action=edit_states">add allowed states.</a>');
        	NQ::close();
        	PHPWS_Core::goBack();
        }
        $states[-1] = 'Select state';
        $states = array_reverse($states, true);
        $form->addSelect('state', $states);
        $form->setLabel('state', 'State');

        /* Province search */
        $form->addText('prov');
        $form->setLabel('prov', 'Province/Territory');
        
        // Workflow states
        $workflowStates = WorkflowStateFactory::getStatesAssoc();
        unset($workflowStates['CreationState']); // Remove this state, since it's not valid (internal only state for initial creation)
        $form->addCheckAssoc('workflow_state', $workflowStates);
        
        $form->addSubmit('submit', 'Search');

        // Javascript...
        javascript('jquery');
        javascriptMod('intern', 'resetSearch');

        return PHPWS_Template::process($form->getTemplate(), 'intern', 'search.tpl');
    }

}

?>
