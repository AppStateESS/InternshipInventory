<?php

namespace Intern\UI;
use Intern\InternshipFormView;

/**
 * This class holds the form for adding/editing an internship.
 */
class InternshipUI implements UI {

    public static $requiredFields = array('student_first_name',
            'student_last_name',
            'banner',
            'student_phone',
            'student_email',
            'student_gpa',
            'campus',
            'student_level',
            'agency_name',
            'term',
            'department',
            'campus',
            'location');

    public static function display()
    {
        $tpl = array();

        if (isset($_REQUEST['internship_id'])) {
            /* Attempting to edit internship */
            try{
                $i = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
            }catch(InternshipNotFoundException $e){
                NQ::simple('intern', INTERN_ERROR, 'Could not locate an internship with the given ID.');
                return;
            }
            
            $internshipForm = new EditInternshipFormView('Edit Internship', $i);
            $internshipForm->buildInternshipForm();
            $internshipForm->plugInternship();
            
            $tpl['TITLE'] = 'Edit Internship';
            
            $form = $internshipForm->getForm();
            
            /*** 'Generate Contract' Button ***/
            $tpl['PDF'] = \PHPWS_Text::linkAddress('intern', array('action' => 'pdf', 'id' => $i->id));

            /*** Document List ***/
            $docs = $i->getDocuments();
            if (!is_null($docs)) {
                foreach ($docs as $doc) {
                    $tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
                            'DELETE' => $doc->getDeleteLink());
                }
            }
            $folder = new Intern_Folder(Intern_Document::getFolderId());
            $tpl['UPLOAD_DOC'] = $folder->documentUpload($i->id);
            
            $wfState = $i->getWorkflowState();

			if(($wfState instanceof SigAuthReadyState || $wfState instanceof SigAuthApprovedState || $wfState instanceof DeanApprovedState || $wfState instanceof RegisteredState) && ($docs < 1))
			{
	        	NQ::simple('intern', INTERN_WARNING, "No documents have been uploaded yet. Usually a copy of the signed contract document should be uploaded.");
			}
			
            /******************
             * Change History *
            */
            if (!is_null($i->id)) {
                $historyView = new ChangeHistoryView($i);
                $tpl['CHANGE_LOG'] = $historyView->show();
            }
            
            // Show a warning if in SigAuthReadyState, is international, and not OIED approved
            if ($i->getWorkflowState() instanceof SigAuthReadyState && $i->isInternational() && !$i->isOiedCertified()) {
                NQ::simple('intern', INTERN_WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
            }
            
            // Show a warning if in DeanApproved state and is distance_ed campus
            if ($i->getWorkflowState() == 'DeanApprovedState' && $i->isDistanceEd()) {
                NQ::simple('intern', INTERN_WARNING, 'This internship must be registered by Distance Education.');
            }
            
            // Sanity check cource section #
            if ($i->isDistanceEd() && ($i->getCourseSection() < 300 || $i->getCourseSection() > 399)) {
    			NQ::simple('intern', INTERN_WARNING, "This is a distance ed internship, so the course section number should be between 300 and 399.");
            }
        
            // Sanity check distance ed radio
            if (!$i->isDistanceEd() && ($i->getCourseSection() > 300 && $i->getCourseSection() < 400)) {
                NQ::simple('intern', INTERN_WARNING, "The course section number you entered looks like a distance ed course. Be sure to check the Distance Ed option, or double check the section number.");
            }
            
            $emgContactDialog = new EmergencyContactFormView($i);
            
            $tpl['ADD_EMERGENCY_CONTACT'] = '<button type="button" class="btn btn-default btn-sm" id="add-ec-button"><i class="fa fa-plus"></i> Add Contact</button>';
            $tpl['EMERGENCY_CONTACT_DIALOG'] = $emgContactDialog->getHtml(); 
            
        } else {
            // Attempting to create a new internship
            
            // Check permissions
            if(!\Current_User::allow('intern', 'create_internship')){
                NQ::simple('intern', INTERN_ERROR, 'You do not have permission to create new internships.');
                NQ::close();
                \PHPWS_Core::home();
            }
            
            $tpl['TITLE'] = 'Add Internship';
            
            $internshipForm = new InternshipFormView('Add Internship');
            $internshipForm->buildInternshipForm();
            
            $tpl['AUTOFOCUS'] = 'autofocus';
            
            /* Show form with empty fields. */
            $form = $internshipForm->getForm();
            // Show a disabled button in document list if we are adding an internship.
            $tpl['UPLOAD_DOC'] = '<div title="Please save this internship first."><button id="doc-upload-btn" class="btn btn-default btn-sm" title="Please save this internship first." disabled="disabled"><i class="fa fa-upload"></i> Add document</button></div>';

            // Show a disabled emergency contact button
            $tpl['ADD_EMERGENCY_CONTACT'] = '<div title="Please save this internship first."><button class="btn btn-default btn-sm" id="add-ec-button" disabled="disabled" data-toggle="tooltip" title="first tooltip"><i class="fa fa-plus"></i> Add Contact</button></div>';
            
        }
        
        /*
         * If 'missing' is set then we have been redirected
         * back to the form because the user didn't type in something and
         * somehow got past the javascript.
         */
        if (isset($_REQUEST['missing'])) {
            $missing = explode(' ', $_REQUEST['missing']);

            //javascriptMod('intern', 'missing');
            /*
             * Set classes on field we are missing.
            */
            foreach ($missing as $m) {
            	//$form->addCssClass($m, 'has-error');
            	$form->addExtraTag($m, 'data-has-error="true"');
            }
            

            /* Plug old values back into form fields. */
            $form->plugIn($_GET);

            // If internship is being edited...
            if (isset($_REQUEST['internship_id'])) {
                /* Re-add hidden fields with object ID's */
                $i = InternshipFactory::getInternshipById($_GET['internship_id']);
                $a = $i->getAgency();
                //$f = $i->getFacultySupervisor();
                $form->addHidden('agency_id', $a->id);
                //$form->addHidden('supervisor_id', $f->id);
                $form->addHidden('id', $i->id);
            }
        }

        $form->mergeTemplate($tpl);
        
        //test($form->getTemplate(),1);
        
        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'add_internship.tpl');
    }

}

?>
