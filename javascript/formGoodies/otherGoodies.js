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

    /*******
     * OIED Checkbox
     * The OIED checkbox is shadowed by a hidden field. The hidden field
     * is always submitted with the form. However, the checkbox value will
     * not be submitted if the checkbox is 'disabled'.
     */
    
    $("#internship_oied_certified").change(function() {
    	if($("#internship_oied_certified").attr('checked')){
    		$("#internship_oied_certified_hidden").val('true');
    	}else{
    		$("#internship_oied_certified_hidden").val('false');
    	}
    });
    
    /************************
     * Internship Type Help *
     */
    // Setup the dialog
    $("#internship-type-help").dialog({
    	autoOpen: false,
    	buttons: {Close: function() {$(this).dialog("close");}},
    	modal: true,
    	width: 600
    });
    
    // Register the event handler for the help button that shows the dialog
    $("#internship-type-help-button").click(function(){
    	$("#internship-type-help").dialog('open');
    });
    
    /**********
     * Multi-part / Secondary Part *
     */
    // Set inital state
    if($("#internship_multipart").attr('checked')){
    	// disable secondary part check
    	$("#internship_secondary_part").attr('disabled', false);
    }else{
    	$("#internship_secondary_part").attr('disabled', true);
    }
    
    // Bind event handler
    $("#internship_multipart").click(function(){
    	if($("#internship_multipart").attr('checked')){
    		// Enable secondary part
    		$("#internship_secondary_part").attr('disabled', false);
    	}else{
    		// Disable and clear secondary part
    		$("#internship_secondary_part").attr('disabled', true);
    		$("#internship_secondary_part").attr('checked', false);
    		// Re-enable the course fields, in case they were disabled
    		$("#internship_course_subj").attr('disabled', false);
    		$("#internship_course_no").attr('disabled', false);
    		$("#internship_course_sect").attr('disabled', false);
    		$("#internship_course_title").attr('disabled', false);
    	}
    });
    
    // Set inital state
    if($("#internship_secondary_part").attr('checked')){
    	// Disable course info
    	$("#internship_course_subj").attr('disabled', true);
		$("#internship_course_no").attr('disabled', true);
		$("#internship_course_sect").attr('disabled', true);
		$("#internship_course_title").attr('disabled', true);
    }
    
    // Bind the event handler
    $("#internship_secondary_part").click(function(){
    	if($("#internship_secondary_part").attr('checked')){
    		// Disable course info
    		$("#internship_course_subj").attr('disabled', true);
    		$("#internship_course_no").attr('disabled', true);
    		$("#internship_course_sect").attr('disabled', true);
    		$("#internship_course_title").attr('disabled', true);
    	}else{
    		// Enable course info
    		$("#internship_course_subj").attr('disabled', false);
    		$("#internship_course_no").attr('disabled', false);
    		$("#internship_course_sect").attr('disabled', false);
    		$("#internship_course_title").attr('disabled', false);
    	}
    });
    
    
    /********************
     * Faculty Selection
     */
    // Bind onChange event handler for department drop down
    $("#internship_department").bind('change', function(event){
    	// Reset state of interface
    	$("#internship_faculty").prop('disabled', true); // Disable faculty drop down
    	$("#internship_faculty").html("<option value='-1'>Loading...</option>"); // Reset list of options in drop down
    	
    	$("#faculty_details").hide();
    	$("#faculty_email").html('');
    	$("#faculty_phone").html('');
    	$("#faculty_fax").html('');
    	$("#faculty_address").html('');
    	
    	// Make the request for the list of faculty members for the selected department
    	$.ajax({
    		success: handleFacultyResponse,
    		error: handleFacultyReponseError,
    		data: {module: 'intern',
    			   action: 'getFacultyListForDept',
    			   department: $("#internship_department").val()
    			   },
            dataType: 'json',
    	    url: 'index.php'
    	});
    });
    
    var facultyData = null;
    
    // Handle the AJAX response containing the faculty members for a department
    function handleFacultyResponse(data, textStatus, jqXHR)
    {
    	//console.log(data);
    	
    	// Save the data outside this method for later
    	facultyData = data;
    	
    	// Show a message if no faculty were returned for the selected department
    	if(data.length == 0){
    		$("#internship_faculty").html("<option value='-1'>No Advisors Available</option>");
    		return;
    	}
    	
    	// Generate the dropdown list
        var listItems = "<option value='-1'>None</option>";
        for (var i = 0; i < data.length; i++){
        	// If the banner ID matches what's in the hidden field, set the selected option in the drop down
        	selected = '';
        	if($("#internship_faculty_id").val() == data[i].id){
        		selected = 'selected="selected"';
        	}
            listItems += "<option value='" + data[i].id + "' " + selected + ">" + data[i].first_name + " " + data[i].last_name + "</option>";
        }
        $("#internship_faculty").html(listItems);
        $("#internship_faculty").prop('disabled', false);
        
        // If a banner id is already set in the hidden field, select it
        if ($("#internship_faculty_id").val() != null) {
        	selectFaculty($("#internship_faculty_id").val());
        }
    }
    
    // Handle an AJAX error when getting faculty members for a department
    function handleFacultyReponseError(jqXHR, textStatus, errorThrown)
    {
    	console.log("Error loading facuty list. Please contact ESS.");
    	console.log(textStats);
    }
    
    // Trigger a change for the inital loading of faculty info
    $("#internship_department").change(); // Trigger an initial update
    
    // Handle changes to the faculty drop down
    $("#internship_faculty").bind('click', function(){
    	if($("#internship_faculty").val() != "-1") {
    		selectFaculty($("#internship_faculty").val());
    	}
    });
    
    // Change link click handler
    $("#faculty-change").bind('click', function(){
    	// Reset the selected banner_id in the hidden field
    	$("#internship_faculty_id").val(null);
    	
    	// Slide the faculty details panel out, and the faculty selector panel in
    	$("#faculty_details").hide('slide', {direction: 'right'}, "fast", function(){
    		$("#faculty_selector").show('slide', {direction: 'left'}, "fast");
    	});
    });
    
    function selectFaculty(bannerId)
    {
    	// Store the selected faculty banner id in the hidden field
    	$("#internship_faculty_id").val(bannerId);
    	
    	// Search the list of faculty for a match to the JSON data fetched earlier
    	//TODO What if there isn't a match? We still need to be able to find/show that faculty member.
    	var faculty = null;
    	for(var i = 0; i < facultyData.length; i++){
    		if(facultyData[i].id == bannerId){
    			faculty = facultyData[i];
    			break;
    		}
    	}
    	
    	// Update the faculty details panel
    	departmentName = $("#internship_department :selected").text();
    	
    	$("#faculty_details").removeClass('text disabled'); // Disable detail text
    	$("#faculty_name").html(faculty.first_name + " " + faculty.last_name + " - " + departmentName);
    	$("#faculty_email").html('<a href="mailto:' + faculty.username + '@appstate.edu">' + faculty.username + '@appstate.edu </a>');
    	
    	if(faculty.phone != ''){
    	    $("#faculty_phone").html('<a href="tel:+1' + faculty.phone + '">' + faculty.phone + '</a>');
    	}else{
    	    $("#faculty_phone").html('<span class="text disabled italic">has not been set</span>');
    	}
    	
    	if(faculty.fax != ''){
    	    $("#faculty_fax").html('<a href="fax:+1' + faculty.fax + '">' + faculty.fax + '</a>');
    	}else{
    	    $("#faculty_fax").html('<span class="text disabled italic">has not been set</span>');
    	}

    	// Format the address
    	var address = ''
    	if(faculty.street_address1 != ''){
    	    address += faculty.street_address1;
    	    
    	    if (faculty.street_address2 != '') {
                address += ("<br />" + faculty.street_address2); 
            }
    	} else {
    	    address += ('<span class="text disabled italic">has not been set</span>');
    	}
    	if(faculty.city != '' && faculty.state != ''){
    	    address += ("<br />" + faculty.city + ", " + faculty.state);
    	}
    	if(faculty.zip != '') {
    	    address += " " + faculty.zip;
    	}
    	$("#faculty_address").html(address);
    	
    	// Slide the faculty selector div (drop downs) out, then slide the faculty details panel in
    	$("#faculty_selector").hide('slide', {direction: 'left'}, "fast", function(){
    		$("#faculty_details").show('slide', {direction: 'right'}, "fast");
    	});
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
