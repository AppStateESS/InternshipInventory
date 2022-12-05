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
use \Intern\Faculty;
use \Intern\Subject;
use \Intern\InternSettings;
use \Intern\Term;

class RegistrationIssueEmail extends Email {

    private $internship;
    private $term;
    private $note;

    /**
    *  Sends the 'Registration Issue' notification email.
    *
    * @param InternSettings $emailSettings
    * @param Internship $internship
    * @param string $note
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term, $note) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
        $this->note = $note;
    }

    protected function getTemplateFileName(){
        return 'email/RegistrationIssue.tpl';
    }

    protected function buildMessage()
    {
        $this->subject = 'Internship Enrollment Issue';
        $this->to[] = $this->internship->email . $this->emailSettings->getEmailDomain();

        $subjects = Subject::getSubjects();
        $faculty = $this->internship->getFaculty();

        if($this->internship->isUndergraduate()){
            $this->tpl['REG_NAME'] = "Registrar's Office";
            $this->tpl['REG_NUM'] = "Registrar's Office Service Desk at (828-262-2050)";
        } else{
            $this->tpl['REG_NAME'] = 'Graduate School';
            $this->tpl['REG_NUM'] = 'Graduate School at (828-262-2130)';
        }

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['USER'] = $this->internship->email;
        $this->tpl['PHONE'] = $this->internship->phone;
        $this->tpl['TERM'] = $this->term->getDescription();

        if(isset($this->internship->course_subj)){
            $this->tpl['SUBJECT'] = $subjects[$this->internship->course_subj];
        }else{
            $this->tpl['SUBJECT'] = '(No course subject provided)';
        }

        $this->tpl['COURSE_NUM'] = $this->internship->course_no;
        if(isset($this->internship->course_sect)){
            $this->tpl['SECTION'] = $this->internship->course_sect;
        }else{
            $this->tpl['SECTION'] = '(not provided)';
        }

        if(isset($this->internship->course_title)){
            $this->tpl['COURSE_TITLE'] = $this->internship->course_title;
        }

        if(isset($this->internship->credits)){
            $this->tpl['CREDITS'] = $this->internship->credits;
        }else{
            $this->tpl['CREDITS'] = '(not provided)';
        }

        $startDate = $this->internship->getStartDate(true);
        if(isset($startDate)){
            $this->tpl['START_DATE'] = $startDate;
        }else{
            $this->tpl['START_DATE'] = '(not provided)';
        }

        $endDate = $this->internship->getEndDate(true);
        if(isset($endDate)){
            $this->tpl['END_DATE'] = $endDate;
        }else{
            $this->tpl['END_DATE'] = '(not provided)';
        }

        if($faculty instanceof Faculty){
            $this->tpl['FACULTY'] = $faculty->getFullName();

            // CC the Faculty member
            $this->cc[] = $faculty->getUsername() . $this->emailSettings->getEmailDomain();
        }else{
            $this->tpl['FACULTY'] = '(not provided)';
        }

        $department = $this->internship->getDepartment();
        $this->tpl['DEPT'] = $department->getName();

        if($this->internship->international){
            $this->tpl['COUNTRY'] = $this->internship->loc_country;
            $this->tpl['INTERNATIONAL'] = 'Yes';
        }else{
            $this->tpl['STATE'] = $this->internship->loc_state;
            $this->tpl['INTERNATIONAL'] = 'No';
        }

        $this->tpl['NOTE'] = $this->note;
    }
}
