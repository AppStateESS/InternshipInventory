<?php

namespace Intern;

/*
 * View for interface to Add an Internship
 */
class AddInternshipView implements \View {

    private $terms;
    private $departments;
    private $states;
    private $countries;

    private $previousValues; // Values from a previously submitted version of the form

    public function __construct(Array $terms, Array $departments, Array $states, Array $countries, Array $previousValues)
    {
        $this->terms = $terms;
        $this->departments = $departments;
        $this->states = $states;
        $this->countries = $countries;
        $this->previousValues = $previousValues;
    }

    public function render()
    {
        $tpl = array();

        // Translate departments into proper array format for template row repeat
        foreach ($this->departments as $id => $name) {

            $selected = false;
            if(isset($this->previousValues['department']) && $id == $this->previousValues['department']){
                $selected = true;
            }

            $tpl['DEPARTMENTS'][] = array('DEPT_ID' => $id,
                                          'DEPT_NAME' => $name,
                                          'SELECTED' => $selected ? 'selected' : '');
        }

        // Translate terms into proper array format for template row repeat
        foreach ($this->terms as $term => $text) {
            $selected = false;
            if(isset($this->previousValues['term']) && $term == $this->previousValues['term']){
                $selected = true;
            }

            $tpl['TERMS'][] = array('TERM' => $term,
                                    'TERM_TEXT' => $text,
                                    'SELECTED' => $selected ? 'checked' : '',
                                    'ACTIVE'   => $selected ? 'active' : '');
        }

        // Dynamically generate domestic/international checkboxes so we can set defaults for previously selected values
        $locations = array('domestic' => 'Domestic', 'international' => 'International');
        foreach ($locations as $location => $text) {
            $selected = false;
            if(isset($this->previousValues['location']) && $location == $this->previousValues['location']){
                $selected = true;
            }

            $tpl['LOCATIONS'][] = array('LOCATION' => $location,
                                    'LOCATION_TEXT' => $text,
                                    'SELECTED' => $selected ? 'checked' : '',
                                    'ACTIVE'   => $selected ? 'active' : '');
        }

        // Dynamically generate the list of approved US states
        foreach ($this->states as $abbr => $stateName) {
            $selected = false;
            if(isset($this->previousValues['state']) && $abbr == $this->previousValues['state']) {
                $selected = true;
            }

            $tpl['STATES'][] = array('ABBR' => $abbr,
                                     'STATE_NAME' => $stateName,
                                     'SELECTED' => $selected ? 'selected' : '');
        }

        // Dynamically generate the list of countries
        foreach ($this->countries as $countryCode => $country) {
            $selected = false;
            if(isset($this->previousValues['country']) && $country['id'] == $this->previousValues['country']) {
                $selected = true;
            }

            $tpl['COUNTRIES'][] = array('ABBR' => $countryCode,
                                     'COUNTRY_NAME' => $country,
                                     'SELECTED' => $selected ? 'selected' : '');
        }

        // Set previous data for student id field
        if(isset($this->previousValues['studentId']) && $this->previousValues['studentId'] != ''){
            $tpl['PREV_STUDENTID'] = $this->previousValues['studentId'];
        }

        // Set previous data for agency
        if(isset($this->previousValues['agency']) && $this->previousValues['agency'] != ''){
            $tpl['PREV_AGENCY'] = $this->previousValues['agency'];
        }

        \javascript('jquery');
        \javascriptMod('intern', 'missing');

        return \PHPWS_Template::process($tpl, 'intern', 'addInternship.tpl');
    }

    public function getContentType()
    {
        return 'text/html';
    }
}
?>
