<?php

namespace Intern\Email;
use \Intern\Internship;
use \Intern\CountryFactory;
use \Intern\Term;
use \Intern\InternSettings;

/**
 * Email to the International Ed Office to notify that an international internship
 * has been cancelled.
 *
 * @author jbooker
 * @package Intern
 */
class IntlInternshipCancelNotice extends Email {

    private $internship;
    private $term;

    /**
    * Constructor
    *
    * @param InternSettings $emailSettings
    * @param Term $term
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName(){
        return 'email/OIEDCancellation.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = $this->term->getDescription();

        $countries = CountryFactory::getCountries();
        $this->tpl['COUNTRY'] = $countries[$this->internship->loc_country];

        $this->to = $this->emailSettings->getInternationalOfficeEmail();
        $this->subject = 'International Internship Cancellation';
    }
}
