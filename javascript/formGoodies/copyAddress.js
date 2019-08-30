function copyAddress(){

    //copy from Host Details to Supervisor Info
    function doCopySupervisor(){
        $("#internship_supervisor_address").val($("#internship_host_address").val());
        $("#internship_supervisor_city").val($("#internship_host_city").val());
        $("#internship_supervisor_state").val($("#internship_host_state").val());
        $("#internship_supervisor_zip").val($("#internship_host_zip").val());
        $("#internship_supervisor_province").val($("#internship_host_province").val());
        $("#internship_supervisor_country").val($("#internship_host_country").val());
        $("#internship_supervisor_phone").val($("#internship_host_phone").val());
    }

    function addHandlersSupervisor(){
        $("#internship_host_address").keyup(doCopySupervisor);
        $("#internship_host_city").keyup(doCopySupervisor);
        $("#internship_host_state").change(doCopySupervisor);
        $("#internship_host_zip").keyup(doCopySupervisor);
        $("#internship_host_province").keyup(doCopySupervisor);
        $("#internship_host_country").keyup(doCopySupervisor);
    }

    function removeHandlersSupervisor(){
        // Remove handlers
        $("#internship_host_address").unbind('keyup');
        $("#internship_host_city").unbind('keyup');
        $("#internship_host_state").unbind('change');
        $("#internship_host_zip").unbind('keyup');
        $("#internship_host_province").unbind('keyup');
        $("#internship_host_country").unbind('keyup');
    }

    // Bind event handler for "same address" checkbox
    $("#internship_copy_address").change(function(){
        if($("#internship_copy_address").prop('checked')){
            // Same address box was checked, so copy the address
            doCopySupervisor();
            // Setup the event handlers for copying later changes
            addHandlersSupervisor();
        }else{
            // Box was unchecked, so remove event handlers for later changes
            removeHandlersSupervisor();
        }
    });

    // Set initial state
    if($("#internship_copy_address").prop('checked')){
        addHandlersSupervisor();
    }
};
