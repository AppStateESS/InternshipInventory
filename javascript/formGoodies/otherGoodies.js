/***********************
 * Form Save Handler
 * Prevents duplicate form submission.
 */
function setupFormSubmit()
{
    $('#internship').submit(formSubmitHandler);
}

function formSubmitHandler()
{
    // Disable the button
    $('button[type="submit"]').prop('disabled','disabled');

    $('button[type="submit"]').html('Saving... <i class="fa fa-spinner fa-spin"></i>');
}

function setupContractButton()
{
    $('.generateContract').click(generateContractHandler);
}

function generateContractHandler()
{
    // Disable the button
    $('.generateContract').prop('disabled', 'disabled');

    // Change the button text
    $('.generateContract').html('<i class="fa fa-spinner fa-spin"></i> Generating...');
    if(!approved){
        window.location = 'index.php?module=intern&action=pdf&internship_id=' + toGenId;
    } else {
        $.post('index.php?module=intern&action=SaveInternship', $('#internship').serialize() + '&generateContract=true',
        function(data){
            window.location = 'index.php?module=intern&action=pdf&internship_id=' + data.id;
        },
        'json');
    }
}

function addFormErrors()
{
    // Add error class to parent object (hopefully a div.form-group)
    // of any form element with the data-has-error="true" attribute.
    $("input[data-has-error='true']").parent().parent().addClass('has-error');
    $("select[data-has-error='true']").parent().parent().addClass('has-error');
    $("input[type='radio'][data-has-error='true']").parent().addClass('has-error');
}

function initPayment()
{
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
}

function initOIED()
{
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
}

function initRemote(){
    /**********
     * Remote State *
     */
    // Set inital state for checkbox
    if($("#internship_remote").prop('checked')){
        $("#internship_remote_state_label").toggle(true);
        $("#internship_remote_state").parent().removeClass('text-muted');
    }else{
        // disable remote dropdown
        $("#internship_remote_state_label").toggle(false);
        $("#internship_remote_state").parent().addClass('text-muted');
    }

    // Bind event handler
    $("#internship_remote").click(function(){
        if($("#internship_remote").prop('checked')){
            // Enable remote dropdown
            $("#internship_remote_state_label").toggle(true);
            $("#internship_remote_state").parent().removeClass('text-muted');
        }else{
            // Disable and clear remote dropdown
            $("#internship_remote_state_label").toggle(false);
            $("#internship_remote_state").parent().addClass('text-muted');
        }
    });
}

function initMultiPart()
{
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
}

function initSecondaryPart()
{
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
}

function initFacultySelector()
{
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

        if($("#internship_department").val() === '-1'){
            return;
        }

        // Make the request for the list of faculty members for the selected department
        $.ajax({
            success: function(data){
              if (data.length === 0){
                  $.ajax({
                      success: handleGetFacultyResponse,
                      error: handleGetFacultyResponseError,
                      data: {module: 'intern',
                          action: 'restFacultyById',
                          id: $("#internship_faculty_id").val()
                          },
                      dataType: 'json',
                      url: 'index.php'
                  });
              }
              else{
                handleFacultyResponse(data)
              }},
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
    function handleFacultyResponse(data){
      // Save the data outside this method for later
      facultyData = data;

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
      if ($("#internship_faculty_id").val() !== '') {
        selectFaculty($("#internship_faculty_id").val());
      }
    }

    // Handle the AJAX response to get faculty member when no faculty in department
    function handleGetFacultyResponse(data)
    {
        $("#internship_faculty").html("<option value='-1'>No Supervisors Available</option>");
        updateFaculty(data);
    }

    // Handle an AJAX error when getting faculty members for a department
    function handleFacultyReponseError(jqXHR, textStatus)
    {
      console.log("Error loading facuty list. Please contact ESS.");
      console.log(textStatus);
    }

    // Handles an AJAX error when there are no listed faculty by that id in table
    function handleGetFacultyResponseError(data)
    {
      $("#internship_faculty").html("<option value='-1'>No Supervisors Available</option>");
      return;
    }

    // Trigger a change for the inital loading of faculty info
    $("#internship_department").change(); // Trigger an initial update


    // Handle changes to the faculty drop down
    $("#internship_faculty").bind('change', function(){
        if($("#internship_faculty").val() !== "-1") {
            selectFaculty($("#internship_faculty").val());
        } else {
            $("#internship_faculty_id").val('');
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

    // Select faculty from the dropdown list
    function selectFaculty(bannerId)
    {
        // Store the selected faculty banner id in the hidden field
        $("#internship_faculty_id").val(bannerId);

        // Search the list of faculty for a match to the JSON data fetched earlier
        var faculty = null;

        for(var i = 0; i < facultyData.length; i++){
            if(facultyData[i].id == bannerId){ // NB: Comparison must use double equals, not triple
                faculty = facultyData[i];
                updateFaculty(faculty);
            }
        }
        $.ajax({
            success: updateFaculty,
            error: handleFacultyReponseError,
            data: {module: 'intern',
                action: 'restFacultyById',
                id: $("#internship_faculty_id").val()
                },
            dataType: 'json',
            url: 'index.php'
        });
    }

    function updateFaculty(faculty)
    {
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
}

function initRequestBackCheck()
{
    $("#back_check_id").click(function() {

        $("#back_check_id").prop('disabled', true);

        $("#back_check_id").html('Background Check Requested');

        $.ajax({

            url: 'index.php?module=intern&action=RequestBackgroundCheck&internship_id=' + $('#internship_internship_id').val(),

            success: function() {
                //console.log('Background Request Sent');
            },

            error: function() {
                alert('Request was not sent. Internal error.');
            }

        });
    });
}

function initRequestDrugCheck()
{
    $("#drug_check_id").click(function() {

        $("#drug_check_id").prop('disabled', true);

        $("#drug_check_id").html('Drug Screening Requested');

        $.ajax({

            url: 'index.php?module=intern&action=RequestDrugScreening&internship_id=' + $('#internship_internship_id').val(),

            success: function() {
                //console.log('Drug Request Sent');
            },

            error: function() {
                alert('Request was not sent. Internal error.');
            }

        });
    });
}


function otherStuff()
{

    addFormErrors();
    initPayment();
    initOIED();
    initMultiPart();
    initRemote();
    initSecondaryPart();
    initFacultySelector();
    initRequestBackCheck();
    initRequestDrugCheck();

    setupFormSubmit();
    setupContractButton();
}
