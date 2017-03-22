<table id="affiliate-pager" class="table table-hover table-striped">
<tr>
    <th>Name {NAME_SORT}</th>
    <th>Expiration Date {END_DATE_SORT}</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan="3">{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->

<!-- BEGIN listrows -->
<tr class="result-row {STATUS}">
    <td>{NAME}</td>
    <td>{EXPIRES}</td>
</tr>
<!-- END listrows -->
</table>

<div align="center">
<b>{PAGE_LABEL}</b><br />
{PAGES}<br />
{LIMITS}
</div>
