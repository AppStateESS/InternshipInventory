<script type="text/javascript">
    $(document).ready(function() {
        // Setup date pickers
        $("#internship_start_date").datepicker();
        $("#internship_end_date").datepicker();
    });
</script>

<h2><i class="fa fa-search"></i> Search Inventory</h2>

<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}" {FORM_ENCODE}>
  {HIDDEN_FIELDS}

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="form-group">
        <label class="control-label" for="{NAME_ID}" style="display: none;">{NAME_LABEL_TEXT}</label> <input type="text" id="{NAME_ID}" name="{NAME_NAME}" class="form-control input-lg" placeholder="Name or Banner ID" autofocus>
      </div>
    </div>

    <div class="col-md-4">
      <button type="submit" class="btn btn-primary pull-right btn-lg" id="{SUBMIT_ID}">Search</button>
    </div>
  </div>

  <hr>

  <div class="row">
    <!-- Left Column -->
    <div class="col-md-6">

        <fieldset class="search-fieldset">
          <legend>Course</legend>

          <div class="form-group">
            <label class="col-md-3 control-label" for="{TERM_SELECT_ID}">{TERM_SELECT_LABEL_TEXT}</label>
            <div class="col-md-5">{TERM_SELECT}</div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label" for="{COURSE_SUBJ_ID}">Subject</label>
            <div class="col-md-8">{COURSE_SUBJ}</div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label" for="{COURSE_NO_ID}">{COURSE_NO_LABEL_TEXT}</label>
            <div class="col-md-3">{COURSE_NO}</div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label" for="{COURSE_SECT_ID}">{COURSE_SECT_LABEL_TEXT}</label>
            <div class="col-md-3">{COURSE_SECT}</div>
          </div>

        </fieldset>

        <fieldset class="search-fieldset">
            <legend>Faculty</legend>
        </fieldset>

      <div class="form-group">
        <label class="col-md-3 control-label" for="{DEPARTMENT_ID}">{DEPARTMENT_LABEL_TEXT}</label>
        <div class="col-md-8">{DEPARTMENT}</div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label" for="{FACULTY_ID}">{FACULTY_LABEL_TEXT}</label>
        <div class="col-md-8">{FACULTY}</div>
      </div>

      <!-- Level & Major Fieldset -->
      <fieldset>
        <div id="MajorSelector"></div>
      </fieldset>

      <fieldset>
        <legend>Internship Type</legend>
        <div class="form-group">
          <label class="col-md-3 control-label" for="type">Type</label>
          <div class="col-md-9">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default">Internship
                  <input type="radio" name="type" value="internship">
                </label>
                <label class="btn btn-default">Student Teaching
                  <input type="radio" name="type" value="student_teaching">
                </label>
                <label class="btn btn-default">Practicum
                  <input type="radio" name="type" value="practicum">
                </label>
                <label class="btn btn-default">Clinical
                  <input type="radio" name="type" value="clinical">
                </label>
              </div>
          </div>
        </div>
      </fieldset>

  </div> <!-- End Left Column -->

    <!-- Right Column -->
    <div class="col-md-6">

      <fieldset class="search-fieldset">
        <legend>Location</legend>

        <div class="form-group">
          <label class="col-md-3 control-label" for="campus">Campus</label>
          <div class="col-md-8">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">Any Campus
                  <input type="radio" name="campus" value="-1" checked>
                </label>
                <label class="btn btn-default">Main Campus
                  <input type="radio" name="campus" value="main_campus">
                </label>
                <label class="btn btn-default">Distance Ed
                  <input type="radio" name="campus" value="distance_ed">
                </label>
              </div>
          </div>
        </div>

        <div id="LocationSelector"></div>

        <div class="form-group">
          <label class="col-md-3 control-label" for="{HOST_ID}">Host</label>
          <div class="col-md-8">{HOST_SELECT}</div>
        </div>

      </fieldset>

      <fieldset>
        <legend>Date Range</legend>
        <div class="form-group">
          <label class="col-md-3 control-label" for="{START_DATE_ID}">{START_DATE_LABEL_TEXT}</label>
          <div class="col-md-6">{START_DATE}</div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label" for="{END_DATE_ID}">{END_DATE_LABEL_TEXT}</label>
          <div class="col-md-6">{END_DATE}</div>
        </div>
      </fieldset>


      <fieldset>
        <legend>Status</legend>
        <div class="form-group">
          <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
              <div class="panel-body">
                <!-- BEGIN workflow_state_repeat -->
                <div class="checkbox">
                  <label> {WORKFLOW_STATE} {WORKFLOW_STATE_LABEL_TEXT}</label>
                </div>
                <!-- END workflow_state_repeat -->
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="oied">OIED Certification</label>
          <div class="col-md-8">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">Any
                  <input type="radio" name="oied" value="-1" checked>
                </label>
                <label class="btn btn-default">Non-Certified
                  <input type="radio" name="oied" value="0">
                </label>
                <label class="btn btn-default">Certified
                  <input type="radio" name="oied" value="1">
                </label>
              </div>
          </div>
        </div>
      </fieldset>

    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-md-offset-8">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" name="{SUBMIT_NAME}" value="Search">Search</button>
        <button type="button" name="reset" class="btn btn-default">Clear Fields</button>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript" src="{vendor_bundle}"></script>
<script type="text/javascript" src="{entry_bundle}"></script>
<script type="text/javascript" src="{major_bundle}"></script>
