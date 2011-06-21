/**
 * Since we are messing around with the state
 * fields we have to do some of the job that 
 * coypAddress.js does.
 * If sameAddress == true then the 
 * 'Same Address' checkbox is clicked.
 */
function sameAddressState(sameAddress){
    $("#internship_agency_state").unbind('change');
    $("#internship_agency_state").unbind('keyup');

    if(sameAddress){
        // Re-add handlers
        $("#internship_agency_state").change(function(){
            $("#internship_agency_sup_state").val($("#internship_agency_state").val()).change();
        });
        $("#internship_agency_state").keyup(function(){
            $("#internship_agency_sup_state").val($("#internship_agency_state").val());
        });
    }
};

function otherStuff(){
    /* PAYMENT 
     * If Paid is selected then make stipend selectable.
     */
    $("input:radio[value='paid']").click(function(){
        $("input:checkbox[name='stipend']").attr('disabled', false);
    });
    /* If unpaid is selected uncheck stipend and make it unselectable */
    $("input:radio[value='unpaid']").click(function(){
        $("input:checkbox[name='stipend']").attr('disabled', true);
        $("input:checkbox[name='stipend']").attr('checked', false);
    });
    
    /* Check whether to set stipend as disabled */
    if($("input:radio[value='paid']").attr('checked')){
        $("input:checkbox[name='stipend']").attr('disabled', false);
    }else{
        $("input:checkbox[name='stipend']").attr('disabled', true);
    }
    
    /* 'OTHER' INTERNSHIP TYPE 
     * If checkbox beside the 'Other type' text-box is selected then
     * enable the text-box 
     */
    $("input:checkbox[name='check_other_type']").click(function(){
        if($(this).attr('checked')){
            $("input:text[name='other_type']").attr('disabled', false);
        }else{
            $("input:text[name='other_type']").attr('disabled', true);
        }
    });
    /* Should the text box be initialized to disabled? */
    if($("input:checkbox[name='check_other_type']").attr('checked')){
        $("input:text[name='other_type']").attr('disabled', false);
    }else{
        $("input:text[name='other_type']").attr('disabled', true);
    }

    /*
     * Location stuff.
     *
     * If domestic is clicked then change labels appropriately.
     */
    var domesticClick = function(){
        // Change province/territory label to say state.
        $("#internship_agency_state-label,#internship_agency_sup_state-label").text("State");
        // Swap out province textbox for state drop down
        $("#internship_agency_state").parent().html(stateSelect);
        $("#internship_agency_sup_state").parent().html(supStateSelect);

        // Change postal code label to say zip.
        $("#internship_agency_zip-label,#internship_agency_sup_zip-label").text("Zip Code");

        // Remove requirement class from country (agency and supervisor)
        $("#internship_agency_country,#internship_agency_sup_country").removeClass('input-required');
        // Hide country
        $("#internship_agency_country,#internship_agency_sup_country").fadeOut('fast');
        // Hide labels too.
        $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().fadeOut('fast');
        
        // Re-add handlers for copying state info. <mod/intern/javascript/copyAddress>
        sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
    };

    /**
     * If internat is selected: show country. Add required flag to country.
     */
    var internatClick = function(){
        // Change state to stay province/territory.
        $("#internship_agency_state-label,#internship_agency_sup_state-label").text("Province/Territory");

        // Create elements if they don't exist
        if(territory == undefined || supTerritory == undefined){
            territory = $("<input type='text' name='agency_state' id='internship_agency_state' class='input-required'>");
            supTerritory = $("<input type='text' name='agency_sup_state' id='internship_agency_sup_state' class='input-required'>");
            $(territory).val($("#internship_agency_state").attr("where"));
            $(supTerritory).val($("#internship_agency_sup_state").attr("where"));
        }
        // Swap state drop down for a text box
        $("#internship_agency_state").parent().html(territory);
        $("#internship_agency_sup_state").parent().html(supTerritory);

        // Change zip code to say postal code
        $("#internship_agency_zip-label,#internship_agency_sup_zip-label").text("Postal Code");

        // Add requirement class from country (agency and supervisor)
        $("#internship_agency_country,#internship_agency_sup_country").addClass('input-required');
        // Show countrys
        $("#internship_agency_country,#internship_agency_sup_country").fadeIn('fast');
        // Show labels too.
        $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().fadeIn('fast');
        sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
    };

    /* Attach above function to click event */
    $("input:radio[name=location][value=domestic]").click(function(){ domesticClick(); });

    /* Attach above function to click event */
    $("input:radio[name=location][value=internat]").click(function(){ internatClick(); });
    
    // Save select box in variable..will be swapped a lot (possibly) later...
    var stateSelect = $("select[name=agency_state]");
    var supStateSelect = $("select[name=agency_sup_state]");
    var territory = undefined;
    var supTerritory = undefined;
    
    // If domestic is checked initially then do setup...
    if($("input:radio[name=location][value=domestic]").attr('checked')){
        domesticClick();
    }

    // If internat is checked initially then do setup...
    if($("input:radio[name=location][value=internat]").attr('checked')){
        internatClick();
    }

};
