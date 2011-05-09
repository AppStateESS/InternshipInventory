<div>{HOME_LINK} | {ADD_LINK}</div>
<h1>Internship Search</h1>
{START_FORM}
<table>
  <tr>
    <td>{NAME_LABEL}{NAME}</td>
    <td>{BANNER_LABEL}{BANNER}</td>
  </tr>
  <tr>
    <td>{TERM_LABEL}{TERM}</td>
    <td>{DEPTNAME_LABEL}{DEPTNAME}</td>

  </tr>
  <tr>
    <td>{SUBMIT}<button style="border: 1px solid black;" id="export-csv" type="button">{CSV_REPORT}</button></td>
  </tr>
</table>
{END_FORM}
<table id="search-results">
<tr id="header-row">
  <th>
    Student's Name
  </th>
  <th>
    Banner
  </th>
  <th>
    Dept.
  </th>
  <th>
    Grad./Undergrad.
  </th>
  <th>
    Term
  </th>
  <th>
    Action
  </th>
</tr>
<!-- BEGIN listrows -->
<tr class="result-row" id="{ID}">
  <td>
    {STUDENT_NAME}
  </td>
  <td>
    {STUDENT_BANNER}
  </td>
  <td>
    {DEPT_NAME}
  </td>
  <td>
    {GRAD_UGRAD}
  </td>
  <td>
    {TERM}
  </td>
  <td class="action">
    {EDIT}
  </td>
</tr>
<tr>
  <td colspan="5">
    <div id="{ID}-details"></div>
  </td>     
</tr>
<!-- END listrows -->
<!-- BEGIN empty_table -->
<tr>
    <td colspan=5 class="empty-message">{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->
</table>
