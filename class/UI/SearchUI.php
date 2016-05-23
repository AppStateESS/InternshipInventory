<?php

namespace Intern\UI;

use Intern\Internship;
use Intern\Term;
use Intern\Department;
use Intern\Major;
use Intern\GradProgram;
use Intern\Subject;
use Intern\WorkflowStateFactory;
use Intern\MajorsProviderFactory;

  /**
   * SearchUI
   *
   * Search/Sort by student names and banners, department name,
   * grad/undergrad, and term.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
class SearchUI implements UI
{
    public function display()
    {

        \javascript('jquery');
        \javascript('jquery_ui');
        //\javascriptMod('intern', 'spinner');
        \javascriptMod('intern', 'formGoodies');

        // Set up search fields
        $form = new \PHPWS_Form('internship');
        $form->setMethod('get');
        $form->addHidden('module', 'intern');
        $form->addHidden('action', 'results');
        $form->useRowRepeat();

        // Student name or Banner ID
        $form->addText('name');
        $form->setLabel('name', "Name or Banner ID");

        /***************
         * Course Info *
         ***************/
        $terms = Term::getTermsAssoc();
        $form->addSelect('term_select', array(-1 => 'All') + $terms);
        $form->setLabel('term_select', 'Term');
        $form->setClass('term_select', 'form-control');

        $subjects = array('-1' => 'Select subject ') + Subject::getSubjects();
        $form->addSelect('course_subj', $subjects);
        $form->setLabel('course_subj', 'Subject');
        $form->setClass('course_subj', 'form-control');

        $form->addText('course_no');
        $form->setLabel('course_no', 'Course Number');
        $form->setSize('course_no', 6);
        $form->setMaxSize('course_no', 4);
        $form->setClass('course_no', 'form-control');

        $form->addText('course_sect');
        $form->setLabel('course_sect', 'Section');
        $form->setSize('course_sect', 6);
        $form->setMaxSize('course_sect', 4);
        $form->setClass('course_sect', 'form-control');

        /*******************
         * Department Info *
         *******************/

        // Deity can search for any department. Other users are restricted.
        if(\Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(\Current_User::getUsername());
        }

        $depts = array('-1' => 'Select Department') + $depts;

        $form->addSelect('department', $depts);
        $form->setLabel('department', 'Department');
        //$form->setClass('', 'form-control');
        $form->setClass('department', 'form-control');

        // If the user only has one department, select it for them
        // sizeof($depts) == 2 because of the 'Select Deparmtnet' option
        if(sizeof($depts) == 2){
            $keys = array_keys($depts);
            $form->setMatch('department', $keys[1]);
        }

        /***************************
         * Faculty Member Dropdown
         *
         * The options for this drop down are provided through AJAX on page-load and
         * when the user changes the department dropdown above.
         */
        $form->addSelect('faculty', array(-1=>'Select Faculty Advisor'));
        $form->setExtra('faculty', 'disabled');
        $form->setLabel('faculty', 'Faculty Advisor / Instructor of Record');
        $form->addCssClass('faculty', 'form-control');

        // Hidden field for selected faculty member
        $form->addHidden('faculty_id');

        // Student level radio button
        javascriptMod('intern', 'majorSelector', array('form_id'=>$form->id));

        // Student Major dummy box (gets replaced by dropdowns below using JS when student_level is selected)
        $levels = array('-1' => 'Choose student level first');
        $form->addDropBox('student_major', $levels);
        $form->setLabel('student_major', 'Major / Program');
        $form->addCssClass('student_major', 'form-control');

        // Get the majors list
        $majorsList = MajorsProviderFactory::getProvider()->getMajors(Term::timeToTerm(time()));

        // Undergrad major drop down
        $undergradMajors = array('-1'=>'Select Undergraduate Major') + $majorsList->getUndergradMajorsAssoc();
        $form->addSelect('undergrad_major', $undergradMajors);
        $form->setMatch('undergrad_major', '-1');
        $form->setClass('undergrad_major', 'form-control');

        // Graduate major drop down
        $graduateMajors = array('-1'=>'Select Graduate Major') + $majorsList->getGraduateMajorsAssoc();
        $form->addSelect('graduate_major', $graduateMajors);
        $form->setMatch('graduate_major', '-1');
        $form->setClass('graduate_major', 'form-control');

        /*******************
         * Internship Type *
         *******************/
        // Handeled directly in the html template

        /************
         * Location *
         ************/
        // Campus Handeled directly in the html template

        // International vs Domestic - Handeled directly in the html template

        // State & Country search handeled in-browser


        /*******************
         * Workflow States *
         *******************/
        $workflowStates = WorkflowStateFactory::getStatesAssoc();
        unset($workflowStates['Intern\WorkflowState\CreationState']); // Remove this state, since it's not valid (internal only state for initial creation)
        $form->addCheckAssoc('workflow_state', $workflowStates);

        // Internship types.
        $types = Internship::getTypesAssoc();
        $form->addRadioAssoc('type', $types);

        /************************
         * Certification Status *
         ************************/
        // Handeled directly in the html template

        $form->addSubmit('submit', 'Search');

        // Javascript...
        javascriptMod('intern', 'resetSearch');

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'search.tpl');
    }

}
