<script type="text/javascript">
	$(document).ready(function() {
		// Setup date pickers
		$("#internship_start_date").datepicker();
		$("#internship_end_date").datepicker();
	});
</script>

<h1 class="add-icon">{TITLE}</h1>
<div id="pdf-download">{PDF}</div>

{START_FORM} {SUBMIT}

<table id="internship-form">
  <tr>
    <!-- Student info section -->
    <td class="sub-form-cell"><span class="info-header">Student</span>
      <table class="sub-form" id="student-info">
        <tr>
          <td>{STUDENT_FIRST_NAME_LABEL}</td>
          <td>{STUDENT_FIRST_NAME}</td>
        </tr>
        <tr>
          <td>{STUDENT_MIDDLE_NAME_LABEL}</td>
          <td>{STUDENT_MIDDLE_NAME}</td>
        </tr>
        <tr>
          <td>{STUDENT_LAST_NAME_LABEL}</td>
          <td>{STUDENT_LAST_NAME}</td>
        </tr>
        <tr>
          <td>{BANNER_LABEL}</td>
          <td>{BANNER}</td>
        </tr>
        <tr>
          <td>{STUDENT_EMAIL_LABEL}</td>
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
          <td>{STUDENT_PHONE_LABEL}</td>
          <td>{STUDENT_PHONE}</td>
        </tr>
        <tr>
          <td>{STUDENT_GPA_LABEL}</td>
          <td>{STUDENT_GPA}</td>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Major</legend>
              <!-- BEGIN student_level_repeat -->
              {STUDENT_LEVEL}{STUDENT_LEVEL_LABEL}
              <!--  END student_level_repeat -->
              <br /> <span id="ugrad_drop"> {UGRAD_MAJOR_LABEL}<span
                class="required-input">*</span><br /> {UGRAD_MAJOR} </span> <span
                id="grad_drop"> {GRAD_PROG_LABEL}<span
                class="required-input">*</span><br /> {GRAD_PROG} </span>
            </fieldset></td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class="align-form">
              <legend>Emergency Contact</legend>
              {EMERGENCY_CONTACT_NAME_LABEL}{EMERGENCY_CONTACT_NAME}<br />
              {EMERGENCY_CONTACT_RELATION_LABEL}{EMERGENCY_CONTACT_RELATION}<br />
              {EMERGENCY_CONTACT_PHONE_LABEL}{EMERGENCY_CONTACT_PHONE}<br />
            </fieldset></td>
        </tr>
      </table></td>
    <!-- End student info section -->
    <!-- Faculty info -->
    <td class="sub-form-cell"><span class="info-header">Faculty</span>
      <table class="sub-form" id="faculty-info">
        <tr>
          <td>{SUPERVISOR_FIRST_NAME_LABEL}</td>
          <td>{SUPERVISOR_FIRST_NAME}</td>
        </tr>
        <tr>
          <td>{SUPERVISOR_LAST_NAME_LABEL}</td>
          <td>{SUPERVISOR_LAST_NAME}</td>
        </tr>
        <tr>
          <td>{SUPERVISOR_EMAIL_LABEL}</td>
          <td>{SUPERVISOR_EMAIL}@appstate.edu</td>
        </tr>
        <tr>
          <td>{SUPERVISOR_PHONE_LABEL}</td>
          <td>{SUPERVISOR_PHONE}</td>
        </tr>
        <tr>
          <td colspan=2>{DEPARTMENT_LABEL}<br/ > {DEPARTMENT}</td>
        </tr>
      </table>

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
          </td>
        </tr>
      </table>

      <!-- start document list -->
      <span class="info-header">Documents</span>
      <table class="sub-form">
        <tr>
          <td>
            <ul>
              <!-- BEGIN docs -->
              <li>{DOWNLOAD}{DELETE}</li>
              <!-- END docs -->
            </ul> {UPLOAD_DOC}</td>
        </tr>
      </table> <!-- end document list --></td>
    <!-- End faculty info -->
  </tr>

  <tr>
    <!-- Internship details -->
    <td class="sub-form-cell"><span class="info-header">Internship
        Details</span>
      <table class="sub-form" id="agency-info">
        <tr>
          <td colspan="2"><h3>Location of Internship</h3>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset{LOC_HIGHLIGHT}>
              <legend>Location</legend>
              <!-- BEGIN location_repeat -->
              {LOCATION}{LOCATION_LABEL}
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
          <td>{TERM_LABEL}</td>
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
          <td>{CREDITS_LABEL}</td>
          <td>{CREDITS}</td>
        </tr>
        <tr>
          <td>{AVG_HOURS_WEEK_LABEL}</td>
          <td>{AVG_HOURS_WEEK}</td>
        </tr>
        <tr>
          <td colspan="2"><h3>Course Information</h3>
          </td>
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
          <td>{COURSE_TITLE_LABEL}</td>
          <td>{COURSE_TITLE}</td>
        </tr>

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
          <td colspan="2">
            <fieldset>
              <legend>Type</legend>
              {INTERNSHIP_DEFAULT_TYPE}{INTERNSHIP_DEFAULT_TYPE_LABEL}<br />
              <!-- {SERVICE_LEARNING_TYPE}{SERVICE_LEARNING_TYPE_LABEL}<br/>
            {INDEPENDENT_STUDY_TYPE}{INDEPENDENT_STUDY_TYPE_LABEL}<br/>
            {RESEARCH_ASSIST_TYPE}{RESEARCH_ASSIST_TYPE_LABEL}<br/> -->
              {STUDENT_TEACHING_TYPE}{STUDENT_TEACHING_TYPE_LABEL}<br />
              {CLINICAL_PRACTICA_TYPE}{CLINICAL_PRACTICA_TYPE_LABEL}<br />
              <!-- {SPECIAL_TOPICS_TYPE}{SPECIAL_TOPICS_TYPE_LABEL}<br/>
            {CHECK_OTHER_TYPE}{OTHER_TYPE_LABEL}{OTHER_TYPE}<br/> -->
            </fieldset></td>
        </tr>
      </table></td>
    <!-- End internship details -->
    <!-- Agency info -->
    <td class="sub-form-cell"><span class="info-header">Agency
        Details</span>
      <table class="sub-form" id="agency-info">

        <tr>
          <td>{AGENCY_NAME_LABEL}</td>
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
      </table></td>
    <!-- End agency info -->
  </tr>
  <tr>
    <td colspan="2" class="sub-form-cell"><span class="info-header">Notes</span>
      <table class="sub-form">
        <tr>
          <td>{NOTES}</td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
{SUBMIT} {END_FORM}

