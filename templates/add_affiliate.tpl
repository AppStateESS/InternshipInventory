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
    <h2>Add Affiliation Agreement</h2>
  </div>
</div>
{HOMELINK}
{ERROR}
{START_FORM}

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

<div class='row'>
  <div class="col-md-5">
    <button type="submit" class="btn btn-success btn-lg pull-right">
      Save and Continue
      <i class="fa fa-chevron-right"></i>
    </button>
  </div>
</div>
{END_FORM}
