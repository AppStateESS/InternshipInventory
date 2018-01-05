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
use \Intern\Term;
use \Intern\InternSettings;

/**
 * Email notification that an internship has be certified by the international
 * Education Office. Generally sent to the faculty instructor, if one is available
 *
 * @author jbooker
 * @package Intern
 */
class OIEDCertifiedEmail extends Email{

    private $internship;
    private $agency;
    private $term;

    /**
    * @param InternSettings $emailSettings
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
        $this->agency = $internship->getAgency();
    }

    protected function getTemplateFileName() {
        return 'email/OiedCertifiedNotice.tpl';
    }

    protected function buildMessage()
    {
        $faculty = $this->internship->getFaculty();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->getBannerId();
        $this->tpl['TERM'] = $this->term->getDescription();
        $this->tpl['FACULTY'] = $faculty->getFullName();
        $this->tpl['AGENCY'] = $this->agency->getName();

        $this->to = $faculty->getUsername() . $this->emailSettings->getEmailDomain();

        $this->subject = 'OIED Certified Internship';
    }
}
