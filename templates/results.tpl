<h1 class="search-results-icon">Search Results</h1>
<span>{BACK_LINK}</span><br />
<span style="float:right;">{CSV_REPORT}</span>

<table id="search-results">
<tr id="header-row">
  <th>{STUDENT_LAST_NAME_SORT}</th>
  <th>{BANNER_SORT}</th>
  <th>{NAME_SORT}</th>
  <th>{LAST_NAME_SORT}</th>
  <th>{TERM_SORT}</th>
  <th>Action</th>
</tr>

<!-- BEGIN listrows -->
<tr class="result-row" id="{ID}">
  <td>{STUDENT_NAME}</td>
  <td>{STUDENT_BANNER}</td>
  <td>{DEPT_NAME}</td>
  <td>{FACULTY_NAME}</td>
  <td>{TERM}</td>
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

<div style="text-align:center">
{TOTAL_ROWS} results<br />
{PAGES}<br />
{LIMITS}<br />
</div>
