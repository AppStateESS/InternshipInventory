<h1 class="search-icon">Search Inventory</h1>
{START_FORM}

<table>
  <tr>
    <td class="search-header">{NAME_LABEL}</td>
    <td>{NAME}</td>
  </tr>

  <tr>
    <td class="search-header">{TERM_SELECT_LABEL}</td>
    <td>{TERM_SELECT}</td>
  </tr>

  <tr>
    <td class="search-header">{DEPT_LABEL}</td>
    <td>{DEPT}</td>
  </tr>

  <tr>
    <td class="search-header">&nbsp;</td>
    <td id="major-level">
      <fieldset>
        <legend>Level &amp; Major/Program</legend>
        <!-- BEGIN student_level_repeat -->
        {STUDENT_LEVEL}{STUDENT_LEVEL_LABEL}
        <!--  END student_level_repeat -->
        <br />
        <span id="ugrad_drop">{UGRAD_MAJOR}</span>
        <span id="grad_drop">{GRAD_PROG}</span>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td class="search-header">{GRAD_LABEL}</td>
    <td>{GRAD}</td>
  </tr>

  <tr>
    <td class="search-header">&nbsp;</td>
    <td>
      <fieldset>
        <legend>Internship Type</legend>
        <!-- BEGIN type_repeat -->
        {TYPE}{TYPE_LABEL}<br/>
        <!-- END type_repeat -->
      </fieldset>
    </td>
  </tr>
  <tr>
    <td class="search-header">&nbsp;</td>
    <td>
      <fieldset>
        <legend>Location</legend>
        <!-- BEGIN loc_repeat -->
        {LOC}{LOC_LABEL}<br/>
         <!-- END loc_repeat -->
      </fieldset>
    </td>
  </tr>
  <tr>
    <td class="search-header">{STATE_LABEL}</td>
    <td>{STATE}</td>
  </tr>
  <tr>
    <td class="search-header">{PROV_LABEL}</td>
    <td>{PROV}</td>
  </tr>
  <tr>
    <td class="search-header">&nbsp;</td>
    <td>
      <fieldset>
        <legend>Status</legend>
        <!-- BEGIN workflow_state_repeat -->
        {WORKFLOW_STATE}{WORKFLOW_STATE_LABEL}<br />
        <!-- END workflow_state_repeat -->
      </fieldset>
    </td>
    
  </tr>
  <tr>
    <td colspan="2">{SUBMIT} <input type="button" title="Reset search fields" name="reset" value="Clear Fields"/></td>
  </tr>
</table>
</table>
{END_FORM}
</div>

