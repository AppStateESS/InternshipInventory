<div style="float:right;">
    {SEARCH}
</div>

<table id="admin-pager" style="border:0; cell-spacing:3px; padding:3px;clear:both;">
<tr>
    <th>Username{USERNAME_SORT}</th>
    <th>Department{DEPARTMENT_ID_SORT}</th>
    <th>Option</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan=3>{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->

<!-- BEGIN listrows -->
<tr class="{TOGGLE}">
    <td>{USERNAME}</td>
    <td>{DEPARTMENT}</td>
    <td>{DELETE}</td>
</tr>
<!-- END listrows -->
</table>
<div align="center">{TOTAL_ROWS}<br/>{PAGES}<br/>{LIMITS}</div>
