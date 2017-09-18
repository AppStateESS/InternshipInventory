<?php

namespace Intern\UI;

use Intern\Internship;
use Intern\TermFactory;
use Intern\DepartmentFactory;
use Intern\Major;
use Intern\GradProgram;
use Intern\Subject;
use Intern\WorkflowStateFactory;
use Intern\DataProvider\Major\MajorsProviderFactory;
use Intern\AssetResolver;

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
        $terms = TermFactory::getTermsAssoc();
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
            $depts = DepartmentFactory::getDepartmentsAssoc();
        }else{
            $depts = DepartmentFactory::getDepartmentsAssocForUsername(\Current_User::getUsername());
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
        $form->addSelect('faculty', array(-1=>'Select Faculty Supervisor'));
        $form->setExtra('faculty', 'disabled');
        $form->setLabel('faculty', 'Faculty Supervisor / Instructor of Record');
        $form->addCssClass('faculty', 'form-control');

        // Hidden field for selected faculty member
        $form->addHidden('faculty_id');

        // Student level radio button, Undergrad major drop down, Graduate major drop down
        // Are all handled in JSX

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
        $tpl = array();
        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'searchInterface');
        $tpl['major_bundle'] = AssetResolver::resolveJsPath('assets.json', 'majorSelector');

        $form->mergeTemplate($tpl);

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'search.tpl');
    }

}
