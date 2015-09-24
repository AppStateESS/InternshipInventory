<?php

namespace Intern;

/**
 * View class for generating the emergency contact form.
 *
 * @author jbooker
 * @package intern
 */
class EmergencyContactFormView {

    private $internshipId;
    private $form;

    public function __construct(Internship $i)
    {
        $this->internshipId = $i->getId();

        $this->form = new \PHPWS_Form('emerg_form');
        $this->form->setProtected(false);
    }

    public function getHtml()
    {
        $this->form->addHidden('module', 'intern');
        $this->form->addHidden('action', 'addEmergencyContact');
        $this->form->addHidden('internshipId', $this->internshipId);


        $this->form->addText('emergency_contact_name');
        $this->form->setClass('emergency_contact_name', 'form-text');
        $this->form->setLabel('emergency_contact_name', 'Name');
        $this->form->setClass('emergency_contact_name', 'form-control');

        $this->form->addText('emergency_contact_relation');
        $this->form->setClass('emergency_contact_relation', 'form-text');
        $this->form->setLabel('emergency_contact_relation', 'Relationship');
        $this->form->setClass('emergency_contact_relation', 'form-control');

        $this->form->addText('emergency_contact_phone');
        $this->form->setClass('emergency_contact_phone', 'form-text');
        $this->form->setLabel('emergency_contact_phone', 'Phone');
        $this->form->setClass('emergency_contact_phone', 'form-control');

        return \PHPWS_Template::process($this->form->getTemplate(), 'intern', 'emergencyContactForm.tpl');
    }
}

?>
