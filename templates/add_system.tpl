<script src="mod/sysinventory/javascript/ui.datepicker.js"></script>

<script>
$(document).ready(function() {
    $('#add_system_purchase_date').datepicker();
});
</script>

<div class="page-title">{PAGE_TITLE}</div>
<br/>{HOME_LINK}<br/>{MESSAGE}<br/>
{START_FORM}
<table width="100%">
<tr>
    <td align="right">{DEPARTMENT_ID_LABEL}</td>
    <td>{DEPARTMENT_ID}</td>
</tr>

<tr>
    <td align="right">{LOCATION_ID_LABEL}</td>
    <td>{LOCATION_ID}</td>
</tr>
<tr>
    <td align="right">{ROOM_NUMBER_LABEL}</td>
    <td>{ROOM_NUMBER}</td>
</tr>
<tr>
    <td align="right">{MODEL_LABEL}</td>
    <td>{MODEL}</td>
</tr>
<tr>
    <td align="right">{HDD_LABEL}</td>
    <td>{HDD}</td>
</tr>
<tr>
    <td align="right">{PROC_LABEL}</td>
    <td>{PROC}</td>
</tr>
<tr>
    <td align="right">{RAM_LABEL}</td>
    <td>{RAM}</td>
</tr>
<tr>
    <td align="right">{DUAL_MON_LABEL}</td>
    <td>{DUAL_MON}</td>
</tr>
<tr>
    <td align="right">{MAC_LABEL}</td>
    <td>{MAC}</td>
</tr>
<tr>
    <td align="right">{PRINTER_LABEL}</td>
    <td>{PRINTER}</td>
</tr>
<tr>
    <td align="right">{STAFF_MEMBER_LABEL}</td>
    <td>{STAFF_MEMBER}</td>
</tr>
<tr>
    <td align="right">{USERNAME_LABEL}</td>
    <td>{USERNAME}</td>
</tr>
<tr>
    <td align="right">{TELEPHONE_LABEL}</td>
    <td>{TELEPHONE}</td>
</tr>
<tr>
    <td align="right">{DOCKING_STAND_LABEL}</td>
    <td>{DOCKING_STAND}</td>
</tr>
<tr>
    <td align="right">{DEEP_FREEZE_LABEL}</td>
    <td>{DEEP_FREEZE}</td>
</tr>
<tr>
    <td align="right">{PURCHASE_DATE_LABEL}</td>
    <td>{PURCHASE_DATE}</td>
</tr>
<tr>
    <td align="right">{ROTATION_LABEL}</td>
    <td>{ROTATION}</td>
</tr>
<tr>
    <td align="right">{VLAN_LABEL}</td>
    <td>{VLAN}</td>
</tr>
<tr>
    <td align="right">{REFORMAT_LABEL}</td>
    <td>{REFORMAT}</td>
</tr>
<tr>
    <td align="right">{NOTES_LABEL}</td>
    <td>{NOTES}</td>
</tr>
<tr>
    <td colspan=2 align="center">{SUBMIT}</td>
</tr>
</table>
{END_FORM}
