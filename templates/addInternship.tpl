<form role="form" class="form-protected" autocomplete="off" action="index.php" method="post">
<input type="hidden" name="action" value="createInternship">

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
        <h2><i class="fa fa-plus"></i> Add an Internship</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="margin-top:0"><i class="fa fa-user"></i> Student</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-push-1">
                        <div class="form-group">
                            <label for="student_id" class="sr-only">Banner ID, User name, or Full Name</label>
                            <input type="text" id="student_id" class="form-control input-lg" placeholder="Banner ID, User name, or Full Name" autocomplete="off" autofocus>
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
    <label>Term</label>
        <div class="form-group">
            <div class="btn-group" data-toggle="buttons">
                <!-- BEGIN TERMS -->
                <label class="btn btn-default">
                    <input type="radio" name="term" id="term">{TERM_TEXT}
                </label>
                <!-- END TERMS -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
        <label>Location</label>
        <div class="form-group">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default">
                    <input type="radio" name="term" id="domestic">Domestic
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="term" id="international">International
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group">
            <label for="department">Department</label>
            <select id="department" class="form-control">
                <!-- BEGIN DEPARTMENTS -->
                <option value="{DEPT_ID}">{DEPT_NAME}</option>
                <!-- END DEPARTMENTS -->
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-4 col-md-push-3">
        <div class="form-group">
            <label for="agency">Host Agency</label>
            <input type="text" id="agency" class="form-control" placeholder="Acme, Inc.">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-md-push-3">
    <button type="submit" class="btn btn-lg btn-primary pull-right" id="create-btn">Create Internship</button>
    </div>
</div>
</form>