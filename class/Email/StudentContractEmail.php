<?php
namespace Intern\Email;

use \Intern\Internship;
use \Intern\InternSettings;
use \Intern\InternshipContractPdfView;

class StudentContractEmail extends Email {

    private $internship;
    private $pdfView;

    /**
     * Sends an email to the student with their contract attached.
     *
     * @param InternSettings $emailSettings
     * @param Internship $internship
     */
    public function __construct(InternSettings $emailSettings, Internship $internship, InternshipContractPdfView $pdfView)
    {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->pdfView = $pdfView;
    }

    protected function getTemplateFileName() {
        return 'email/StudentContract.tpl';
    }

    protected function buildMessage()
    {
        $term = Term::rawToRead($this->internship->getTerm());

        $this->to = $this->internship->getEmail() . $this->emailSettings->getEmailDomain();
        $this->subject = "Internship Contract for $term";

        $this->tpl['TERM'] = $term;
        $this->tpl['AGENCY'] = $this->internship->getAgency()->getName();
    }

    /**
     * Override parent function of same name so that we can handle attaching the Contract
     */
    protected function buildSwiftMessage($to, $fromAddress, $fromName, $subject, $content, $cc = NULL, $bcc = NULL){
        $message = parent::buildSwiftMessage($to, $fromAddress, $fromName, $subject, $content, $cc, $bcc);

        $pdfFile = $this->pdfView->getPdf()->output('internship-contract.pdf', 'S');

        $attachment = \Swift_Attachment::newInstance($pdfFile, $this->internship->getFullName() . ' Internship Contract.pdf');

        $message->attach($attachment);

        return $message;
    }


}
