/**
 * Since we are messing around with the state
 * fields we have to do some of the job that 
 * coypAddress.js does.
 * If sameAddress == true then the 
 * 'Same Address' checkbox is clicked.
 */
function sameAddressState(sameAddress)
{
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

function setupFormSubmit()
{
	$("#internship").submit(formSubmitHandler);
}

function formSubmitHandler()
{
	// Disable the button
	$('input[type="submit"]').attr('disabled','disabled');
	
	// Setup the spinner
	var opts = {
			  lines: 11, // The number of lines to draw
			  length: 6, // The length of each line
			  width: 2, // The line thickness
			  radius: 5, // The radius of the inner circle
			  rotate: 0, // The rotation offset
			  color: '#000', // #rgb or #rrggbb
			  speed: 1, // Rounds per second
			  trail: 60, // Afterglow percentage
			  shadow: false, // Whether to render a shadow
			  hwaccel: false, // Whether to use hardware acceleration
			  className: 'spinner', // The CSS class to assign to the spinner
			  zIndex: 2e9, // The z-index (defaults to 2000000000)
			  top: 'auto', // Top position relative to parent in px
			  left: 'auto' // Left position relative to parent in px
			};
	var spinner = new Spinner(opts).spin();
	$('input[type="submit"]').after(spinner.el);
	
	return;
}

function otherStuff()
{
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

    /*
     * Location stuff.
     *
     * If domestic is clicked then change labels appropriately.
     */
    var domesticClick = function(){
    	/**** Internship Location ****/
    	// Show the state dropdown, add require field class
        $("#internship_loc_state").parent().parent().fadeIn('fast');
        $("#internship_loc_state").addClass('input-required');
        
        // Hide the province and country fields
        $("#internship_loc_province").parent().parent().fadeOut('fast');
        $("#internship_loc_country").parent().parent().fadeOut('fast');
        
        // Remove required class to country (internship location)
        $("#internship_loc_country").removeClass('input-required');
        
        
        /****** Other Fields ***********/
        
        // Change province/territory label to say state.
        $("#internship_agency_state-label,#internship_agency_sup_state-label").text("State");
        // Swap out province textbox for state drop down
        $("#internship_agency_state").parent().html(stateSelect);
        $("#internship_agency_sup_state").parent().html(supStateSelect);
        //$("#internship_loc_state").parent().html(locStateSelect);

        // Change postal code label to say zip.
        $("#internship_agency_zip-label,#internship_agency_sup_zip-label,#internship_loc_zip-label").text("Zip Code");

        // Remove requirement class from country (agency and supervisor)
        $("#internship_loc_country").removeClass('input-required');
        
        // Hide country
        $("#internship_agency_country,#internship_agency_sup_country").fadeOut('fast');
        // Hide labels too.
        $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().fadeOut('fast');
        
        // Make the state field required again
        //$("#internship_loc_state").addClass('input-required');
        //$("#internship_loc_state-label").append('<span class="required-input">*</span>');
        
        // Re-add handlers for copying state info. <mod/intern/javascript/copyAddress>
        sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
        
        // Disable OIED certification checkbox
        $("#internship_oied_certified").attr('disabled', true);
    };

    /**
     * If internat is selected: show country. Add required flag to country.
     */
    var internatClick = function(){
    	
    	/**** Internship Location ****/
        // Hide state dropdown, remove required field class
        $("#internship_loc_state").removeClass('input-required');
        $("#internship_loc_state").parent().parent().fadeOut('fast');
        
        // Show the province and country fields
        $("#internship_loc_province").parent().parent().fadeIn('fast');
        $("#internship_loc_country").parent().parent().fadeIn('fast');
        
        // Add required class to country (internship location)
        $("#internship_loc_country").addClass('input-required');
        
        // Change the zip code label to 'postal code'
        $("#internship_loc_zip-label").text("Postal Code");
        
        /********** Other Fields *******************/
        
        // Change state to say province/territory.
    	$("#internship_agency_state-label,#internship_agency_sup_state-label").text("Province/Territory");
    	
        // Create elements if they don't exist
        if(territory == undefined || supTerritory == undefined){
            territory = $("<input type='text' name='agency_state' id='internship_agency_state'>");
            supTerritory = $("<input type='text' name='agency_sup_state' id='internship_agency_sup_state'>");
            $(territory).val($("#internship_agency_state").attr("where"));
            $(supTerritory).val($("#internship_agency_sup_state").attr("where"));
        }
        
        // Swap state drop down for a text box
        $("#internship_agency_state").parent().html(territory);
        $("#internship_agency_sup_state").parent().html(supTerritory);

        // Change zip code to say postal code
        $("#internship_agency_zip-label,#internship_agency_sup_zip-label").text("Postal Code");

        //$("#internship_loc_country-label").append('<span class="required-input">*</span>');
        // Show countries
        $("#internship_agency_country,#internship_agency_sup_country").fadeIn('fast');
        // Show labels too.
        $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().fadeIn('fast');
        sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
        
        // Enable OIED certification checkbox
        $("#internship_oied_certified").attr('disabled', false);
    };

    /* Attach above function to click event */
    $("input:radio[name=location][value=domestic]").click(function(){ domesticClick(); });

    /* Attach above function to click event */
    $("input:radio[name=location][value=internat]").click(function(){ internatClick(); });
    
    // Save select box in variable..will be swapped a lot (possibly) later...
    var stateSelect = $("select[name=agency_state]");
    var supStateSelect = $("select[name=agency_sup_state]");
    var locStateSelect = $("select[name=loc_state]");
    var territory = undefined;
    var supTerritory = undefined;
    var locTerritory = undefined;
    
    // If domestic is checked initially then do setup...
    if($("input:radio[name=location][value=domestic]").attr('checked')){
        domesticClick();
    }

    // If internat is checked initially then do setup...
    if($("input:radio[name=location][value=internat]").attr('checked')){
        internatClick();
    }

    /* Undergraduate Level and Major handling */
    
    var ugradClick = function()
    {
    	$("#grad_drop").hide();
    	$("#ugrad_drop").show();
    };
    
    var gradClick = function()
    {
    	$("#ugrad_drop").hide();
    	$("#grad_drop").show();
    };
    
    // Bind event handler for radio button change
    $("#internship_student_level_ugrad").click(function(){ugradClick();});
    $("#internship_student_level_grad").click(function(){gradClick();});
    
    // Set initial state for student level radio button
    if($("#internship_student_level_ugrad").attr('checked')){
    	ugradClick();
    }else if($("#internship_student_level_grad").attr('checked')){
    	gradClick();
    }else{
    	// Nothing is checked, hide both
    	$("#ugrad_drop").hide();
    	$("#grad_drop").hide();
    }
};
