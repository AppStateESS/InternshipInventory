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
        var index = ($('#add_system_build_id').attr('options'))[selectedIndex].value;
        $('#add_system_model').attr('value', data[index].model);
        $('#add_system_hdd').attr('value', data[index].hdd);
        $('#add_system_proc').attr('value', data[index].proc);
        $('#add_system_ram').attr('value', data[index].ram);
        if(data[index].dual_mon == 'yes') {
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
