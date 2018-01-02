<div class="row">
  <div class="col-md-3">
    <a href="index.php?module=intern&action=showAffiliateAgreement" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i> Back to List</a>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <h1>Add Affiliation Agreement</h1>
  </div>
</div>

{ERROR}

{START_FORM}

<div class="row">
    <div class="col-md-5">
        <div class="form-group {name_ERROR}">
            <label for="{NAME_ID}" class="control-label">
                {NAME_LABEL_TEXT}
            </label>
            {NAME}
        </div>

        <div class="form-group {begin_date_ERROR}">
            <label for="begin_date" class="control-label">
                Beginning Date
            </label>
            <input type="date" id="begin_date" name="begin_date" class="form-control" required>
        </div>

        <div class="form-group {end_date_ERROR}">
            <label for="end_date" class="control-label">
                Ending Date
            </label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
        </div>

        <div class="checkbox">
            <label>
                {AUTO_RENEW}
                {AUTO_RENEW_LABEL_TEXT}
            </label>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-5">
    <button type="submit" class="btn btn-success btn-lg pull-right">
      Save and Continue
      <i class="fa fa-chevron-right"></i>
    </button>
  </div>
</div>
{END_FORM}
