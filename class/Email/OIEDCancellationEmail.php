<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\CountryFactory;
use Intern\Term;

class OIEDCancellationEmail extends Email {

    private $internship;

    /**
    * Cancelation email for OIED.
    *
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName(){
        return 'email/OIEDCancellation.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $i->getFullName();
        $this->tpl['BANNER'] = $i->banner;
        $this->tpl['TERM'] = Term::rawToRead($i->term, false);

        $countries = CountryFactory::getCountries();
        $this->tpl['COUNTRY'] = $countries[$i->loc_country];

        $this->to = $this->emailSettings->getInternationalOfficeEmail();
        $this->subject = 'International Internship Cancellation';
    }
}
