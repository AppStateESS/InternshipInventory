function initMajors(form_id){

    var selectorBase = "#" + form_id;

	// Set initial state
    $(selectorBase +"undergrad_major").hide();
    $(selectorBase +"gradaute_major").hide();

    // Set initial state for student level drop down by calling the
    // usual event handler if needed
	$("input[type='radio'][name='student_level'][value='-1']").prop('checked', true);
    handleLevelChange();

    // Event handler for student level drop down
    function handleLevelChange()
    {
		$("input[type='radio'][name='student_level']:parent").removeClass('active');
		$("input[type='radio'][name='student_level']:checked:parent").addClass('active');

		var studentLevel = $("input[type='radio'][name='student_level']:checked").val();
        if(studentLevel === 'ugrad'){
            $(selectorBase +"_student_major").hide();
            $(selectorBase +"_graduate_major").hide();
            $(selectorBase +"_undergrad_major").show();
        } else if (studentLevel == 'grad'){
            $(selectorBase +"_student_major").hide();
            $(selectorBase +"_graduate_major").show();
            $(selectorBase +"_undergrad_major").hide();
        } else {
            $(selectorBase +"_student_major").show();
            $(selectorBase +"_graduate_major").hide();
            $(selectorBase +"_undergrad_major").hide();
        }
    }

    // Bind event handler for drop down change
	$("input[type='radio'][name='student_level']").change(handleLevelChange);
}
