function initMajors(form_id){
	
    var selectorBase = "#" + form_id;

	// Set initial state
    $(selectorBase +"_ugrad_major").hide();
    $(selectorBase +"_grad_prog").hide();

    // Set initial state for student level drop down by calling the
    // usual event handler if needed
    if($(selectorBase +"_student_level").val() !== -1){
        handleLevelChange();
    }
    
    // Event handler for student level drop down
    function handleLevelChange()
    {
        var studentLevel = $(selectorBase + "_student_level").val();
        if(studentLevel === 'ugrad'){
            $(selectorBase +"_student_major").hide();
            $(selectorBase +"_grad_prog").hide();
            $(selectorBase +"_ugrad_major").show();
        } else if (studentLevel === 'grad'){
            $(selectorBase +"_student_major").hide();
            $(selectorBase +"_grad_prog").show();
            $(selectorBase +"_ugrad_major").hide();
        } else {
            $(selectorBase +"_student_major").show();
            $(selectorBase +"_grad_prog").hide();
            $(selectorBase +"_ugrad_major").hide();
        }
    }
    
    // Bind event handler for drop down change
    $(selectorBase +"_student_level").change(handleLevelChange);
}
