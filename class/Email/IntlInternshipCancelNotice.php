<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
