<script type="text/javascript">
    $(document).ready(function() {
        // Setup date pickers
        $("#internship_start_date").datepicker();
        $("#internship_end_date").datepicker();
    });
</script>

<h1 class="add-icon">{TITLE}</h1>

<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}"{FORM_ENCODE}>
  {HIDDEN_FIELDS}

  <!-- BEGIN generate_contract -->
  <div class="controls pull-right">
    <a href="{PDF}" id="contract-button" class="btn"><i class="icon-file"></i> Generate Contract</a>
  </div>
  <!-- END generate_contract -->

  <button type="submit" class="btn btn-primary" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>

  <div class="row">
    <!-- Left column -->
    <div class="col-lg-6">
      <!-- Student info section -->
      <fieldset>
        <legend>Student</legend>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{BANNER_ID}">{BANNER_LABEL_TEXT}</label>
          <div class="col-lg-6">{BANNER}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_FIRST_NAME_ID}">{STUDENT_FIRST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_FIRST_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_MIDDLE_NAME_ID}">{STUDENT_MIDDLE_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_MIDDLE_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_LAST_NAME_ID}">{STUDENT_LAST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_LAST_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_EMAIL_ID}">{STUDENT_EMAIL_LABEL_TEXT}</label>
          <div class="col-lg-6 input-group">
            {STUDENT_EMAIL}<span class="input-group-addon">@appstate.edu</span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_ADDRESS_ID}">{STUDENT_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_ADDRESS}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_CITY_ID}">{STUDENT_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_CITY}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_STATE_ID}">{STUDENT_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_STATE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_ZIP_ID}">{STUDENT_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_ZIP}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_PHONE_ID}">{STUDENT_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_PHONE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_GPA_ID}">{STUDENT_GPA_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_GPA}</div>
        </div>

        <div class="form-group">
          <div class="col-lg-6 col-lg-offset-3">
            <!-- BEGIN campus_repeat -->
            <label class="radio-inline"> {CAMPUS} {CAMPUS_LABEL_TEXT} </label>
            <!-- END campus_repeat -->
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_LEVEL_ID}">{STUDENT_LEVEL_LABEL_TEXT}</label>
          <div class="col-lg-4">{STUDENT_LEVEL}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_MAJOR_ID}">{STUDENT_MAJOR_LABEL_TEXT}</label>
          <div class="col-lg-8">{STUDENT_MAJOR} {UGRAD_MAJOR} {GRAD_PROG}</div>
        </div>

      </fieldset>

      <!-- Emergency Contact Info -->
      <fieldset>
        <legend>Emergency Contacts</legend>
        {ADD_EMERGENCY_CONTACT}
        <div id="emergency-contact-list-container" style="width: 325px">
          <ul id="emergency-contact-list">
          </ul>
        </div>
        <div id="emergency-spinner" style="margin-top: 15px;"></div>
      </fieldset>

      <fieldset>
        <legend>Location</legend>
        <p>
          <span class="help-block">Physical Location of Internship</span>
        </p>

        <div class="form-group">
          <div class="col-lg-6 col-lg-offset-3">
            <!-- BEGIN location_repeat -->
            <label class="radio-inline"> {LOCATION}{LOCATION_LABEL_TEXT} </label>
            <!-- END location_repeat -->
          </div>
        </div>

      </fieldset>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_ADDRESS_ID}">{LOC_ADDRESS_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_ADDRESS}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_CITY_ID}">{LOC_CITY_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_CITY}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_STATE_ID}">{LOC_STATE_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_STATE}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_ZIP_ID}">{LOC_ZIP_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_ZIP}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_PROVINCE_ID}">{LOC_PROVINCE_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_PROVINCE}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="LOC_COUNTRY_ID}">{LOC_COUNTRY_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_COUNTRY}</div>
      </div>

      <h4>Term Information</h4>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="{TERM_ID}">{TERM_LABEL_TEXT}</label>
        <div class="col-lg-6">{TERM}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{START_DATE_ID}">{START_DATE_LABEL_TEXT}</label>
        <div class="col-lg-6">{START_DATE}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{END_DATE_ID}">{END_DATE_LABEL_TEXT}</label>
        <div class="col-lg-6">{END_DATE}</div>
      </div>

      <h4>Course Information</h4>

      <div class="form-group">
        <div class="col-lg-8 col-lg-offset-3">
          <div class="checkbox">
            <label>{MULTIPART}&nbsp;{MULTIPART_LABEL_TEXT}</label>
          </div>
        </div>

        <div class="col-lg-8 col-lg-offset-4">
          <div class="checkbox">
            <label>{SECONDARY_PART}&nbsp;{SECONDARY_PART_LABEL_TEXT}</label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_SUBJ_ID}">{COURSE_SUBJ_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_SUBJ}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_NO_ID}">{COURSE_NO_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_NO}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_SECT_ID}">{COURSE_SECT_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_SECT}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">{CREDITS_LABEL_TEXT}</label>
        <div class="col-lg-6">{CREDITS}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">{COURSE_TITLE_LABEL_TEXT}</label>
        <div class="col-lg-6">
          {COURSE_TITLE} <span class="help-block"><small class="text-muted">(Limit 28 characters; Banner)</small></span>
        </div>
      </div>

      <!-- BEGIN corequisite -->
      <h4>Corequisite Course</h4>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">Course Number</label>
        <div class="col-lg-6">{COREQUISITE_COURSE_NUM}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">Course Section</label>
        <div class="col-lg-6">{COREQUISITE_COURSE_SECT}</div>
      </div>
      <!-- END corequisite -->

      <fieldset>
        <legend>Compensation</legend>

        <div class="form-group">
          <div class="col-lg-6 col-lg-offset-3">
            <!-- BEGIN payment_repeat -->
            <label class="radio-inline"> {PAYMENT}{PAYMENT_LABEL_TEXT}</label>
            <!-- END payment_repeat -->

            <div class="checkbox">
              <label>{STIPEND}&nbsp;{STIPEND_LABEL_TEXT}</label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{PAY_RATE_ID}">Pay rate</label>
          <div class="col-lg-3">{PAY_RATE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 col-lg-offset-1control-label" for="{AVG_HOURS_WEEK_ID}">{AVG_HOURS_WEEK_LABEL_TEXT}</label>
          <div class="col-lg-3">{AVG_HOURS_WEEK}</div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Type</legend>
        <div class="form-group">
          <div class="col-lg-6 col-lg-offset-3">
            <!-- BEGIN experience_type_repeat -->
            <label class="radio"> {EXPERIENCE_TYPE} {EXPERIENCE_TYPE_LABEL} </label>
            <!-- END experience_type_repeat -->
          </div>
          <div class="col-lg-3">
            <a id="internship-type-help-button" class="pull-right" style="cursor: pointer;"><i class="icon-question-sign" style="text-decoration: none;"></i> Type Definitions</a>
          </div>
        </div>
      </fieldset>

    </div>
    <!-- End of left column -->


    <!-- Right Column -->
    <div class="col-lg-6">
      <!-- Status -->
      <fieldset>
        <legend>Status</legend>
        <p>
          Current Status: <strong>{WORKFLOW_STATE}</strong>
        </p>
        <p>Next status</p>
        <div class="form-group">
          <div class="col-lg-10">
            <div class="well">
              <!-- BEGIN workflow_state_repeat -->
              <div class="radio">
                <label>{WORKFLOW_ACTION} {WORKFLOW_ACTION_LABEL}</label>
              </div>
              <!-- END workflow_state_repeat -->
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10">
            <div class="checkbox">
              <label>{OIED_CERTIFIED} {OIED_CERTIFIED_LABEL}</label>
            </div>
          </div>
        </div>
      </fieldset>

      <!-- Faculty info -->
      <fieldset>
        <legend>Faculty Advisor</legend>
        <div id="faculty_selector">
          <div class="form-group">
            <label class="col-lg-3 control-label" for="{DEPARTMENT_ID}">{DEPARTMENT_LABEL_TEXT}</label>
            <div class="col-lg-8">{DEPARTMENT}</div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" for="{FACULTY_ID}">{FACULTY_LABEL_TEXT}</label>
            <div class="col-lg-8">{FACULTY}</div>
          </div>
        </div>
        <div id="faculty_details" style="margin-top: 1em;">
          <div id="faculty_change" style="float: right;">
            <a id="faculty-change" style="cursor: pointer">change</a>
          </div>
          <div id="faculty_name" style="font-size: 1.5em;"></div>
          <div id="faculty_email" style="margin-top: 1em;"></div>
          <div style="float: right; margin-right: 3em; margin-top: 1em;">
            Fax: <span id="faculty_fax" style="margin-top: 1em;"></span>
          </div>
          <div style="margin-top: 1em;">
            Phone: <span id="faculty_phone" style="margin-top: 1em;"></span><br />
          </div>
          <div style="margin-top: 1em;">
            Address:
            <div id="faculty_address"></div>
          </div>
        </div>
      </fieldset>

      <!-- Document List -->
      <fieldset>
        <legend>Documents</legend>
        <div class="pull-right">{UPLOAD_DOC}</div>
        <ul>
          <!-- BEGIN docs -->
          <li>{DOWNLOAD}{DELETE}</li>
          <!-- END docs -->
        </ul>
      </fieldset>

      <!-- Agency info -->
      <fieldset>
        <legend>Agency Details</legend>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_NAME_ID}">{AGENCY_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_NAME}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_PHONE_ID}">{AGENCY_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_PHONE}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_ADDRESS_ID}">{AGENCY_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_ADDRESS}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_CITY_ID}">{AGENCY_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_CITY}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_STATE_ID}">{AGENCY_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_STATE}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_ZIP_ID}">{AGENCY_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_ZIP}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_COUNTRY_ID}">{AGENCY_COUNTRY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_COUNTRY}</div>
        </div>
        
      </fieldset>
      
      <fieldset>
        <legend>Supervisor Info</legend>
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP__ID}">{AGENCY_SUP__LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_FIRST_NAME_ID}">{AGENCY_SUP_FIRST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_FIRST_NAME}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_LAST_NAME_ID}">{AGENCY_SUP_LAST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_LAST_NAME}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_TITLE_ID}">{AGENCY_SUP_TITLE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_TITLE}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_PHONE_ID}">{AGENCY_SUP_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_PHONE}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_EMAIL_ID}">{AGENCY_SUP_EMAIL_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_EMAIL}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP__ID}">{AGENCY_SUP__LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_FAX_ID}">{AGENCY_SUP_FAX_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_FAX}</div>
        </div>
        
        {COPY_ADDRESS}{COPY_ADDRESS_LABEL}
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_ADDRESS_ID}">{AGENCY_SUP_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_ADDRESS}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_CITY_ID}">{AGENCY_SUP_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_CITY}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_STATE_ID}">{AGENCY_SUP_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_STATE}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_ZIP_ID}">{AGENCY_SUP_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_ZIP}</div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_COUNTRY_ID}">{AGENCY_SUP_COUNTRY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_COUNTRY_ZIP}</div>
        </div>
        
      </fieldset>

    </div>
    <!-- End of right column -->
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="form-group">{CHANGE_LOG}</div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="form-group">
        <label for="{NOTES_ID}">Add a note</label> {NOTES}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-2">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
      </div>
    </div>
  </div>

  {END_FORM} {EMERGENCY_CONTACT_DIALOG}

  <div id="internship-type-help">
    <h2>Internship Type Definitions</h2>
    <h3>Student Teaching</h3>
    <p>A course requiring students to instruct or teach at an entity external to the institution, generally as part of the culminating curriculum of a teacher education or certificate program.</p>

    <h3>Practicum</h3>
    <p>A course requiring students to participate in an approved project or proposal that practically applies previously studied theory of the field or discipline under the supervision of an expert or qualified representative of the field or discipline.</p>

    <h3>Clinical</h3>
    <p>A course requiring medical- or healthcare-focused experiential work where students test, observe, experiment, or practice a field or discipline in a hands-on or simulated environment.</p>

    <h3>Internship</h3>
    <p>A course requiring students to participate in a partnership, professional employment, work experience or cooperative education with any entity external to the institution, generally under the supervision of an employee of the external entity.</p>
  </div>