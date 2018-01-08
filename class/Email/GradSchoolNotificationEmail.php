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
use \Intern\Subject;
use \Intern\Term;
use \Intern\Faculty;
use \Intern\InternSettings;

/**
 * An email to the graduate school contact, letting them know there's a graduate
 * internship that needs approval.
 *
 * @author jbooker
 * @package Intern
 */
class GradSchoolNotificationEmail extends Email {

    private $internship;
    private $term;

    /**
    * Constructor
    *
    * @param InternSettings $emailSettings
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName()
    {
        return 'email/GradSchoolNotification.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();

        $faculty = $this->internship->getFaculty();

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

        if(isset($this->internship->course_no) && $this->internship->course_no !== ''){
            $this->tpl['COURSE_NUM'] = $this->internship->course_no;
        }else{
            $this->tpl['COURSE_NUM'] = '(No course number provided)';
        }

        if(isset($this->internship->course_sect) && $this->internship->course_sect !== ''){
            $this->tpl['SECTION'] = $this->internship->course_sect;
        }else{
            $this->tpl['SECTION'] = '(not provided)';
        }

        if(isset($this->internship->course_title) && $this->internship->course_title !== ''){
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
            $advisor = $this->internship->getFaculty();
            $this->tpl['FACULTY'] = $advisor->getFullName();
        }else{
            $this->tpl['FACULTY'] = '(not provided)';
        }

        $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();

        $campus = $this->internship->getCampus();
        if($campus == 'distance_ed'){
            $this->tpl['CAMPUS'] = 'Distance Ed';
        }else if($campus == 'main_campus'){
            $this->tpl['CAMPUS'] = 'Main campus';
        }else{
            $this->tpl['CAMPUS'] = $campus;
        }

        if($this->internship->international){
            $this->tpl['COUNTRY'] = $this->internship->loc_country;
            $this->tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        }else{
            $this->tpl['STATE'] = $this->internship->loc_state;
            $this->tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        $this->to = explode(',', $this->emailSettings->getGradSchoolEmail()); // NB: Setting is a comma separated array

        $this->subject = 'Internship Approval Needed: ' . $intlSubject . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
