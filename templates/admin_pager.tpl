<table width="100%" border=0 cellspacing=3 cellpadding=3>
<tr>
    <th>USERNAME</th>
    <th>DEPARTMENT</th>
    <th>OPTION</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan=3>{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->

<!-- BEGIN listrows -->
<tr {TOGGLE}>
    <td>{USERNAME}</td>
    <td>{NAME}</td>
    <td>{DELETE}</td>
</tr>
<!-- END listrows -->
</table>
<div align="center">{TOTAL_ROWS}<br/>{PAGES}<br/>{LIMITS}
