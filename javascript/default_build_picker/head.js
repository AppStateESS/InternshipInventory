<script type="text/javascript">
//<![CDATA[

function handle_build_change(){
    var selectedIndex = $('#add_system_build_id').attr('selectedIndex');

    if(selectedIndex == 0){
        $('#add_system_model').attr('value', '');
        $('#add_system_hdd').attr('value', '');
        $('#add_system_proc').attr('value', '');
        $('#add_system_ram').attr('value', '');
        $('#add_system_dual_mon').attr('checked',false);

    }else{
        $('#add_system_model').attr('value', data[selectedIndex].model);
        $('#add_system_hdd').attr('value', data[selectedIndex].hdd);
        $('#add_system_proc').attr('value', data[selectedIndex].proc);
        $('#add_system_ram').attr('value', data[selectedIndex].ram);
        if(data[selectedIndex].dual_mon == 'yes') {
            $('#add_system_dual_mon').attr('checked',true);
        }else{
            $('#add_system_dual_mon').attr('checked',false);
        }
    }
}

var json_data = {json_data};

var data = eval(json_data);

$(document).ready(function(){
        $('#add_system_build_id').bind('change', handle_build_change);
    });

//]]>
</script>
