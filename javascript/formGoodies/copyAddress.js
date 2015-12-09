function copyAddress(){

    function doCopySupervisor(){
        $("#internship_agency_sup_address").val($("#internship_agency_address").val());
        $("#internship_agency_sup_city").val($("#internship_agency_city").val());
        $("#internship_agency_sup_state").val($("#internship_agency_state").val());
        $("#internship_agency_sup_zip").val($("#internship_agency_zip").val());
        $("#internship_agency_sup_province").val($("#internship_agency_province").val());
        $("#internship_agency_sup_country").val($("#internship_agency_country").val());
        $("#internship_agency_sup_phone").val($("#internship_agency_phone").val());
    }

    function addHandlersSupervisor(){
        $("#internship_agency_address").keyup(doCopySupervisor);
        $("#internship_agency_city").keyup(doCopySupervisor);
        $("#internship_agency_state").change(doCopySupervisor);
        $("#internship_agency_zip").keyup(doCopySupervisor);
        $("#internship_agency_province").keyup(doCopySupervisor);
        $("#internship_agency_country").keyup(doCopySupervisor);
    }

    function removeHandlersSupervisor(){
        // Remove handlers
        $("#internship_agency_address").unbind('keyup');
        $("#internship_agency_city").unbind('keyup');
        $("#internship_agency_state").unbind('change');
        $("#internship_agency_zip").unbind('keyup');
        $("#internship_agency_province").unbind('keyup');
        $("#internship_agency_country").unbind('keyup');
    }

    function doCopyAgency(){
        $("#internship_agency_address").val($("#internship_loc_address").val());
        $("#internship_agency_city").val($("#internship_loc_city").val());
        $("#internship_agency_state").val(internship.loc_state);
        $("#internship_agency_zip").val($("#internship_loc_zip").val());
        $("#internship_agency_province").val($("#internship_loc_province").val());
        $("#internship_agency_country").val(internship.loc_country);
    }

    function addHandlersAgency(){
        $("#internship_loc_address").keyup(doCopyAgency);
        $("#internship_loc_city").keyup(doCopyAgency);
        $("#internship_loc_state").change(doCopyAgency);
        $("#internship_loc_zip").keyup(doCopyAgency);
        $("#internship_loc_province").keyup(doCopyAgency);
        $("#internship_loc_country").keyup(doCopyAgency);
    }

    function removeHandlersAgency(){
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
    $("#internship_copy_address_agency").change(function(){
        if($("#internship_copy_address_agency").prop('checked')){
            // Same address box was checked, so copy the address
            doCopyAgency();
            // Setup the event handlers for copying later changes
            addHandlersAgency();
        }else{
            // Box was unchecked, so remove event handlers for later changes
            removeHandlersAgency();
        }
    });

    // Set initial state
    if($("#internship_copy_address").prop('checked')){
        addHandlersSupervisor();
    }

    // Set initial state
    if($("#internship_copy_address_agency").prop('checked')){
        addHandlersAgency()();
    }
};
