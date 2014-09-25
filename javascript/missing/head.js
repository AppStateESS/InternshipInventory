<script type="text/javascript">
$(document).ready(function(){
    // Add event handlers to change highlighting of a field when user updates the field
    
    var params = getUrlVars();
    if(typeof params['missing'] != 'undefined') {
    	var missingFields = params['missing'].split('+');
    	for (i = 0; i < missingFields.length; i++) {
    		field = $("#" + missingFields[i]);
    		
    		// Add highlight
    		highlightField(field);

    		field.bind('keyup change', function(){
	    		highlightHandler(this);
	    	});
    	}
    }
    
    // Handler to un-highlight a highlighted field when it is updated
    function highlightHandler(field){
        if($(field).hasClass('has-error')){
            // Field is highlighted and not empty, so clear the error class
            clearHighlight(field);
        }
    }
    
    // Add error class to the given field
    function highlightField(field)
    {
		$(field).addClass("has-error");
	}

    // Remove error class from given field
	function clearHighlight(field)
	{
		$(field).removeClass("has-error");
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
});
</script>