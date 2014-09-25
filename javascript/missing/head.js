<script type="text/javascript">
$(document).ready(function(){
    // Add event handlers to change highlighting of a field when user updates the field
    $(".missing").keyup(function(){
        highlightHandler(this);
    });
    $(".missing").change(function(){
        highlightHandler(this);
    });
    
    var params = getUrlVars();
    if(typeof params['missing'] != 'undefined') {
    	var missingFields = params['missing'].split('+');
    	for (i = 0; i < missingFields.length; i++) {
    		highlightField(missingFields[i]);
    	}
    }
    
    //$("#term").parent().parent().parent().addClass("has-error");
    
    
    // Handler to un-highlight a highlighted field when it is updated
    function highlightHandler(field){
        if($(field).hasClass("missing") && $(field).val9() != ''){
            // Field is highlighted and not empty, so clear the error class
            removeClass(field);
        }
    }
    
    // Add error class to the given field
    function highlightField(field)
    {
    	if($("#" + field).attr('type') == 'radio'){
			$("#" + field).parent().parent().parent().addClass("has-error");
		}else{
			$("#" + field).parent().addClass("has-error");
		}
	}

    // Remove error class from given field
	function clearHighlight(field)
	{
		if($("#" + field).attr('type') == 'radio'){
			$("#" + field).parent().parent().parent().removeClass("has-error");
		}else{
			$("#" + field).parent().removeClass("has-error");
		}
	}
	
	// Returns the parameters in the query string of the current URL
	function getUrlVars()
	{
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
	
	/*
	function addFormErrors()
	{
	    // Add error class to parent object (hopefully a div.form-group)
	    // of any form element with the data-has-error="true" attribute.
	    $("input[data-has-error='true']").parent().parent().addClass('has-error');
	    $("select[data-has-error='true']").parent().parent().addClass('has-error');
	    $("input[type='radio'][data-has-error='true']").parent().addClass('has-error');
	}
	*/
});
</script>