<h1>Search Results<img id="results-icon" class="menu-icon" src="mod/intern/img/contact-new.png"/></h1>
<span id="csv-export">{CSV_LINK}</span>
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
    {TERM}
  </td>
  <td class="action">
    {EDIT} | {PDF}
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
<div align="center">
  <b>{PAGE_LABEL}</b><br />
  {PAGES}<br />
  {LIMITS}
</div>
