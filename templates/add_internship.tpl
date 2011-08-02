<script type="text/javascript">
    $(document).ready(function(){
        $("#internship_start_date").datepicker();
        $("#internship_end_date").datepicker();
    });
</script>

<h1>{TITLE}<img id="results-icon" class="menu-icon" src="mod/intern/img/contact-new.png"/></h1>
<div id="pdf-download">{PDF}</div>
<div>
  {START_FORM}
  {SUBMIT}
  <p style="float : right">{APPROVED} {APPROVED_LABEL} {APPROVED_BY_ON}</p>
  <table id="internship-form">
    <tr>
  <!-- Student info section -->
  <td class="sub-form-cell">
    <div class="info-header">Student</div>
  <table class="sub-form" id="student-info">
    <tr>
      <td>
        {STUDENT_FIRST_NAME_LABEL}
      </td>
      <td>
        {STUDENT_FIRST_NAME}
      </td>
    </tr>
    <tr>
      <td>
        {STUDENT_MIDDLE_NAME_LABEL}
      </td>
      <td>
        {STUDENT_MIDDLE_NAME}
      </td>
    </tr>
    <tr>
      <td>
        {STUDENT_LAST_NAME_LABEL}
      </td>
      <td>
        {STUDENT_LAST_NAME}
      </td>
    </tr>
    <tr>
      <td>
        {BANNER_LABEL}
      </td>
      <td>
        {BANNER}
      </td>
    </tr>
    <tr>
      <td>
        {STUDENT_PHONE_LABEL}
      </td>
      <td>
        {STUDENT_PHONE}
      </td>
    </tr>
    <tr>
      <td>
        {STUDENT_EMAIL_LABEL}
      </td>
      <td>
        {STUDENT_EMAIL}
      </td>
    </tr>
    <tr>
      <td colspan=2>
        {UGRAD_MAJOR_LABEL}<br />
        {UGRAD_MAJOR}
      </td>
    </tr>
    <tr>
      <td>
        {GRAD_PROG_LABEL}
      </td>
      <td>
        {GRAD_PROG}
      </td>
    </tr>
  </table>
  </td>
  <!-- End student info section -->
  <!-- Faculty info -->
  <td class="sub-form-cell">
    <span class="info-header">Faculty</span>
    <table class="sub-form" id="faculty-info">
      <tr>
        <td>
          {SUPERVISOR_FIRST_NAME_LABEL}
        </td>
        <td>
          {SUPERVISOR_FIRST_NAME}
        </td>
      </tr>
      <tr>
        <td>
          {SUPERVISOR_LAST_NAME_LABEL}
        </td>
        <td>
          {SUPERVISOR_LAST_NAME}
        </td>
      </tr>
      <tr>
        <td>
          {SUPERVISOR_EMAIL_LABEL}
        </td>
        <td>
          {SUPERVISOR_EMAIL}
        </td>
      </tr>
      <tr>
        <td>
          {SUPERVISOR_PHONE_LABEL}
        </td>
        <td>
          {SUPERVISOR_PHONE}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          {DEPARTMENT_LABEL}<br/ >
            {DEPARTMENT}
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
            <li>{UPLOAD_DOC}</li>
          </ul>
        </td>
      </tr>
    </table>
    <!-- end document list -->
  </td>
  <!-- End faculty info -->
  </tr>

  <tr>
  <!-- Internship details -->
  <td class="sub-form-cell"> 
    <span class="info-header">Internship Details</span>
    <table class="sub-form" id="agency-info">
    <tr><td colspan="2">
    <fieldset>
            <legend>
              <b>Location</b>
            </legend>
            {LOCATION_1}{LOCATION_1_LABEL}
            {LOCATION_2}{LOCATION_2_LABEL}
    </fieldset>
        </td>
      </tr>
      <tr>
        <td>{TERM_LABEL}</td>
        <td>{TERM}</td>
      </tr>
      <tr>
        <td>
          {START_DATE_LABEL}
        </td>
        <td>
          <span id="start-date">{START_DATE}</span>
        </td>
      </tr>
      <tr>
        <td>
          {END_DATE_LABEL}
        </td>
        <td>
          <span id="end-date">{END_DATE}</span>
        </td>
      </tr>
      <tr>
        <td>
          {CREDITS_LABEL}
        </td>
        <td>
          {CREDITS}
        </td>
      </tr>
      <tr>
        <td>
          {AVG_HOURS_WEEK_LABEL}
        </td>
        <td>
          {AVG_HOURS_WEEK}
        </td>
      </tr>
      <tr><td colspan="2"><h3>Course Information</h3></td></tr>
      <tr><td>{COURSE_SUBJ_LABEL}</td><td>{COURSE_SUBJ}</td></tr>
      <tr><td>{COURSE_NO_LABEL}</td><td>{COURSE_NO}</td></tr>
      <tr><td>{COURSE_SECT_LABEL}</td><td>{COURSE_SECT}</td></tr>
      <tr><td>{COURSE_TITLE_LABEL}</td><td>{COURSE_TITLE}</td></tr>
      <tr><td colspan="2"><h3>Student Location during Internship</h3></td></tr>
      <tr>
        <td>{LOC_ADDRESS_LABEL}</td>
        <td>{LOC_ADDRESS}</td>
      </tr>
      <tr>
        <td>{LOC_CITY_LABEL}</td>
        <td>{LOC_CITY}</td>
      </tr>
      <tr>
        <td>{LOC_STATE_LABEL}</td>
        <td>{LOC_STATE}</td>
      </tr>
      <tr>
        <td>{LOC_ZIP_LABEL}</td>
        <td>{LOC_ZIP}</td>
      </tr>
      <tr>
        <td>{LOC_COUNTRY_LABEL}</td>
        <td>{LOC_COUNTRY}</td>
      </tr>

      <tr>
        <td colspan="2">
          <fieldset>
            <legend>
              <b>Payment</b>
            </legend>
            {PAYMENT_1}{PAYMENT_1_LABEL}<br/>
            {PAYMENT_2}{PAYMENT_2_LABEL}<br/>
            <span style="padding-left: 15px;">{STIPEND}{STIPEND_LABEL}</span>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset>
            <legend>
              <b>Type</b>
            </legend>
            {INTERNSHIP_DEFAULT_TYPE}{INTERNSHIP_DEFAULT_TYPE_LABEL}<br/>  
            <!-- {SERVICE_LEARNING_TYPE}{SERVICE_LEARNING_TYPE_LABEL}<br/>
            {INDEPENDENT_STUDY_TYPE}{INDEPENDENT_STUDY_TYPE_LABEL}<br/>
            {RESEARCH_ASSIST_TYPE}{RESEARCH_ASSIST_TYPE_LABEL}<br/> -->
            {STUDENT_TEACHING_TYPE}{STUDENT_TEACHING_TYPE_LABEL}<br/>
            {CLINICAL_PRACTICA_TYPE}{CLINICAL_PRACTICA_TYPE_LABEL}<br/>
            <!-- {SPECIAL_TOPICS_TYPE}{SPECIAL_TOPICS_TYPE_LABEL}<br/>
            {CHECK_OTHER_TYPE}{OTHER_TYPE_LABEL}{OTHER_TYPE}<br/> -->
          </fieldset>
        </td>
      </tr>
    </table>
  </td>
  <!-- End internship details -->
  <!-- Agency info -->
  <td class="sub-form-cell">
    <span class="info-header">Agency</span>
    <table class="sub-form" id="agency-info">
      
      <tr>
        <td>
          {AGENCY_NAME_LABEL}
        </td>
        <td>
          {AGENCY_NAME}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_PHONE_LABEL}
        </td>
        <td>
          {AGENCY_PHONE}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_ADDRESS_LABEL}
        </td>
        <td>
          {AGENCY_ADDRESS}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_CITY_LABEL}
        </td>
        <td>
          {AGENCY_CITY}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_STATE_LABEL}
        </td>
        <td>
          {AGENCY_STATE}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_ZIP_LABEL}
        </td>
        <td>
          {AGENCY_ZIP}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_COUNTRY_LABEL}
        </td>
        <td>
          {AGENCY_COUNTRY}
        </td>
      </tr>
      <tr>
        <td colspan=2><h4>Supervisor Info</h4></td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_FIRST_NAME_LABEL}
        </td>
        <td>
          {AGENCY_SUP_FIRST_NAME}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_LAST_NAME_LABEL}
        </td>
        <td>
          {AGENCY_SUP_LAST_NAME}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_PHONE_LABEL}
        </td>
        <td>
          {AGENCY_SUP_PHONE}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_EMAIL_LABEL}
        </td>
        <td>
          {AGENCY_SUP_EMAIL}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_FAX_LABEL}
        </td>
        <td>
          {AGENCY_SUP_FAX}
        </td>
      </tr>
      <tr>
        <td colspan="2">
          {COPY_ADDRESS}{COPY_ADDRESS_LABEL}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_ADDRESS_LABEL}
        </td>
        <td>
          {AGENCY_SUP_ADDRESS}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_CITY_LABEL}
        </td>
        <td>
          {AGENCY_SUP_CITY}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_STATE_LABEL}
        </td>
        <td>
          {AGENCY_SUP_STATE}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_ZIP_LABEL}
        </td>
        <td>
          {AGENCY_SUP_ZIP}
        </td>
      </tr>
      <tr>
        <td>
          {AGENCY_SUP_COUNTRY_LABEL}
        </td>
        <td>
          {AGENCY_SUP_COUNTRY}
        </td>
      </tr>
    </table>
  </td>
  <!-- End agency info -->
  </tr>
  </table>
  <h2>{NOTES_LABEL}</h2><div>{NOTES}</div>
  {SUBMIT}
{END_FORM}
</div>

