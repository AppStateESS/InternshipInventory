<h1 class="search-icon">Search Inventory</h1>
    {START_FORM}
<div style="margin-left: 60px;float:left;margin-right:3em;">
    
      <div class="search-label">{NAME_LABEL}<br />
      {NAME}</div>

      <div class="search-label">{TERM_SELECT_LABEL}<br />
      {TERM_SELECT}</div>

      <div class="search-label">{DEPT_LABEL}<br />
      {DEPT}</div>

      <fieldset class="search-fieldset">
        <legend>Level &amp; Major/Program</legend>
        <!-- BEGIN student_level_repeat -->
        {STUDENT_LEVEL}{STUDENT_LEVEL_LABEL}&nbsp;
        <!--  END student_level_repeat -->
        <br />
        <span id="ugrad_drop">{UGRAD_MAJOR}</span>
        <span id="grad_drop">{GRAD_PROG}</span>
      </fieldset>
      
      <fieldset class="search-fieldset">
        <legend>Campus</legend>
        <!-- BEGIN campus_repeat -->
        {CAMPUS}{CAMPUS_LABEL}&nbsp;
        <!-- END campus_repeat -->
      </fieldset>

      <fieldset class="search-fieldset">
        <legend>Internship Type</legend>
        <!-- BEGIN type_repeat -->
        {TYPE}{TYPE_LABEL}<br/>
        <!-- END type_repeat -->
      </fieldset>

</div>
<div style="float:left;">
      <fieldset class="search-fieldset">
        <legend>Course</legend>
        {COURSE_SUBJ}<br />
        <div class="search-label">Course Number:&nbsp;{COURSE_NO}</div>
        <div class="search-label">Section Number:&nbsp;{COURSE_SECT}</div>
      </fieldset>
      <fieldset class="search-fieldset">
        <legend>Location</legend>
        <!-- BEGIN loc_repeat -->
        {LOC}{LOC_LABEL}&nbsp;
         <!-- END loc_repeat -->
      </fieldset>

      <div class="search-label">{STATE_LABEL}<br />
      {STATE}</div>

      <div class="search-label">
      {PROV_LABEL}<br />
      {PROV}</div>

      <fieldset class="search-fieldset">
        <legend>Status</legend>
        <!-- BEGIN workflow_state_repeat -->
        {WORKFLOW_STATE}{WORKFLOW_STATE_LABEL}<br />
        <!-- END workflow_state_repeat -->
      </fieldset>
</div>
<div style="clear:both; float:right;margin-top:10px;margin-right:150px;">
  {SUBMIT} <input type="button" title="Reset search fields" name="reset" value="Clear Fields"/>
</div>
{END_FORM}
