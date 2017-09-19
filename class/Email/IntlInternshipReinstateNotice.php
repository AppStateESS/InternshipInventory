<?php
namespace Intern\Email;

use \Intern\Internship;
use \Intern\CountryFactory;
use \Intern\Term;
use \Intern\InternSettings;

/**
 * Email to the International Ed Office to notify that an international internship
 * has been reinstated.
 *
 * @author jbooker
 * @package Intern
 */
class IntlInternshipReinstateNotice extends Email {

    private $internship;
    private $term;

    /**
    *  Sends the  reinstate notification email to OIED.
    *
    * @param InternSettings $emailSettings
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName() {
        return 'email/OIEDReinstate.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = $this->term->getDescription();

        $countries = \Intern\CountryFactory::getCountries();
        $this->tpl['COUNTRY'] = $countries[$this->internship->loc_country];

        $this->to = $this->emailSettings->getInternationalOfficeEmail();
        $this->subject = 'International Internship Reinstated';
    }
}
