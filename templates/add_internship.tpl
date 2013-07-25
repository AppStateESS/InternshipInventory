<script type="text/javascript">
	$(document).ready(function() {
		// Setup date pickers
		$("#internship_start_date").datepicker();
		$("#internship_end_date").datepicker();
	});
</script>

<h1 class="add-icon">{TITLE}</h1>

<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}" {FORM_ENCODE}>
{HIDDEN_FIELDS}

<!-- BEGIN generate_contract -->
<div class="controls pull-right">
 <a href="{PDF}" id="contract-button" class="btn"><i class="icon-file"></i> Generate Contract</a>
</div>
<!-- END generate_contract -->

<button type="submit" class="btn btn-primary" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>

<div class="row">
  <!-- Left column -->
  <div class="span6">
    <!-- Student info section -->
    <fieldset>
      <legend>Student</legend>
      
      <div class="control-group">
        <label class="control-label" for="{BANNER_ID}">{BANNER_LABEL_TEXT}</label>
        <div class="controls">
          {BANNER}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_FIRST_NAME_ID}">{STUDENT_FIRST_NAME_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_FIRST_NAME}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_MIDDLE_NAME_ID}">{STUDENT_MIDDLE_NAME_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_MIDDLE_NAME}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_LAST_NAME_ID}">{STUDENT_LAST_NAME_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_LAST_NAME}
        </div>
      </div>
      
      <div class="control-group input-append">
        <label class="control-label" for="{STUDENT_EMAIL_ID}">{STUDENT_EMAIL_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_EMAIL}<span class="add-on">@appstate.edu</span>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_ADDRESS_ID}">{STUDENT_ADDRESS_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_ADDRESS}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_CITY_ID}">{STUDENT_CITY_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_CITY}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_STATE_ID}">{STUDENT_STATE_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_STATE}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_ZIP_ID}">{STUDENT_ZIP_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_ZIP}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_PHONE_ID}">{STUDENT_PHONE_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_PHONE}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_GPA_ID}">{STUDENT_GPA_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_GPA}
        </div>
      </div>
      
      <div class="control-group">
        <div class="controls">
        <!-- BEGIN campus_repeat -->
          <label class="radio">
            {CAMPUS} {CAMPUS_LABEL_TEXT}
          </label>
        <!-- END campus_repeat -->
        </div>
      </div>
      
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_LEVEL_ID}">{STUDENT_LEVEL_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_LEVEL}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STUDENT_MAJOR_ID}">{STUDENT_MAJOR_LABEL_TEXT}</label>
        <div class="controls">
          {STUDENT_MAJOR}
          {UGRAD_MAJOR}
          {GRAD_PROG}
        </div>
      </div>
      
    </fieldset>
    
    <!-- Emergency Contact Info -->
    <fieldset>
      <legend>Emergency Contacts</legend>
    {ADD_EMERGENCY_CONTACT}
    <div id="emergency-contact-list-container" style="width:325px">
      <ul id="emergency-contact-list">
      </ul>
    </div>
    <div id="emergency-spinner" style="margin-top:15px;"></div>
    </fieldset>
    
    <fieldset>
      <legend>Location</legend>
      <p>
      Physical Location of Internship
      </p>
      
      <div class="control-group">
        <div class="controls">
        <!-- BEGIN location_repeat -->
          <label class="radio">
            {LOCATION}{LOCATION_LABEL_TEXT}
          </label>
        <!-- END location_repeat -->
        </div>
      </div>
      
    </fieldset>
    
    <div class="control-group">
      <label class="control-label" for="{LOC_ADDRESS_ID}">{LOC_ADDRESS_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_ADDRESS}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{LOC_CITY_ID}">{LOC_CITY_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_CITY}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{LOC_STATE_ID}">{LOC_STATE_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_STATE}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{LOC_ZIP_ID}">{LOC_ZIP_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_ZIP}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{LOC_PROVINCE_ID}">{LOC_PROVINCE_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_PROVINCE}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="LOC_COUNTRY_ID}">{LOC_COUNTRY_LABEL_TEXT}</label>
      <div class="controls">
        {LOC_COUNTRY}
      </div>
    </div>
    
    <h4>Term Information</h4>
    <div class="control-group">
      <label class="control-label" for="{TERM_ID}">{TERM_LABEL_TEXT}</label>
      <div class="controls">
        {TERM}
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="{START_DATE_ID}">{START_DATE_LABEL_TEXT}</label>
      <div class="controls">
        {START_DATE}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{END_DATE_ID}">{END_DATE_LABEL_TEXT}</label>
      <div class="controls">
        {END_DATE}
      </div>
    </div>
        
    <h4>Course Information</h4>
    
    <div class="control-group">
      <div class="controls">
        <label class="checkbox">
          {MULTIPART}&nbsp;{MULTIPART_LABEL_TEXT}
        </label>
      </div>
    
      <div class="controls">
        <label class="checkbox">
          <span style="padding-left: 20px;">{SECONDARY_PART}&nbsp;{SECONDARY_PART_LABEL_TEXT}</span>
        </label>
      </div> 
    </div>
      
    <div class="control-group">
      <label class="control-label" for="{COURSE_SUBJ_ID}">{COURSE_SUBJ_LABEL_TEXT}</label>
      <div class="controls">
        {COURSE_SUBJ}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{COURSE_NO_ID}">{COURSE_NO_LABEL_TEXT}</label>
      <div class="controls">
        {COURSE_NO}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{COURSE_SECT_ID}">{COURSE_SECT_LABEL_TEXT}</label>
      <div class="controls">
        {COURSE_SECT}
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="{CREDITS_ID}">{CREDITS_LABEL_TEXT}</label>
      <div class="controls">
        {CREDITS}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{CREDITS_ID}">{COURSE_TITLE_LABEL_TEXT}</label>
      <div class="controls">
        {COURSE_TITLE} <span class="help-inline"><small class="muted">(Limit 28 characters; Banner)</small></span>
      </div>
    </div>
          
    <!-- BEGIN corequisite -->
    <h4>Corequisite Course</h4>
       
    <div class="control-group">
      <label class="control-label" for="{CREDITS_ID}">Course Number</label>
      <div class="controls">
        {COREQUISITE_COURSE_NUM}
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="{CREDITS_ID}">Course Section</label>
      <div class="controls">
        {COREQUISITE_COURSE_SECT}
      </div>
    </div>
    <!-- END corequisite -->

    <fieldset>
      <legend>Compensation</legend>
      
      <div class="control-group">
        <div class="controls">
        <!-- BEGIN payment_repeat -->
          <label class="radio">
            {PAYMENT}{PAYMENT_LABEL}
          </label>
        <!-- END payment_repeat -->
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{STIPEND_ID}">{STIPEND_LABEL_TEXT}</label>
        <div class="controls">
          {STIPEND}
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="{AVG_HOURS_WEEK_ID}">{AVG_HOURS_WEEK_LABEL_TEXT}</label>
        <div class="controls">
         {AVG_HOURS_WEEK}
        </div>
      </div>
    </fieldset>
        
    <fieldset>
      <legend>Type</legend>
      <a id="internship-type-help-button" class="tango16 tango-help-browser" style="float:right;cursor: pointer;">Type Definitions</a>
      <div class="control-group">
        <div class="controls">
        <!-- BEGIN experience_type_repeat -->
          <label class="radio">
            {EXPERIENCE_TYPE} {EXPERIENCE_TYPE_LABEL}
          </label>
        <!-- END experience_type_repeat -->
        </div>
      </div>
    </fieldset>
    
  </div> <!-- End of left column -->
  
  
  <!-- Right Column -->
  <div class="span6">
    <!-- Status -->
    <h4>Status</h4>
    Current Status: <strong>{WORKFLOW_STATE}</strong>
    
    <div class="status-box-content">
      <fieldset>
        <legend>Action</legend>
        <div class="control-group">
          <div class="controls">
            <!-- BEGIN workflow_action_repeat -->
            <label class="radio">
              {WORKFLOW_ACTION} {WORKFLOW_ACTION_LABEL}
            </label>
            <!-- END workflow_action_repeat -->
          </div>
        </div>
      </fieldset>
    </div>

    <div class="control-group">
      <div class="controls">
        <label class="checkbox">
          {OIED_CERTIFIED} {OIED_CERTIFIED_LABEL}
        </label>
      </div>
    </div>
  
    <!-- Faculty info -->
    <div id="faculty_selector">
      <div>
        {DEPARTMENT_LABEL} <span class="required-input">*</span><br />
        {DEPARTMENT}
      </div>
      <div style="margin-top: 1em;">
        {FACULTY_LABEL}<br /> {FACULTY}
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
  
    <!-- Document List -->
    <h4>Documents</h4>
    <ul>
      <!-- BEGIN docs -->
      <li>{DOWNLOAD}{DELETE}</li>
      <!-- END docs -->
    </ul>
    <span style="float:right;">{UPLOAD_DOC}</span>
    
    <!-- Agency info -->
    <span class="info-header">Agency Details</span>

    {AGENCY_NAME_LABEL}<span class="required-input">*</span>
    {AGENCY_NAME}
       
    {AGENCY_PHONE_LABEL}
    {AGENCY_PHONE}
       
    {AGENCY_ADDRESS_LABEL}
    {AGENCY_ADDRESS}
         
    {AGENCY_CITY_LABEL}
    {AGENCY_CITY}
        
    {AGENCY_STATE_LABEL}
    {AGENCY_STATE}
        
    {AGENCY_ZIP_LABEL}
    {AGENCY_ZIP}
          
    {AGENCY_COUNTRY_LABEL}
    {AGENCY_COUNTRY}
          
    <h3>Supervisor Info</h3>
    {AGENCY_SUP_FIRST_NAME_LABEL}
    {AGENCY_SUP_FIRST_NAME}
    
    {AGENCY_SUP_LAST_NAME_LABEL}
    {AGENCY_SUP_LAST_NAME}
    
    {AGENCY_SUP_TITLE_LABEL}
    {AGENCY_SUP_TITLE}
    
    {AGENCY_SUP_PHONE_LABEL}
    {AGENCY_SUP_PHONE}
    
    {AGENCY_SUP_EMAIL_LABEL}
    {AGENCY_SUP_EMAIL}
    
    {AGENCY_SUP_FAX_LABEL}
    {AGENCY_SUP_FAX}
    
    {COPY_ADDRESS}{COPY_ADDRESS_LABEL}
    {AGENCY_SUP_ADDRESS_LABEL}
    {AGENCY_SUP_ADDRESS}
    
    {AGENCY_SUP_CITY_LABEL}
    {AGENCY_SUP_CITY}
    
    {AGENCY_SUP_STATE_LABEL}
    {AGENCY_SUP_STATE}
    
    {AGENCY_SUP_ZIP_LABEL}
    {AGENCY_SUP_ZIP}
    
    {AGENCY_SUP_COUNTRY_LABEL}
    {AGENCY_SUP_COUNTRY}
    
  </div> <!-- End of right column -->
</div>

<div class="row">
  <div class="span12">
    {CHANGE_LOG}
  </div>
</div>

<div class="row">
  <div class="span12">
  Add a note
    {NOTES}
  </div>
</div>

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
