<table id="admin-pager" border=0 cellspacing=3 cellpadding=3>
<tr>
    <th>Username</th>
    <th>Department</th>
    <th>Option</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan=3>{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->

<!-- BEGIN listrows -->
<tr {TOGGLE}>
    <td>{USERNAME}</td>
    <td>{DEPARTMENT}</td>
    <td>{DELETE}</td>
</tr>
<!-- END listrows -->
</table>
<div align="center">{TOTAL_ROWS}<br/>{PAGES}<br/>{LIMITS}
