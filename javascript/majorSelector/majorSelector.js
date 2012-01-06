function initMajors(form_id){
	
	console.log(form_id);
	
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
    $("#" + form_id + "_student_level_ugrad").click(function(){ugradClick();});
    $("#" + form_id + "_student_level_grad").click(function(){gradClick();});
    
    // Set initial state for student level radio button
    if($("#" + form_id + "_student_level_ugrad").attr('checked')){
    	ugradClick();
    }else if($("#" + form_id + "student_level_grad").attr('checked')){
    	gradClick();
    }else{
    	// Nothing is checked, hide both
    	$("#ugrad_drop").hide();
    	$("#grad_drop").hide();
    }
};