<div class="row">
  <div class="col-md-3">
    <a href={BACK} class="btn btn-primary btn-lg">
      <i class="fa fa-chevron-left"></i> 
      Back to List
    </a>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <h2>Edit Affiliation Agreement</h2>
  </div>
</div>
{HOMELINK}
{ERROR}
{START_FORM}

<div id="terminate">
</div>

<div class='row'>
  <div class='col-md-2'>
    <button type="submit" class="btn btn-success btn-lg">
      Save
      <i class="fa fa-save"></i>
    </button>
  </div>
</div>


<p></p>

<div class='row'>
  <div class="col-md-5">
    <div class="form-group {name_ERROR}">
      <label class="control-label">
        {NAME_LABEL}
      </label>
      {NAME}
    </div>

    <div class="form-group {begin_date_ERROR}">
      <label class="control-label">
        {BEGIN_DATE_LABEL}
      </label>
      {BEGIN_DATE}
    </div>

    <div class="form-group {end_date_ERROR}">
      <label class="control-label">
        {END_DATE_LABEL}
      </label>
      {END_DATE}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          {AUTO_RENEW_LABEL}
        </label>
        <label>
          {AUTO_RENEW}
        </label>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-md-offset-1">
    <div class="row">
      <h3>Contracts</h3>
      <div class="col-md-9">
        <ul class="list-group">
          <!-- BEGIN docs -->
          <li class="list-group-item"><i class="fa fa-file"></i> {DOWNLOAD} &nbsp;{DELETE}</li>
          <!-- END docs -->
        </ul>
      </div>
      <div>{UPLOAD_DOC}</div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label>
        {NOTES_LABEL}
      </label>
      {NOTES}
    </div>
  </div>
</div>


{END_FORM}

<div class="row">
  <div class="col-md-5">
    <h3>
    Add Departments
    </h3>
    <div id='departments'>
    </div>
  </div>

  <div class='col-md-5 col-md-offset-2'>
    <h3>
      Add Locations
    </h3>
    <div id="locations">
    </div>
  </div>
</div>
