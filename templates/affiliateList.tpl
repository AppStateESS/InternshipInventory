<div class="row">
  <div class="col-md-4">
    <h2>Affiliation Agreements</h2>
  </div>
</div>

{START_FORM}

<div class="row">
    <div class="col-md-3">
        <a href="index.php?module=intern&action=addAgreementView" class="btn btn-md btn-success"><i class="fa fa-plus"></i> Add New Agreement </a>
    </div>
    <div class="form-group col-md-3 col-md-offset-4">
        <label for="{SEARCH_ID}">{SEARCH_LABEL}</label>
        {SEARCH}
    </div>
    <div class="col-md-2" style="padding-top: 2em;">
        <button type="submit" class="btn btn-success btn-md">
            <i class="fa fa-search"></i>
        </button>
        <!-- BEGIN CLEAR -->
        <a href="{CLEAR}" class="btn btn-md btn-danger">
            <i class="fa fa-times"></i>
        </a>
      <!-- END CLEAR -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
      {PAGER}
    </div>
</div>

{END_FORM}
