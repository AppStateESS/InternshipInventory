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

    //copy from Location to Host Details
    function doCopyHost(){
        $("#internship_host_address").val($("#internship_loc_address").val());
        $("#internship_host_city").val($("#internship_loc_city").val());
        $("#internship_host_state").val(internship.loc_state);
        $("#internship_host_zip").val($("#internship_loc_zip").val());
        $("#internship_host_province").val($("#internship_loc_province").val());
        $("#internship_host_country").val(internship.loc_country);
    }

    function addHandlersHost(){
        $("#internship_loc_address").keyup(doCopyHost);
        $("#internship_loc_city").keyup(doCopyHost);
        $("#internship_loc_state").change(doCopyHost);
        $("#internship_loc_zip").keyup(doCopyHost);
        $("#internship_loc_province").keyup(doCopyHost);
        $("#internship_loc_country").keyup(doCopyHost);
    }

    function removeHandlersHost(){
        // Remove handlers
        $("#internship_loc_address").unbind('keyup');
        $("#internship_loc_city").unbind('keyup');
        $("#internship_loc_state").unbind('change');
        $("#internship_loc_zip").unbind('keyup');
        $("#internship_loc_province").unbind('keyup');
        $("#internship_loc_country").unbind('keyup');
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

    // Bind event handler for "same address" checkbox
    $("#internship_copy_address_host").change(function(){
        if($("#internship_copy_address_host").prop('checked')){
            // Same address box was checked, so copy the address
            doCopyHost();
            // Setup the event handlers for copying later changes
            addHandlersHost();
        }else{
            // Box was unchecked, so remove event handlers for later changes
            removeHandlersHost();
        }
    });

    // Set initial state
    if($("#internship_copy_address").prop('checked')){
        addHandlersSupervisor();
    }

    // Set initial state
    if($("#internship_copy_address_host").prop('checked')){
        addHandlersHost()();
    }
};
