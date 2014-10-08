<?php

namespace Intern\UI;

use Intern\InternshipFormView;
use Intern\InternshipFactory;
use Intern\AgencyFactory;
use Intern\EditInternshipFormView;
use Intern\InternFolder;
use Intern\InternDocument;
use Intern\ChangeHistoryView;
use Intern\EmergencyContactFormView;

use \PHPWS_DB;

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

        // TODO: Make sure an 'internship_id' key is set on the request
        if(!isset($_REQUEST['internship_id'])) {
            \NQ::simple('intern', NotifyUI::ERROR, 'No internship ID was given.');
            \NQ::close();
            \PHPWS_Core::reroute('index.php');
        }

        /* Attempting to edit internship */
        try{
            $i = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        }catch(InternshipNotFoundException $e){
            \NQ::simple('intern', NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            return;
        }

        // Get the agency
        $agency = AgencyFactory::getAgencyById($i->getAgencyId());

        $internshipForm = new EditInternshipFormView('Edit Internship', $i, $agency);
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
        $folder = new InternFolder(InternDocument::getFolderId());
        $tpl['UPLOAD_DOC'] = $folder->documentUpload($i->id);

        $wfState = $i->getWorkflowState();

		if(($wfState instanceof SigAuthReadyState || $wfState instanceof SigAuthApprovedState || $wfState instanceof DeanApprovedState || $wfState instanceof RegisteredState) && ($docs < 1))
		{
        	\NQ::simple('intern', NotifyUI::WARNING, "No documents have been uploaded yet. Usually a copy of the signed contract document should be uploaded.");
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
            \NQ::simple('intern', NotifyUI::WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
        }

        // Show a warning if in DeanApproved state and is distance_ed campus
        if ($i->getWorkflowState() == 'DeanApprovedState' && $i->isDistanceEd()) {
            \NQ::simple('intern', NotifyUI::WARNING, 'This internship must be registered by Distance Education.');
        }

        // Sanity check cource section #
        if ($i->isDistanceEd() && ($i->getCourseSection() < 300 || $i->getCourseSection() > 399)) {
			NQ::simple('intern', NotifyUI::WARNING, "This is a distance ed internship, so the course section number should be between 300 and 399.");
        }

        // Sanity check distance ed radio
        if (!$i->isDistanceEd() && ($i->getCourseSection() > 300 && $i->getCourseSection() < 400)) {
            \NQ::simple('intern', NotifyUI::WARNING, "The course section number you entered looks like a distance ed course. Be sure to check the Distance Ed option, or double check the section number.");
        }

        $emgContactDialog = new EmergencyContactFormView($i);

        $tpl['ADD_EMERGENCY_CONTACT'] = '<button type="button" class="btn btn-default btn-sm" id="add-ec-button"><i class="fa fa-plus"></i> Add Contact</button>';
        $tpl['EMERGENCY_CONTACT_DIALOG'] = $emgContactDialog->getHtml();

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

            /* Re-add hidden fields with object ID's */
            $i = InternshipFactory::getInternshipById($_GET['internship_id']);
            $a = $i->getAgency();
            //$f = $i->getFacultySupervisor();
            $form->addHidden('agency_id', $a->id);
            //$form->addHidden('supervisor_id', $f->id);
            $form->addHidden('id', $i->id);
        }

        $form->mergeTemplate($tpl);

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_internship.tpl');
    }

}

?>
