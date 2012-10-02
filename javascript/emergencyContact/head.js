<script type="text/javascript" src="./mod/intern/javascript/emergencyContact/EmgContactList.js" ></script>
<script type="text/javascript">

	var emgSpinner = null;

    $(document).ready(function(){
    	// Setup the dialog
        $("#emergency-dialog").dialog({
        		autoOpen: false,
        		modal: true,
        		title: 'Add Emergency Contact',
        		buttons: {"Add": dialogAddButtonHandler,
        				  "Cancel": dialogCancelButtonHandler
        				}
        	});
        
        // Add event handler for 'Add Contact' button.
        $("#add-ec-button").click(function(){
        	// Open the dialog
        	$("#emergency-dialog").dialog('open');
        });
        
        $('#emergency-dialog').keyup(function(e) {
            if (e.keyCode == 13) {
                dialogAddButtonHandler();
            }
        });
        
    });
    
    // Handle the user clicking the 'Add' button in the dialog.
    function dialogAddButtonHandler()
    {
    	name     = $("#emerg_form_emergency_contact_name").val();
    	relation = $("#emerg_form_emergency_contact_relation").val();
    	phone    = $("#emerg_form_emergency_contact_phone").val();
    	
    	// Sanity checking; All three fields must be filled in
    	if(name.length == 0 || relation.length == 0 || phone.length == 0){
    		alert('Please enter a value for all emergency contact fields.');
    		return;
    	}
    	
    	// Spinner options
    	opts = {
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
  			  top: 0, // Top position relative to parent in px
  			  left: 0 // Left position relative to parent in px
  			};
    	
    	// Setup and show the spinner
    	emgSpinner = new Spinner(opts).spin();
    	$("#emergency-spinner").append(emgSpinner.el);
    	
    	// Close the dialog
    	$("#emergency-dialog").dialog('close');
    	
    	// Do some ajax
    	$.ajax({
    		type: 'POST',
    		url: 'index.php',
    		data: $("#emerg_form").serialize(),
    		dataType: 'json',
    		success: emergContactAddSuccess,
    		error: emergContactAddError,
    		complete: emergContactAddComplete
    	});
    }

    // Handle the user clicking the'Cancel' button in the dialog.
    function dialogCancelButtonHandler()
    {
    	$("#emergency-dialog").dialog('close');
    }
    
    // Called on ajax success
    function emergContactAddSuccess(data, textStatus, jqXHR)
    {
        // Add the new contact to the interface
        $("#emergency-contact-list").EmgContactList('add', data.id, data.name, data.relation, data.phone);
        
    	// If last request worked, then clear any previous values from the
        // dialog's fields
  		$("#emerg_form_emergency_contact_name").val('');
   		$("#emerg_form_emergency_contact_relation").val('');
   		$("#emerg_form_emergency_contact_phone").val('');
    }
    
    // Called on ajax error
    function emergContactAddError(jqXHR, textStatus, errorThrown)
    {
    	alert('Sorry, there was an error saving the emergency contact information.');
    }
    
    // Called when the AJAX request to save emerg contact is complete
    // (regardless of success or fail)
    function emergContactAddComplete(jqXHR, textStatus)
    {
    	emgSpinner.stop();
    }
</script>