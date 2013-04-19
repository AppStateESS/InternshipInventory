<script type="text/javascript">
    $(document).ready(function(){
        $("#facultyEdit_department_drop").bind('change', handleDepartmentChange)
    });
    
    function handleDepartmentChange(event)
    {
    	// Make the request for the list of faculty members for the selected department
    	$.ajax({
    		success: handleFacultyResponse,
    		error: handleFacultyReponseError,
    		data: {module: 'intern',
    			   action: 'getFacultyListForDept',
    			   department: $("#facultyEdit_department_drop").val()
    			   },
            dataType: 'json',
    	    url: 'index.php'
    	});
    }
    
    function handleFacultyResponse(data, textStatus, jqXHR)
    {
    	console.log(data);
    	
    	$("#facultyList").html(''); //TODO load the page content via ajax
    }
    
    // Handle an AJAX error when getting faculty members for a department
    function handleFacultyReponseError(jqXHR, textStatus, errorThrown)
    {
    	console.log("Error loading facuty list. Please contact ESS.");
    	console.log(textStats);
    }
    
</script>