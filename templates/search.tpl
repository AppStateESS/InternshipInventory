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
    <td class="search-header">{MAJOR_LABEL}</td>
    <td>{MAJOR}</td>
  </tr>

  <tr>
    <td class="search-header">{GRAD_LABEL}</td>
    <td>{GRAD}</td>
  </tr>

  <tr>
    <td class="search-header">Internship Type</td>
    <td>
      <!-- BEGIN type_repeat -->
      {TYPE}{TYPE_LABEL}<br/>
      <!-- END type_repeat -->
    </td>
  </tr>
  <tr>
    <td class="search-header">Location</td>
    <td>
      <!-- BEGIN loc_repeat -->
      {LOC}{LOC_LABEL}<br/>
      <!-- END loc_repeat -->
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
    <td class="search-header">Status</td>
    <td>
      <!-- BEGIN workflow_state_repeat -->
      {WORKFLOW_STATE}{WORKFLOW_STATE_LABEL}<br />
      <!-- END workflow_state_repeat -->
    </td>
    
  </tr>
  <tr>
    <td colspan="2">{SUBMIT} <input type="button" title="Reset search fields" name="reset" value="Clear Fields"/></td>
  </tr>
</table>
</table>
{END_FORM}
</div>

