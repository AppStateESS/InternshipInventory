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
        {STUDENT_LEVEL}{STUDENT_LEVEL_LABEL}
        <!--  END student_level_repeat -->
        <br />
        <span id="ugrad_drop">{UGRAD_MAJOR}</span>
        <span id="grad_drop">{GRAD_PROG}</span>
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
        <legend>Location</legend>
        <!-- BEGIN loc_repeat -->
        {LOC}{LOC_LABEL}
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
    <br />
    {SUBMIT} <input type="button" title="Reset search fields" name="reset" value="Clear Fields"/>
</div>
{END_FORM}