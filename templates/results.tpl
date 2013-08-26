<h2><i class="icon-search"></i> Search Results</h2>

<div class="row">
  <div class="col-lg-1">
    <p>
      <a href="{BACK_LINK_URI}" class="btn btn-default btn-sm"><i class="icon-chevron-left"></i> Back to search</a>
    </p>
  </div>
  <div class="col-lg-2 col-lg-offset-9">
    <a href="{EXPORT_URI}" class="btn btn-primary"><i class="icon-download-alt"></i> Export to Spreadsheet</a>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
<table class="table table-striped table-hover" id="search-results">
<tr id="header-row">
  <th>{LAST_NAME_SORT}</th>
  <th>{BANNER_SORT}</th>
  <th>{NAME_SORT}</th>
  <th>{INTERN_FACULTY_LAST_NAME_SORT}</th>
  <th>{TERM_SORT}</th>
  <th>{STATE_SORT}</th>
</tr>

<!-- BEGIN listrows -->
<tr class="result-row">
  <td>{STUDENT_NAME}</td>
  <td>{STUDENT_BANNER}</td>
  <td>{DEPT_NAME}</td>
  <td>{FACULTY_NAME}</td>
  <td>{TERM}</td>
  <td>{WORKFLOW_STATE}</td>
</tr>
<!-- END listrows -->
</table>

<div style="text-align:center">
{TOTAL_ROWS} results<br />
{PAGES}<br />
{LIMITS}<br />
</div>

  </div>
</div>