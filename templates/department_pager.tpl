<table id="department-pager">
<tr>
    <th>Name:</th>
    <th>Option</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan=3>{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->

<!-- BEGIN listrows -->
<tr>
    <td>{NAME}</td>
    <td>{EDIT}{HIDE}<span id="delete-{ID}">{DELETE}</td>
</tr>
<!-- END listrows -->
</table>

<div align="center">
<b>{PAGE_LABEL}</b><br />
{PAGES}<br />
{LIMITS}
</div>
