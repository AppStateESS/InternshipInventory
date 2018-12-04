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
use \Intern\Department;
use \Intern\Faculty;
use \Intern\Term;
use \Intern\InternSettings;

/**
 *  Email to notify a student that their internship has been cancelled.
 *
 * @author jbooker
 * @package Intern
 */
class CancelInternshipNotice extends Email {

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

    protected function getTemplateFileName()
    {
        return 'email/StudentCancellationNotice.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = $this->term->getDescription();

        $dept = new Department($this->internship->department_id);
        $this->tpl['DEPARTMENT'] = $dept->getName();

        // Email the distance ed, graduate school (for grad level), international, or the registrar's office (for undergrad level),
        // Setting is a comma separated array
        if($this->internship->isDistanceEd()){
            $this->to = explode(',', $this->emailSettings->getDistanceEdEmail());
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else if($this->internship->isGraduate()){
            $this->to = explode(',', $this->emailSettings->getGraduateRegEmail());
            $this->tpl['GRADUATE'] = ''; // Dummy template var to use grad school text
        } else if($this->internship->isInternational()){
            $this->to = explode(',', $this->emailSettings->getInternationalRegEmail());
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else {
            $this->to = explode(',', $this->emailSettings->getRegistrarEmail());
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        }

        //CC student
        $this->cc[] = $this->internship->email . $this->emailSettings->getEmailDomain();

        //CC faculty members
        $faculty = $this->internship->getFaculty();
        if ($faculty instanceof Faculty) {
            $this->cc[] = ($faculty->getUsername() . $this->emailSettings->getEmailDomain());
        }

        $this->subject = 'Internship Canceled ' . $this->term->getDescription() . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
