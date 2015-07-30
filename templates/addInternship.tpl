<script src="{source_http}mod/intern/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script src="https://fb.me/react-with-addons-0.13.3.js"></script>
<script src="https://fb.me/JSXTransformer-0.13.3.js"></script>
<script type="text/jsx" src="{source_http}mod/intern/javascript/createInterface/create.jsx"></script>

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
                    <div id="searchform"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="termBlock"></div>

<div id="locationBlock"></div>

<!--
<div class="row" id="state-row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group" id="state">
            <label for="state" class="control-label">State</label>
            <select id="state-control" name="state" class="form-control">
                <option value="{ABBR}" {SELECTED}>{STATE_NAME}</option>
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
                <option value="{ABBR}" {SELECTED}>{COUNTRY_NAME}</option>
            </select>
        </div>
    </div>
</div>
-->

<div id="department"></div>

<div id="hostAgency"></div>

<div id="submitButton"></div>
</form>

<script type="text/javascript">
/*
$(document).ready(function(){
	// Event handler for clicking the submit button
    $('#newInternshipForm').submit(function(){
        // Disable the button
        $('button[type="submit"]').prop('disabled','disabled');

        $('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    });
*/
</script>
