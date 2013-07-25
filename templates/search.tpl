<h2 class="search-icon">Search Inventory</h2>
    
<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}" {FORM_ENCODE}>
{HIDDEN_FIELDS}

<div class="row">
  <div class="pull-right">
    <button class="btn btn-primary" id="{SUBMIT_ID}">Search</button>
  </div>
  <div class="span1 offset4" style="">
    <div class="control-group">
      <label class="control-label" for="{NAME_ID}">{NAME_LABEL_TEXT}</label>
      <div class="controls">
        <input type="text" id="{NAME_ID}" name="{NAME_NAME}" class="input-large" placeholder="Name or Banner ID" autofocus>
      </div>
    </div>
  </div>
</div>

<hr>

<div class="row">
  <div class="span6">
      <div class="control-group">
        <label class="control-label" for="{TERM_SELECT_ID}">{TERM_SELECT_LABEL_TEXT}</label>
        <div class="controls">
          {TERM_SELECT}
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="{TERM_SELECT_ID}">{DEPT_LABEL_TEXT}</label>
        <div class="controls">
          {DEPT}
        </div>
      </div>

      <fieldset>
        <legend>Level &amp; Major/Program</legend>
        <div class="control-group">
          <div class="controls">
            <!-- BEGIN student_level_repeat -->
            <label class="radio">
              {STUDENT_LEVEL} {STUDENT_LEVEL_LABEL_TEXT}
            </label>
            <!--  END student_level_repeat -->
          </div>
        </div>

        <div class="control-group">
          <div class="controls">
            {UGRAD_MAJOR}
          </div>
        </div>
        
        <div class="control-group">
          <div class="controls">
            {GRAD_PROG}
          </div>
        </div>
      </fieldset>
      
      <fieldset>
        <legend>Campus</legend>
        <div class="control-group">
          <div class="controls">
            <!-- BEGIN campus_repeat -->
            <label class="radio">
              {CAMPUS} {CAMPUS_LABEL_TEXT}
            </label>
            <!-- END campus_repeat -->
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Internship Type</legend>
        <div class="control-group">
          <div class="controls">
            <!-- BEGIN type_repeat -->
            <label class="radio">
            {TYPE} {TYPE_LABEL_TEXT}
            </label>
            <!-- END type_repeat -->
          </div>
        </div>
      </fieldset>

  </div>
  <div class="span6">
      <fieldset class="search-fieldset">
        <legend>Course</legend>
        
        <div class="control-group">
          <div class="controls">
            {COURSE_SUBJ}
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="{COURSE_NO_ID}">{COURSE_NO_LABEL_TEXT}</label>
          <div class="controls">
            {COURSE_NO}
          </div>
        </div>
        
        <div class="control-group">
          <label class="control-label" for="{COURSE_SECT_ID}">{COURSE_SECT_LABEL_TEXT}</label>
          <div class="controls">
            {COURSE_SECT}
          </div>
        </div>
        
      </fieldset>
      <fieldset class="search-fieldset">
        <legend>Location</legend>
        <div class="control-group">
          <div class="controls">
            <!-- BEGIN loc_repeat -->
            <label class="radio">
              {LOC}{LOC_LABEL_TEXT}
            </label>
            <!-- END loc_repeat -->
          </div>
        </div>
      </fieldset>

      <div class="control-group">
        <label class="control-label" for="{STATE_ID}">{STATE_LABEL_TEXT}</label>
        <div class="controls">
          {STATE}
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="{PROV_ID}">{PROV_LABEL_TEXT}</label>
        <div class="controls">
          {PROV}
        </div>
      </div>

      <fieldset class="search-fieldset">
        <legend>Status</legend>
        <div class="control-group">
          <div class="controls">
            <div class="well">
              <!-- BEGIN workflow_state_repeat -->
              <label class="checkbox">
                {WORKFLOW_STATE}{WORKFLOW_STATE_LABEL_TEXT}
              </label>
              <!-- END workflow_state_repeat -->
            </div>
          </div>
        </div>
      </fieldset>
      
      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn btn-primary" name="{SUBMIT_NAME}" value="Search">Search</button>
          <button type="button" name="reset" class="btn">Clear Fields</button>
        </div>
      </div>
      
  </div>
</div>

{END_FORM}