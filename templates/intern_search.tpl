<div>{HOME_LINK}</div>
<h1>Internship Search</h1>
{START_FORM}
{LASTNAME_LABEL}{LASTNAME}<br />
{BANNER_LABEL}{BANNER}<br />
{DEPTNAME_LABEL}{DEPTNAME}<br />
{TERM_LABEL}{TERM}<br />
{SUBMIT}

{END_FORM}
<table id="search-results">
<tr id="header-row">
  <th>
    Student {LAST_NAME_SORT}
  </th>
  <th>
    Banner {BANNER_SORT}
  </th>
  <th>
    Dept. {DEPARTMENT_NAME_SORT}
  </th>
  <th>
    Grad./Undergrad.
  </th>
  <th>
    Term {TERM_SORT}
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
</tr>
<!-- END listrows -->
<!-- BEGIN empty_table -->
<tr>
    <td colspan=5 class="empty-message">{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->
</table>
