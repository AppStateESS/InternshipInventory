function initMajors(form_id){
	
	// Set initial state
    $("#" + form_id +"_ugrad_major").hide();
    $("#" + form_id +"_grad_prog").hide();

    // Set initial state for student level drop down by calling the
    // usual event handler if needed
    if($("#" + form_id +"_student_level").val() != -1){
        handleLevelChange();
    }
    
    // Event handler for student level drop down
    function handleLevelChange()
    {
        if($("#" + form_id +"_student_level").val() == 'ugrad'){
            $("#" + form_id +"_grad_prog").hide();
            $("#" + form_id +"_student_major").hide();
            $("#" + form_id +"_ugrad_major").show();
        }else if ($("#" + form_id +"_student_level").val() == 'grad'){
            $("#" + form_id +"_ugrad_major").hide();
            $("#" + form_id +"_student_major").hide();
            $("#" + form_id +"_grad_prog").show();
        }else {
            $("#" + form_id +"_ugrad_major").hide();
            $("#" + form_id +"_grad_prog").hide();
            $("#" + form_id +"_student_major").show();
        }
    }
    
    // Bind event handler for drop down change
    $("#" + form_id +"_student_level").change(handleLevelChange);
};