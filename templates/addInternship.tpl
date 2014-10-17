<form role="form" id="newInternshipForm" class="form-protected" autocomplete="off" action="index.php" method="post">
<input type="hidden" name="module" value="intern">
<input type="hidden" name="action" value="AddInternship">

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
        <h2><i class="fa fa-plus"></i> Add an Internship</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="margin-top:0"><i class="fa fa-user"></i> Student</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-push-1">
                        <div class="form-group" id="studentId">
                            <label for="studentId2" class="sr-only">Banner ID, User name, or Full Name</label>
                            <input type="text" id="studentId2" name="studentId" class="form-control input-lg" placeholder="Banner ID, User name, or Full Name" value="{PREV_STUDENTID}" autocomplete="off" autofocus>
                        </div>
                    <div class="form-group">
                        <button type="button" id="student-search-btn" class="btn btn-default pull-right">Search</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
        <div class="form-group" id="term">
        <label for="term" class="control-label">Term</label><br>
            <div class="btn-group" data-toggle="buttons">
                <!-- BEGIN TERMS -->
                <label class="btn btn-default {ACTIVE}">
                    <input type="radio" name="term" value="{TERM}" {SELECTED}>{TERM_TEXT}
                </label>
                <!-- END TERMS -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
        <div class="form-group" id="location">
            <label for="location" class="control-label">Location</label><br>
            <div class="btn-group" data-toggle="buttons">
                <!-- BEGIN LOCATIONS -->
                <label class="btn btn-default {ACTIVE}">
                    <input type="radio" name="location" value="{LOCATION}" {SELECTED}>{LOCATION_TEXT}
                </label>
                <!-- END LOCATIONS -->
            </div>
        </div>
    </div>
</div>

<div class="row" id="state-row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group" id="state">
            <label for="state" class="control-label">State</label>
            <select id="state-control" name="state" class="form-control">
                <!-- BEGIN STATES -->
                <option value="{ABBR}" {SELECTED}>{STATE_NAME}</option>
                <!-- END STATES -->
            </select>
        </div>
    </div>
</div>

<div class="row" id="country-row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group" id="country">
            <label for="county" class="control-label">Country</label>
            <select id="country-control" name="country" class="form-control">
                <option value="-1">Select a Country</option>
                <!-- BEGIN COUNTRIES -->
                <option value="{ABBR}" {SELECTED}>{COUNTRY_NAME}</option>
                <!-- END COUNTRIES -->
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group" id="department">
            <label for="department2" class="control-label">Department</label>
            <select id="department2" name="department" class="form-control">
                <!-- BEGIN DEPARTMENTS -->
                <option value="{DEPT_ID}" {SELECTED}>{DEPT_NAME}</option>
                <!-- END DEPARTMENTS -->
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group" id="agency">
            <label for="agency2" class="control-label">Host Agency</label>
            <input type="text" id="agency2" name="agency" class="form-control" value="{PREV_AGENCY}" placeholder="Acme, Inc.">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
    <button type="submit" class="btn btn-lg btn-primary pull-right" id="create-btn">Create Internship</button>
    </div>
</div>
</form>

<script type="text/javascript">
$(document).ready(function(){
	// Event handler for clicking the submit button
    $('#newInternshipForm').submit(function(){
        // Disable the button
        $('button[type="submit"]').prop('disabled','disabled');

        $('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    });
	
	
	// Hide the state and country fields initially
	$('#state-row').hide();
	$('#country-row').hide();
	
	// Determines which location radio button is selected and
	// shows/hides the dropdowns accordingly
	function updateDropDowns() {
		if($('#location input[type=radio]:checked').val() == 'domestic') {
	        $('#country-row').hide();
	        $('#state-row').fadeIn(200);
	    }
	    if($('#location input[type=radio]:checked').val() == 'international') {
	        $('#state-row').hide();
	        $('#country-row').fadeIn(200);
	    }
	}
	
	// If domestic or international is selected, then show the corresponding drop-down
    updateDropDowns();
    
    // Event handler to show/hide boxes as location radio buttons are changed
    $('#location input').change(function(){
    	updateDropDowns();
    });
});
</script>