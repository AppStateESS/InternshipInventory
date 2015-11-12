<script type="text/javascript">
    $(document).ready(function() {
        // Setup date pickers
        $("#internship_start_date").datepicker();
        $("#internship_end_date").datepicker();
    });
</script>

<script type="text/javascript">
    var internship = {INTERNSHIP_JSON};
</script>

<h1>
  <i class="fa fa-edit"></i> {TITLE}
</h1>

<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}"{FORM_ENCODE}>
  {HIDDEN_FIELDS}

  <div class="form-group">
    <div class="col-lg-1 col-lg-offset-8">
      <button type="submit" class="btn btn-primary" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
    </div>
    <div class="col-lg-1 col-lg-offset-1">
      <!-- BEGIN generate_contract -->
      <a href="{PDF}" id="contract-button" class="btn btn-default"><i class="fa fa-file"></i> Generate Contract</a>
      <!-- END generate_contract -->
    </div>
  </div>

  <div class="row">
    <!-- Left column -->
    <div class="col-lg-6">
      <!-- Student info section -->
      <fieldset>
        <legend>Student</legend>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="bannerid}">Banner Id</label>
          <div id="bannerid" class="col-lg-6"><p class="form-control-static">{BANNER}</p></div>
        </div>

        <div class="form-group required">
          <label class="col-lg-3 control-label" for="{STUDENT_FIRST_NAME_ID}">{STUDENT_FIRST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_FIRST_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_MIDDLE_NAME_ID}">{STUDENT_MIDDLE_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_MIDDLE_NAME}</div>
        </div>

        <div class="form-group required">
          <label class="col-lg-3 control-label" for="{STUDENT_LAST_NAME_ID}">{STUDENT_LAST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_LAST_NAME}</div>
        </div>

        <div class="form-group required">
          <label class="col-lg-3 control-label" for="{STUDENT_EMAIL_ID}">{STUDENT_EMAIL_LABEL_TEXT}</label>
          <div class="col-lg-6">
            <div class="input-group">
              {STUDENT_EMAIL}<span class="input-group-addon">@appstate.edu</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="birthdate">Birth date</label>
          <div id="birthdate" class="col-lg-6"><p class="form-control-static">{BIRTH_DATE}</p></div>
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
          <label class="col-lg-3 control-label" for="{STUDENT_GPA_ID}">GPA</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_GPA}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="campus">Campus</label>
          <div id="campus" class="col-lg-6"><p class="form-control-static">{CAMPUS}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="level">Level</label>
          <div id="level" class="col-lg-6"><p class="form-control-static">{LEVEL}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{UGRAD_MAJOR_ID}{GRAD_MAJOR_ID}">Major / Program</label>

          <!-- BEGIN oneMajor -->
          <div class="col-lg-8"><p class="form-control-static">{MAJOR}</p></div>
          <!-- END oneMajor -->

          <div class="col-lg-8">
            <div class="btn-group-vertical" data-toggle="buttons" role="group" aria-label="major selector">
              <!-- BEGIN majors_repeat -->
              <label class="btn btn-default {ACTIVE}">
                <input type="radio" name="major_code" autocomplete="off" value="{CODE}" {CHECKED}> {DESC}
              </label>
              <!-- END majors_repeat -->
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="gradDate">Graduation Date</label>
          <div id="gradDate" class="col-lg-6"><p class="form-control-static">{GRAD_DATE}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="credit-hours">Credit Hours</label>
          <div id="credit-hours" class="col-lg-6"><p class="form-control-static">{ENROLLED_CREDIT_HORUS}</p></div>
        </div>

      </fieldset>

      <!-- Emergency Contact Info -->
      <fieldset>
        <legend>Emergency Contacts</legend>
        <div class="row">
          <div class="col-lg-9">
            <ul id="emergency-contact-list" class="list-group">
            </ul>
            <div id="emergency-spinner" style="margin-top: 15px;"></div>
          </div>
          <div class="col-lg-3">{ADD_EMERGENCY_CONTACT}</div>

        </div>
      </fieldset>

      <fieldset>
        <legend>Location</legend>
        <p>
          <span class="help-block">Physical Location of Internship</span>
        </p>

        <div class="form-group">
          <div class="col-lg-3 control-label">
            <label>Location</label>
          </div>
          <div class="col-lg-6">
            <p class="form-control-static">{LOCATION}</p>
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

      <!-- BEGIN loc_state -->
      <div class="form-group">
        <div class="col-lg-3 control-label">
          <label>State</label>
        </div>
        <div class="col-lg-6"><p class="form-control-static">{LOC_STATE}</p></div>
      </div>
      <!-- END loc_state -->

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_ZIP_ID}" id="internship_loc_zip-label">{LOC_ZIP_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_ZIP}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{LOC_PROVINCE_ID}">{LOC_PROVINCE_LABEL_TEXT}</label>
        <div class="col-lg-6">{LOC_PROVINCE}</div>
      </div>

      <!-- BEGIN loc_country -->
      <div class="form-group">
        <div class="col-lg-3 control-label">
          <label>Country</label>
        </div>
        <div class="col-lg-6"><p class="form-control-static">{LOC_COUNTRY}</p></div>
      </div>
      <!-- END loc_country -->

      <h4>Term Information</h4>
      <div class="form-group">
        <div class="col-lg-3 control-label">
            <label>Term</label>
          </div>
          <div class="col-lg-6">
            <p class="form-control-static">{TERM}</p>
          </div>
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

        <div class="col-lg-8 col-lg-offset-3">
          <div class="checkbox">
            <label id="secondar-part-label">{SECONDARY_PART}&nbsp;{SECONDARY_PART_LABEL_TEXT}</label>
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
        <div class="col-lg-6">
          {CREDITS} <span class="help-block"><small class="text-muted">Decimal values will be rounded.</small></span>
        </div>
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
        <legend>Type</legend>
        <div class="form-group">
          <div class="col-lg-5 col-lg-offset-3">
            <!-- BEGIN experience_type_repeat -->
            <label class="radio"> {EXPERIENCE_TYPE} {EXPERIENCE_TYPE_LABEL} </label>
            <!-- END experience_type_repeat -->
          </div>
          <div class="col-lg-4">
            <a id="internship-type-help-button" class="pull-right"><i class="fa fa-question-circle"></i> Type Definitions</a>
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
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">Next status</h4>
          </div>
          <div class="panel-body">

            <!-- BEGIN workflow_action_repeat -->
            <div class="radio">
              <label>{WORKFLOW_ACTION} {WORKFLOW_ACTION_LABEL}</label>
            </div>
            <!-- END workflow_action_repeat -->
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
          <div class="form-group required">
            <label class="col-lg-3 control-label" for="{DEPARTMENT_ID}">{DEPARTMENT_LABEL_TEXT}</label>
            <div class="col-lg-8">{DEPARTMENT}</div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" for="{FACULTY_ID}">{FACULTY_LABEL_TEXT}</label>
            <div class="col-lg-8">{FACULTY}</div>
          </div>
        </div>
        <div id="faculty_details">

          <div class="row">
            <div id="faculty_change" class="col-lg-2">
              <button id="faculty-change" type="button" class="btn btn-default btn-xs">
                <i class="fa fa-chevron-left"></i> change
              </button>
            </div>
            <div id="faculty_name" class="col-lg-10 lead"></div>
          </div>

          <div class="row">
            <div class="col-lg-5 col-lg-offset-2">

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Email address"><i class="fa fa-envelope"></i></abbr> &nbsp;<span id="faculty_email"></span>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Phone"><i class="fa fa-phone"></i></abbr> &nbsp;<span id="faculty_phone"></span>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Fax"><i class="fa fa-print"></i></abbr> &nbsp;<span id="faculty_fax"></span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <abbr title="Address"><i class="fa fa-map-marker"></i></abbr> &nbsp;
              <address id="faculty_address"></address>
            </div>
          </div>

        </div>
      </fieldset>

      <!-- Document List -->
      <fieldset>
        <legend>Contract &amp; Documents</legend>
        <div class="row">
          <div class="col-lg-9">
            <ul class="list-group">
              <!-- BEGIN docs -->
              <li class="list-group-item"><i class="fa fa-file"></i> {DOWNLOAD} &nbsp;{DELETE}</li>
              <!-- END docs -->
            </ul>
          </div>
          <div class="col-lg-2">{UPLOAD_DOC}</div>
        </div>
      </fieldset>

      <!-- Agency info -->
      <fieldset>
        <legend>Agency Details</legend>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_GPA_ID}">Agency Name</label>
          <div class="col-lg-6"><p class="form-control-static">{AGENCY_NAME}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_PHONE_ID}">{AGENCY_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_PHONE}</div>
        </div>

        <div class="checkbox">
          <label> {COPY_ADDRESS_AGENCY} {COPY_ADDRESS_AGENCY_LABEL_TEXT} </label>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_ADDRESS_ID}">{AGENCY_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_ADDRESS}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_CITY_ID}">{AGENCY_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_CITY}</div>
        </div>

        <!-- BEGIN agency-state -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_STATE_ID}">{AGENCY_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_STATE}</div>
        </div>
        <!-- END agency-state -->

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_ZIP_ID}" id="internship_agency_zip-label">{AGENCY_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_ZIP}</div>
        </div>

        <!-- BEGIN agency-intl -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_PROVINCE_ID}">{AGENCY_PROVINCE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_PROVINCE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_COUNTRY_ID}">{AGENCY_COUNTRY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_COUNTRY}</div>
        </div>
        <!-- END agency-intl -->

      </fieldset>

      <fieldset>
        <legend>Supervisor Info</legend>

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
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_EMAIL_ID}">{AGENCY_SUP_EMAIL_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_EMAIL}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_FAX_ID}">{AGENCY_SUP_FAX_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_FAX}</div>
        </div>

        <div class="checkbox">
          <label> {COPY_ADDRESS} {COPY_ADDRESS_LABEL_TEXT} </label>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_PHONE_ID}">{AGENCY_SUP_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_PHONE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_ADDRESS_ID}">{AGENCY_SUP_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_ADDRESS}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_CITY_ID}">{AGENCY_SUP_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_CITY}</div>
        </div>

        <!-- BEGIN agency sup-state -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_STATE_ID}">{AGENCY_SUP_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_STATE}</div>
        </div>
        <!-- END agency sup-state -->

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_ZIP_ID}" id="internship_agency_sup_zip-label">{AGENCY_SUP_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_ZIP}</div>
        </div>

        <!-- BEGIN agency-sup-intl -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_PROVINCE_ID}">{AGENCY_SUP_PROVINCE_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_PROVINCE}</div>
        </div>
        <!-- END agency-sup-intl -->

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{AGENCY_SUP_COUNTRY_ID}">{AGENCY_SUP_COUNTRY_LABEL_TEXT}</label>
          <div class="col-lg-6">{AGENCY_SUP_COUNTRY}</div>
        </div>
      </fieldset>

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

      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
      </div>
    </div>
    <!-- End of right column -->
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="form-group">
        <label for="{NOTES_ID}">Add a note</label> {NOTES}
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="form-group">{CHANGE_LOG}</div>
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
