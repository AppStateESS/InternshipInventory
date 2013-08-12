function copyAddress(){

    function doCopy(){
        $("#internship_agency_sup_address").val($("#internship_agency_address").val());
        $("#internship_agency_sup_city").val($("#internship_agency_city").val());
        $("#internship_agency_sup_state").val($("#internship_agency_state").val());
        $("#internship_agency_sup_zip").val($("#internship_agency_zip").val());
        $("#internship_agency_sup_province").val($("#internship_agency_province").val());
        $("#internship_agency_sup_country").val($("#internship_agency_country").val());
    }

    function addHandlers(){
        $("#internship_agency_address").keyup(doCopy);
        $("#internship_agency_city").keyup(doCopy);
        $("#internship_agency_state").change(doCopy);
        $("#internship_agency_zip").keyup(doCopy);
        $("#internship_agency_province").keyup(doCopy);
        $("#internship_agency_country").keyup(doCopy);
    }
    
    function removeHandlers(){
        /* Remove handlers */
        $("#internship_agency_address").unbind('keyup');
        $("#internship_agency_city").unbind('keyup');
        $("#internship_agency_state").unbind('change');
        $("#internship_agency_zip").unbind('keyup');
        $("#internship_agency_province").unbind('keyup');
        $("#internship_agency_country").unbind('keyup');
    }
    
    // Bind event handler for "same address" checkbox
    $("#internship_copy_address").change(function(){
        if($("#internship_copy_address").prop('checked')){
            // Same address box was checked, so copy the address
            doCopy();
            // Setup the event handlers for copying later changes
            addHandlers();
        }else{
            // Box was unchecked, so remove event handlers for later changes
            removeHandlers();
        }
    });
    
    // Set initial state
    if($("#internship_copy_address").prop('checked')){
        addHandlers();
    }
};