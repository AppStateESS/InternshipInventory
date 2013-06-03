<script type="text/javascript">
	$(document).ready(function() {
		// Setup date pickers
		$("#internship_start_date").datepicker();
		$("#internship_end_date").datepicker();
	});
</script>

<h1 class="add-icon">{TITLE}</h1>
<!-- BEGIN generate_contract -->
<div id="pdf-download"><a href="{PDF}" id="contract-button" class="button"><span class="tango16 tango-document-new">Generate Contract</span></a></div>
<!-- END generate_contract -->

{START_FORM} {SUBMIT}

<table id="internship-form">
  <tr>
    <!-- Student info section -->
    <td class="sub-form-cell"><span class="info-header">Student</span>
      <table class="sub-form" id="student-info">
        <tr>
          <td>{STUDENT_FIRST_NAME_LABEL}<span class="required-input">*</span></td>
          <td>{STUDENT_FIRST_NAME}</td>
        </tr>
        <tr>
          <td>{STUDENT_MIDDLE_NAME_LABEL}</td>
          <td>{STUDENT_MIDDLE_NAME}</td>
        </tr>
        <tr>
          <td>{STUDENT_LAST_NAME_LABEL}<span class="required-input">*</span></td>
          <td>{STUDENT_LAST_NAME}</td>
        </tr>
        <tr>
          <td>{BANNER_LABEL}<span class="required-input">*</span></td>
          <td>{BANNER}</td>
        </tr>
        <tr>
          <td>{STUDENT_EMAIL_LABEL}<span class="required-input">*</span></td>
          <td>{STUDENT_EMAIL}@appstate.edu</td>
        </tr>
        <tr>
          <td>{STUDENT_ADDRESS_LABEL}</td>
          <td>{STUDENT_ADDRESS}</td>
        </tr>
        <tr>
          <td>{STUDENT_CITY_LABEL}</td>
          <td>{STUDENT_CITY}</td>
        </tr>
        <tr>
          <td>{STUDENT_STATE_LABEL}</td>
          <td>{STUDENT_STATE}</td>
        </tr>
        <tr>
          <td>{STUDENT_ZIP_LABEL}</td>
          <td>{STUDENT_ZIP}</td>
        </tr>
        <tr>
          <td>{STUDENT_PHONE_LABEL}<span class="required-input">*</span></td>
          <td>{STUDENT_PHONE}</td>
        </tr>
        <tr>
          <td>{STUDENT_GPA_LABEL}<span class="required-input">*</span></td>
          <td>{STUDENT_GPA}</td>
        <tr><tr>
          <td>Campus</td>
          <td>
            <!-- BEGIN campus_repeat -->
            {CAMPUS}{CAMPUS_LABEL}<br />
            <!-- END campus_repeat -->
          </td>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Major<span class="required-input">*</span></legend>
              <!-- BEGIN student_level_repeat -->
              {STUDENT_LEVEL}{STUDENT_LEVEL_LABEL}
              <!--  END student_level_repeat -->
              <br />
              <span id="ugrad_drop"> {UGRAD_MAJOR_LABEL}<span class="required-input">*</span><br />{UGRAD_MAJOR} </span>
              <span id="grad_drop"> {GRAD_PROG_LABEL}<span class="required-input">*</span><br /> {GRAD_PROG} </span>
            </fieldset></td>
        </tr>
      </table></td>
    <!-- End student info section -->
    <td class="sub-form-cell">
      <span class="info-header">Status</span>
      <table class="sub-form">
        <tr>
          <td>
            Current Status: <strong>{WORKFLOW_STATE}</strong>
            <div class="status-box-content">
              <fieldset>
                <legend>Action</legend>
                <!-- BEGIN workflow_action_repeat -->
                {WORKFLOW_ACTION}{WORKFLOW_ACTION_LABEL}<br />
                <!-- END workflow_action_repeat -->
              </fieldset>
            </div>
            {OIED_CERTIFIED}{OIED_CERTIFIED_LABEL}
          </td>
        </tr>
      </table>

      <!-- Faculty info -->
      <span class="info-header">Faculty</span>
      <table class="sub-form" id="faculty-info">
        <tr>
          <td colspan=2>
            <div id="faculty_selector">
              <div>
                {DEPARTMENT_LABEL}
                <span class="required-input">*</span><br /> {DEPARTMENT}
              </div>
              <div style="margin-top:1em;">
                {FACULTY_LABEL}<br />
                {FACULTY}
              </div>
            </div>
            <div id="faculty_details" style="margin-top:1em;">
              <div id="faculty_change" style="float:right;"><a id="faculty-change" style="cursor:pointer">change</a></div>
              <div id="faculty_name" style="font-size:1.5em;"></div>
              <div id="faculty_email"  style="margin-top:1em;"></div>
              <div style="float:right;margin-right:3em;margin-top:1em;">
                Fax: <span id="faculty_fax" style="margin-top:1em;"></span>
              </div>
              <div style="margin-top:1em;">
                Phone: <span id="faculty_phone" style="margin-top:1em;"></span><br />
              </div>
              <div  style="margin-top:1em;">
                Address: <div id="faculty_address"></div>
              </div>
            </div>
          </td>
        </tr>
      </table>
      <!-- End faculty info -->
      
    </td>
  </tr>

  <tr>
    <!-- Emergency Contacts -->
    <td class="sub-form-cell">
      <span class="info-header">Emergency Contacts</span>
      <table class="sub-form">
        <tr>
          <td>
            <span style="float:right;">{ADD_EMERGENCY_CONTACT}</span>
            <div id="emergency-contact-list-container" style="width:325px">
            <ul id="emergency-contact-list">
            </ul>
            </div>
            <div id="emergency-spinner" style="margin-top:15px;"></div>
          </td>
        </tr>
      </table>
    </td>
    <!-- End Emergency Contacts -->

    <!-- Document List -->    
    <td class="sub-form-cell">
      <span class="info-header">Contract &amp; Documents</span>
      <table class="sub-form">
        <tr>
          <td>
            <ul>
              <!-- BEGIN docs -->
              <li>{DOWNLOAD}{DELETE}</li>
              <!-- END docs -->
            </ul> <span style="float:right;">{UPLOAD_DOC}</span>
          </td>
        </tr>
      </table>
    </td>
    <!-- End document list -->
  </tr>

  <tr>
    <!-- Internship details -->
    <td class="sub-form-cell"><span class="info-header">Internship
        Details</span>
      <table class="sub-form" id="agency-info">
        <tr>
          <td colspan="2"><h3>Physical Location of Internship</h3>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset{LOC_HIGHLIGHT}>
              <legend>Location<span class="required-input">*</span></legend>
              <!-- BEGIN location_repeat -->
              {LOCATION}{LOCATION_LABEL}<br />
              <!-- END location_repeat -->
            </fieldset></td>
        </tr>
        <tr>
          <td>{LOC_ADDRESS_LABEL}</td>
          <td>{LOC_ADDRESS}</td>
        </tr>
        <tr>
          <td>{LOC_CITY_LABEL}</td>
          <td>{LOC_CITY}</td>
        </tr>
        <tr>
          <td>{LOC_STATE_LABEL}<span class="required-input">*</span>
          </td>
          <td>{LOC_STATE}</td>
        </tr>
        <tr>
          <td>{LOC_ZIP_LABEL}</td>
          <td>{LOC_ZIP}</td>
        </tr>
        <tr>
          <td>{LOC_PROVINCE_LABEL}</td>
          <td>{LOC_PROVINCE}</td>
        </tr>
        <tr>
          <td>{LOC_COUNTRY_LABEL}<span class="required-input">*</span>
          </td>
          <td>{LOC_COUNTRY}</td>
        </tr>
        <tr>
          <td colspan="2"><h3>Term Information</h3>
          </td>
        </tr>
        <tr>
          <td>{TERM_LABEL}<span class="required-input">*</span></td>
          <td>{TERM}</td>
        </tr>
        <tr>
          <td>{START_DATE_LABEL}</td>
          <td><span id="start-date">{START_DATE}</span>
          </td>
        </tr>
        <tr>
          <td>{END_DATE_LABEL}</td>
          <td><span id="end-date">{END_DATE}</span>
          </td>
        </tr>
        <tr>
          <td colspan="2"><h3>Course Information</h3>
          </td>
        </tr>
        <tr>
          <td colspan="2">{MULTIPART}&nbsp;{MULTIPART_LABEL}</td>
        </tr>
        <tr>
          <td colspan="2"><span style="padding-left: 20px;">{SECONDARY_PART}&nbsp;{SECONDARY_PART_LABEL}</span></td>
        </tr>
        <tr>
          <td>{COURSE_SUBJ_LABEL}</td>
          <td>{COURSE_SUBJ}</td>
        </tr>
        <tr>
          <td>{COURSE_NO_LABEL}</td>
          <td>{COURSE_NO}</td>
        </tr>
        <tr>
          <td>{COURSE_SECT_LABEL}</td>
          <td>{COURSE_SECT}</td>
        </tr>
        <tr>
          <td>{CREDITS_LABEL}</td>
          <td>{CREDITS}</td>
        </tr>
        <tr>
          <td>{COURSE_TITLE_LABEL}</td>
          <td>
            {COURSE_TITLE}<br />
            <span class="text disabled smaller italic">(Limit 28 characters; Banner)</span>
          </td>
        </tr>
        
        <!-- BEGIN corequisite -->
        <tr>
        	<td colspan="2">
        	<h3>Corequisite Course</h3>
        	</td>
        </tr>
        <tr>
        	<td>Course Number:</td>
        	<td>{COREQUISITE_COURSE_NUM}</td>
        </tr>
        <tr>
        	<td>Section Number:</td>
        	<td>{COREQUISITE_COURSE_SECT}</td>
        </tr>
        <!-- END corequisite -->

        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Compensation</legend>
              <!-- BEGIN payment_repeat -->
              {PAYMENT}{PAYMENT_LABEL}<br />
              <!-- END payment_repeat -->
              <span style="padding-left: 15px;">{STIPEND}{STIPEND_LABEL}</span><br />
              <span style="padding-left: 15px;">{PAY_RATE_LABEL}{PAY_RATE}</span>
            </fieldset></td>
        </tr>
        <tr>
          <td>{AVG_HOURS_WEEK_LABEL}</td>
          <td>{AVG_HOURS_WEEK}</td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Type</legend>
              <a id="internship-type-help-button" class="tango16 tango-help-browser" style="float:right;cursor: pointer;">Type Definitions</a>
              <!-- BEGIN experience_type_repeat -->
              {EXPERIENCE_TYPE}&nbsp;{EXPERIENCE_TYPE_LABEL}<br />
              <!-- END experience_type_repeat -->
            </fieldset></td>
        </tr>
      </table><br />
      {SUBMIT}
    </td>
    <!-- End internship details -->
    <!-- Agency info -->
    <td class="sub-form-cell"><span class="info-header">Agency
        Details</span>
      <table class="sub-form" id="agency-info">

        <tr>
          <td>{AGENCY_NAME_LABEL}<span class="required-input">*</span></td>
          <td>{AGENCY_NAME}</td>
        </tr>
        <tr>
          <td>{AGENCY_PHONE_LABEL}</td>
          <td>{AGENCY_PHONE}</td>
        </tr>
        <tr>
          <td>{AGENCY_ADDRESS_LABEL}</td>
          <td>{AGENCY_ADDRESS}</td>
        </tr>
        <tr>
          <td>{AGENCY_CITY_LABEL}</td>
          <td>{AGENCY_CITY}</td>
        </tr>
        <tr>
          <td>{AGENCY_STATE_LABEL}</td>
          <td>{AGENCY_STATE}</td>
        </tr>
        <tr>
          <td>{AGENCY_ZIP_LABEL}</td>
          <td>{AGENCY_ZIP}</td>
        </tr>
        <tr>
          <td>{AGENCY_COUNTRY_LABEL}</td>
          <td>{AGENCY_COUNTRY}</td>
        </tr>
        <tr>
          <td colspan=2><h3>Supervisor Info</h3>
          </td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_FIRST_NAME_LABEL}</td>
          <td>{AGENCY_SUP_FIRST_NAME}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_LAST_NAME_LABEL}</td>
          <td>{AGENCY_SUP_LAST_NAME}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_TITLE_LABEL}</td>
          <td>{AGENCY_SUP_TITLE}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_PHONE_LABEL}</td>
          <td>{AGENCY_SUP_PHONE}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_EMAIL_LABEL}</td>
          <td>{AGENCY_SUP_EMAIL}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_FAX_LABEL}</td>
          <td>{AGENCY_SUP_FAX}</td>
        </tr>
        <tr>
          <td colspan="2">{COPY_ADDRESS}{COPY_ADDRESS_LABEL}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_ADDRESS_LABEL}</td>
          <td>{AGENCY_SUP_ADDRESS}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_CITY_LABEL}</td>
          <td>{AGENCY_SUP_CITY}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_STATE_LABEL}</td>
          <td>{AGENCY_SUP_STATE}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_ZIP_LABEL}</td>
          <td>{AGENCY_SUP_ZIP}</td>
        </tr>
        <tr>
          <td>{AGENCY_SUP_COUNTRY_LABEL}</td>
          <td>{AGENCY_SUP_COUNTRY}</td>
        </tr>
      </table>
    </td>
    <!-- End agency info -->
  </tr>
  <tr>
    <td colspan="2" class="sub-form-cell">
      {CHANGE_LOG}
    </td>
  </tr>
  <tr>
    <td colspan="2" class="sub-form-cell"><span class="info-header">Add a note</span>
      <table class="sub-form">
        <tr>
          <td>{NOTES}</td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
{SUBMIT} {END_FORM}

{EMERGENCY_CONTACT_DIALOG}

<div id="internship-type-help">
	<h2>Internship Type Definitions</h2>
	<h3>Student Teaching</h3>
	<p>A course requiring students to instruct or teach at an entity
		external to the institution, generally as part of the culminating
		curriculum of a teacher education or certificate program.</p>

	<h3>Practicum</h3>
	<p>A course requiring students to participate in an approved
		project or proposal that practically applies previously studied theory
		of the field or discipline under the supervision of an expert or
		qualified representative of the field or discipline.</p>

	<h3>Clinical</h3>
	<p>A course requiring medical- or healthcare-focused experiential
		work where students test, observe, experiment, or practice a field or
		discipline in a hands-on or simulated environment.</p>

	<h3>Internship</h3>
	<p>A course requiring students to participate in a partnership,
		professional employment, work experience or cooperative education with
		any entity external to the institution, generally under the
		supervision of an employee of the external entity.</p>
</div>
