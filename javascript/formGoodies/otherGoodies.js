/***********************
 * Form Save Handler
 * Prevents duplicate form submission.
 */
function setupFormSubmit()
{
    $("#internship").submit(formSubmitHandler);
}

function formSubmitHandler()
{
    // Disable the button
    $('button[type="submit"]').prop('disabled','disabled');

    $('button[type="submit"]').html('Saving... <i class="fa fa-spinner fa-spin"></i>');
}

function otherStuff()
{
    // Add error class to parent object (hopefully a div.form-group)
    // of any form element with the data-has-error="true" attribute.
    $("input[data-has-error='true']").parent().parent().addClass('has-error');
    $("select[data-has-error='true']").parent().parent().addClass('has-error');
    $("input[type='radio'][data-has-error='true']").parent().addClass('has-error');

    /* PAYMENT 
     * If Paid is selected then make stipend selectable.
     */
    $("input:radio[value='paid']").click(function(){
            $("input:checkbox[name='stipend']").prop('disabled', false);
            $("input:checkbox[name='stipend']").parent().removeClass('text-muted');
            });
    /* If unpaid is selected uncheck stipend and make it unselectable */
    $("input:radio[value='unpaid']").click(function(){
            $("input:checkbox[name='stipend']").prop('disabled', true);
            $("input:checkbox[name='stipend']").prop('checked', false);
            $("input:checkbox[name='stipend']").parent().addClass('text-muted');
            });

    /* Check whether to set stipend as disabled */
    if($("input:radio[value='paid']").prop('checked')){
        $("input:checkbox[name='stipend']").prop('disabled', false);
        $("input:checkbox[name='stipend']").parent().removeClass('text-muted');
    }else{
        $("input:checkbox[name='stipend']").prop('disabled', true);
        $("input:checkbox[name='stipend']").parent().addClass('text-muted');
    }

    /*******
     * OIED Checkbox
     * The OIED checkbox is shadowed by a hidden field. The hidden field
     * is always submitted with the form. However, the checkbox value will
     * not be submitted if the checkbox is 'disabled'.
     */

    $("#internship_oied_certified").change(function() {
        if($("#internship_oied_certified").prop('checked')){
            $("#internship_oied_certified_hidden").prop('value', 'true');
        }else{
            $("#internship_oied_certified_hidden").prop('value', 'false');
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
    // Set inital state for primary/secondar part checkboxes
    if($("#internship_multipart").prop('checked')){
        // disable secondary part check
        $("#internship_secondary_part").prop('disabled', false);
        $("#internship_secondary_part").parent().removeClass('text-muted');
    }else{
        $("#internship_secondary_part").prop('disabled', true);
        $("#internship_secondary_part").parent().addClass('text-muted');
    }

    // Bind event handler
    $("#internship_multipart").click(function(){
        if($("#internship_multipart").prop('checked')){
            // Enable secondary part
            $("#internship_secondary_part").prop('disabled', false);
            $("#internship_secondary_part").parent().removeClass('text-muted');
        }else{
            // Disable and clear secondary part
            $("#internship_secondary_part").prop('disabled', true);
            $("#internship_secondary_part").prop('checked', false);
            $("#internship_secondary_part").parent().addClass('text-muted');

            // Re-enable the course fields, in case they were disabled
            $("#internship_course_subj").prop('disabled', false);
            $("#internship_course_no").prop('disabled', false);
            $("#internship_course_sect").prop('disabled', false);
            $("#internship_credits").prop('disabled', false);
            $("#internship_course_title").prop('disabled', false);
        }
    });

    // Set inital state for course info fields
    if($("#internship_secondary_part").prop('checked')){
        // Disable course info
        $("#internship_course_subj").prop('disabled', true);
        $("#internship_course_no").prop('disabled', true);
        $("#internship_course_sect").prop('disabled', true);
        $("#internship_credits").prop('disabled', true);
        $("#internship_course_title").prop('disabled', true);
    }

    // Bind the event handler
    $("#internship_secondary_part").click(function(){
        if($("#internship_secondary_part").prop('checked')){
            // Disable course info
            $("#internship_course_subj").prop('disabled', true);
            $("#internship_course_no").prop('disabled', true);
            $("#internship_course_sect").prop('disabled', true);
            $("#internship_credits").prop('disabled', true);
            $("#internship_course_title").prop('disabled', true);
        }else{
            // Enable course info
            $("#internship_course_subj").prop('disabled', false);
            $("#internship_course_no").prop('disabled', false);
            $("#internship_course_sect").prop('disabled', false);
            $("#internship_credits").prop('disabled', false);
            $("#internship_course_title").prop('disabled', false);
        }
    });


    /********************
     * Faculty Selection
     */
    // Bind onChange event handler for department drop down
    $("#internship_department").bind('change', function(){
        // Reset state of interface
        $("#internship_faculty").prop('disabled', true); // Disable faculty drop down
        $("#internship_faculty").html("<option value='-1'></option>"); // Reset list of options in drop down

        $("#faculty_details").hide();
        $("#faculty_email").html('');
        $("#faculty_phone").html('');
        $("#faculty_fax").html('');
        $("#faculty_address").html('');

        if($("#internship_department").val() === -1){
            return;
        }

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
function handleFacultyResponse(data)
{
    //console.log(data);

    // Save the data outside this method for later
    facultyData = data;

    // Show a message if no faculty were returned for the selected department
    if(data.length === 0){
        $("#internship_faculty").html("<option value='-1'>No Advisors Available</option>");
        return;
    }

    // Generate the dropdown list
    var listItems = "<option value='-1'>None</option>";
    for (var i = 0; i < data.length; i++){
        // If the banner ID matches what's in the hidden field, set the selected option in the drop down
        var selected = '';
        if($("#internship_faculty_id").val() === data[i].id){
            selected = 'selected="selected"';
        }
        listItems += "<option value='" + data[i].id + "' " + selected + ">" + data[i].first_name + " " + data[i].last_name + "</option>";
    }
    $("#internship_faculty").html(listItems);
    $("#internship_faculty").prop('disabled', false);

    // If a banner id is already set in the hidden field, select it
    if ($("#internship_faculty_id").val() !== null) {
        selectFaculty($("#internship_faculty_id").val());
    }
}

    // Handle an AJAX error when getting faculty members for a department
    function handleFacultyReponseError(jqXHR, textStatus)
    {
        console.log("Error loading facuty list. Please contact ESS.");
        console.log(textStatus);
    }

    // Trigger a change for the inital loading of faculty info
    $("#internship_department").change(); // Trigger an initial update


    // Handle changes to the faculty drop down
    $("#internship_faculty").bind('change', function(){
        if($("#internship_faculty").val() !== "-1") {
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
            if(facultyData[i].id === bannerId){
                faculty = facultyData[i];
                break;
            }
        }

        // Update the faculty details panel
        var departmentName = $("#internship_department :selected").text();

        $("#faculty_details").removeClass('text disabled'); // Disable detail text
        $("#faculty_name").html(faculty.first_name + " " + faculty.last_name + " - " + departmentName);
        $("#faculty_email").html('<a href="mailto:' + faculty.username + '@appstate.edu">' + faculty.username + '@appstate.edu </a>');

        if(faculty.phone !== ''){
            $("#faculty_phone").html('<a href="tel:+1' + faculty.phone + '">' + faculty.phone + '</a>');
        }else{
            $("#faculty_phone").html('<small class="text-muted">Has not been set</small>');
        }

        if(faculty.fax !== '' && faculty.fax !== null){
            $("#faculty_fax").html('<a href="fax:+1' + faculty.fax + '">' + faculty.fax + '</a>');
        }else{
            $("#faculty_fax").html('<small class="text-muted">Has not been set</small>');
        }

        // Format the address
        var address = '';
            if(faculty.street_address1 !== '' && faculty.street_address1 !== null){
                address += faculty.street_address1;

                if (faculty.street_address2 !== '') {
                    address += ("<br />" + faculty.street_address2); 
                }
            } else {
                address += ('<small class="text-muted">Address has not been set</small>');
            }
        if(faculty.city !== '' && faculty.city !== null && faculty.state !== '' && faculty.state !== null){
            address += ("<br />" + faculty.city + ", " + faculty.state);
        }
        if(faculty.zip !== '' && faculty.zip !== null) {
            address += " " + faculty.zip;
        }
        $("#faculty_address").html(address);

        // Slide the faculty selector div (drop downs) out, then slide the faculty details panel in
        $("#faculty_selector").hide('slide', {direction: 'left'}, "fast", function(){
                $("#faculty_details").show('slide', {direction: 'right'}, "fast");
                });
    }

/***********************************************
 * Location Domestic vs International handling *
 ***********************************************/
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

    // Change the postal code label to 'zip'
    $("#internship_loc_zip-label").text("Zip");


    /****** Agency Location ********/
    // Show the state dropdown
    $("#internship_agency_state").parent().parent().fadeIn('fast');

    // Hide the province and country fields
    $("#internship_agency_province").parent().parent().fadeOut('fast');
    $("#internship_agency_country").parent().parent().fadeOut('fast');

    // Change the postal code label to 'zip code'
    $("#internship_agency_zip-label").text("Zip");

    /****** Agency Supervisor ******/
    // Show the state dropdown
    $("#internship_agency_sup_state").parent().parent().fadeIn('fast');

    // Hide the province and country fields
    $("#internship_agency_sup_province").parent().parent().fadeOut('fast');
    $("#internship_agency_sup_country").parent().parent().fadeOut('fast');

    // Change the postal code label to 'zip'
    $("#internship_agency_sup_zip-label").text("Zip");

    /****** Other Fields ********/
    // Disable the OIED checkbox
    $("#internship_oied_certified").prop('disabled', true);
    $("#internship_oied_certified-label").addClass('text-muted');

    // Re-add handlers for copying state info. <mod/intern/javascript/copyAddress>
    //sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
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


    /********** Agency Location *******************/
    // Hide the state dropdown
    $("#internship_agency_state").parent().parent().fadeOut('fast');

    // Show the province and country fields
    $("#internship_agency_province").parent().parent().fadeIn('fast');
    $("#internship_agency_country").parent().parent().fadeIn('fast');

    // Change the zip code label to 'postal code'
    $("#internship_agency_zip-label").text("Postal Code");


    /********* Agency Supervisor Location ***********/
    // Hide the state dropdown
    $("#internship_agency_sup_state").parent().parent().fadeOut('fast');

    // Show the province and country fields
    $("#internship_agency_sup_province").parent().parent().fadeIn('fast');
    $("#internship_agency_sup_country").parent().parent().fadeIn('fast');

    // Change the zip code label to 'postal code'
    $("#internship_agency_sup_zip-label").text("Postal Code");

    /****** Other Fields ********/
    // Enable the OIED checkbox
    $("#internship_oied_certified").prop('disabled', false);
    $("#internship_oied_certified-label").removeClass('text-muted');

    //sameAddressState($("input:checkbox[name='copy_address']").attr('checked'));
};

// Attach above function to click event
$("input:radio[name=location][value=domestic]").click(function(){ domesticClick(); });

// Attach above function to click event
$("input:radio[name=location][value=internat]").click(function(){ internatClick(); });

// If domestic is checked initially then do setup...
if($("input:radio[name=location][value=domestic]").attr('checked')){
    domesticClick();
}

// If internat is checked initially then do setup...
if($("input:radio[name=location][value=internat]").attr('checked')){
    internatClick();
}

/******************************************
 * Undergraduate Level and Major handling *
 ******************************************/

// Set initial state
$("#internship_ugrad_major").hide();
$("#internship_grad_prog").hide();

// Set initial state for student level drop down by calling the
// usual event handler if needed
if($("#internship_student_level").val() != -1){
    handleLevelChange();
}

// Event handler for student level drop down
function handleLevelChange()
{
    if($("#internship_student_level").val() === 'ugrad'){
        $("#internship_grad_prog").hide();
        $("#internship_student_major").hide();
        $("#internship_ugrad_major").show();
    }else if ($("#internship_student_level").val() === 'grad'){
        $("#internship_ugrad_major").hide();
        $("#internship_student_major").hide();
        $("#internship_grad_prog").show();
    }else {
        $("#internship_ugrad_major").hide();
        $("#internship_grad_prog").hide();
        $("#internship_student_major").show();
    }
}

// Bind event handler for drop down change
$("#internship_student_level").change(handleLevelChange);
}
