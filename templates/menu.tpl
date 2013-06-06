<div class="row">
  <div class="span4">
    <h2>Add Internship</h2>
    <p>Create a new internship.</p>
    <p><a class="btn btn-large" href="index.php?module=intern&action=edit_internship"><i class="icon-plus"></i> Add Internship</a></p>
  </div>
  <div class="span4">
    <h2>Search</h2>
    <p>Find an existing internship or generate reports based on search criteria.</p>
    <p><a class="btn btn-large" href="index.php?module=intern&action=search"><i class="icon-search"></i> Search Inventory</a></p>
  </div>
  <div class="span4">
    <h2>Settings</h2>
    <p>Change settings and edit options.</p>
    <div class="btn-group">
      <a class="btn btn-large dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="icon-cog"></i>&nbsp;
        Settings &nbsp;
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
      <!-- dropdown menu links -->
        <!-- BEGIN majors --><li>{EDIT_MAJORS_LINK}</li><!-- END majors -->
        <!-- BEGIN grad --><li>{EDIT_GRAD_LINK}</li><!-- END grad -->
        <!-- BEGIN depts --><li>{EDIT_DEPARTMENTS_LINK}</li><!-- END depts -->
        <!-- BEGIN admins --><li>{EDIT_ADMINS_LINK}</li><!-- END admins -->
        <!-- BEGIN faculty --><li>{EDIT_FACULTY}</li><!-- END faculty -->
        <!-- BEGIN states --><li>{EDIT_STATES_LINK}</li><!-- END states -->
        <!-- BEGIN ctrl_panel --><li>{CONTROL_PANEL}</li><!-- END ctrl_panel -->
      </ul>
    </div>
  </div>
</div>



  {EXAMPLE_LINK}
  
<div id="intern-totals">{GRAND_TOTAL_LABEL}{GRAND_TOTAL}</div>