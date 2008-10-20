<script type="text/javascript">
    function hideMe(actor, actee){
        actee.hide('fast');
    }

    function showMe(actor, actee){
        actee.show('fast');
    }

    function hideOther(){
        this.actor;
        this.actee;
        
        this.hideMe;
        this.showMe;
    }

    function hideOther(actor, actee, hidden){
        var parent  = this;
        this.actor  = $("#"+actor);
        this.actee  = $("#"+actee);
        this.hideMe = hideMe;
        this.showMe = showMe;
        
        if(hidden == true){
            this.actee.hide();
            this.actor.toggle(
                function(){
                    parent.showMe(parent.actor, parent.actee);
                },
                function(){
                    parent.hideMe(parent.actor, parent.actee);
                }
            );
        } else {
            this.actor.toggle(
                function(){
                    parent.hideMe(parent.actor, parent.actee);
                },
                function(){
                    parent.showMe(parent.actor, parent.actee);
                }
            );
        }
    }

    $(document).ready(function () {$('.delete').click(function() {
        confirmed = window.confirm('Are you sure you want to delete this entry?');
        if (confirmed) {
            id = this.id;
            //url = 'index.php?module=sysinventory&action=pdf&sysid=' + id;
            //window.location = url; 
            $.post('index.php', {'action' : 'delete_system', 'systemid' : id}, function(data) {
                if (data != 'false') {
                    $('#expanded'+id).hide('fast');
                    $('#expander'+id).hide('fast');
                    window.location = 'index.php?module=sysinventory&action=pdf';
                }else{
                   alert('System could not be deleted.  Please contact ESS.');
                }
            });
        }
    });
    });

</script>

<div class="page-title">{PAGE_TITLE}</div>
{HOME_LINK} | {QUERY_LINK} | {ADD_SYSTEM_LINK}
<br/><br/>
<table width="100%" cellspacing="10px">
<tr>
    <th>Department {DESCRIPTION_SORT}</th>
    <th>Location {LOCATION_SORT}</th>
    <th>Model {MODEL_SORT}</th>
    <th>Room Number</th>
    <th>Staff Member {STAFF_MEMBER_SORT}</th>
    <th>Purchase Date {PURCHASE_DATE_SORT}</th>
</tr>
<!-- BEGIN empty_table -->
<tr>
    <td colspan=20>{EMPTY_MESSAGE}</td>
</tr>
<!-- END empty_table -->
<!-- BEGIN listrows -->
<tr id=expander{ID} onMouseOver="this.style.backgroundColor='#EEEEEE'" onMouseOut="this.style.backgroundColor='#FFFFFF'" style="cursor:pointer;">
    <td>{DEPARTMENT}</td>
    <td>{LOCATION}</td>
    <td>{MODEL}</td>
    <td>{ROOM_NUMBER}</td>
    <td>{STAFF_MEMBER} ({USERNAME})</td>
    <td>{PURCHASE_DATE}</td>
</tr>
<tr>
    <td colspan=5>
        <!-- EVERYTHING THAT GETS EXPANDED -->
        <div class="expanded" id="expanded{ID}">
            <table border=0>
                <tr>
                    <td class="exptd"><strong>Hard Drive:</strong> {HDD}</td><td class="exptd"><strong>Processor:</strong> {PROC}</td><td class="exptd"><strong>RAM:</strong> {RAM}</td>
                </tr>
                <tr>
                    <td class="exptd"><strong>Dual Monitor?:</strong> {DUAL_MON}</td><td class="exptd"><strong>MAC Address:</strong> {MAC}</td><td colspan=3><strong>Reformat?:</strong> {REFORMAT}</td>
                </tr>
                <tr>
                    <td class="exptd"><strong>Printer:</strong> {PRINTER}</td><td class="exptd"><strong>Docking Stand?:</strong> {DOCKING_STAND}</td><td class="exptd"><strong>Deep Freeze?:</strong> {DEEP_FREEZE}</td>
                </tr>
                <tr>
                    <td class="exptd"><strong>Telephone:</strong> {TELEPHONE}</td><td class="exptd"><strong>Rotation:</strong> {ROTATION}</td><td class="exptd"><strong>VLAN:</strong> {VLAN}</td>
                </tr>
                <tr>
                    <td class="exptd" colspan=3><strong>Notes:</strong><br/>&nbsp;&nbsp;<div style="width:500px;">{NOTES}</div></td>
                </tr>
                <tr>
                    <td class="exptd" colspan=3><strong>{EDIT} | {DELETE}</strong></td>
                </tr>
            </table>
        </div>
    </td>
</tr>
<script type="text/javascript">
var hider_{ID} = new hideOther("expander{ID}", "expanded{ID}", true);
</script>
<!-- END listrows -->
</table>
<div align="center">
<br/><br/>
{TOTAL_ROWS} Results<br/>
<b>{PAGE_LABEL}</b><br/>
{PAGES}<br/>
{LIMITS}<br/><br/>
{CSV_REPORT}
</div>
