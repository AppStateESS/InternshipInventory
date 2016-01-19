<div class="alert">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span class="nq">Derp</span>
</div>

<div class="row">
  <div class="col-lg-3 col-lg-push-5">
    <button type="button" id="faculty-new" class="btn btn-primary"><i class="fa fa-plus"></i> Add a Faculty Member to this Department</button>
  </div>
  <div class="col-lg-4 col-lg-pull-3">
    <div class="form-group">
      <label for="department">Department</label>
      <select id="department" class="form-control" name="department">{DEPTS}</select>
    </div>
  </div>
</div>


<div id="faculty-list" style="margin-top: 1em;"></div>
<div class="faculty-loading" style="margin-left: 10em;"><img src="images/icons/default/ajax25.gif"></div>
