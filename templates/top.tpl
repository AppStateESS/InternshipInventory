  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">{SYSTEM_NAME}</a>
  </div>
<!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li><a href="index.php?module=intern&action=ShowAddInternship"><i class="fa fa-plus"></i> Add Internship</a></li>
      <li><a href="index.php?module=intern&action=search"><i class="fa fa-search"></i> Search</a></li>
      <li><a href="mailto:esswebsupport@appstate.edu?subject=Internship Inventory Help Request"><i class="fa fa-question"></i> Get Help</a></li>
    </ul>


    <ul class="nav navbar-nav navbar-right">
      <!-- BEGIN admin_links -->
      {ADMIN_OPTIONS}
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> Settings &nbsp;<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <!-- BEGIN majors -->
          <li>{EDIT_MAJORS_LINK}</li>
          <!-- END majors -->
          <!-- BEGIN grad -->
          <li>{EDIT_GRAD_LINK}</li>
          <!-- END grad -->
          <!-- BEGIN depts -->
          <li>{EDIT_DEPARTMENTS_LINK}</li>
          <!-- END depts -->
          <!-- BEGIN admins -->
          <li>{EDIT_ADMINS_LINK}</li>
          <!-- END admins -->
          <!-- BEGIN faculty -->
          <li>{EDIT_FACULTY}</li>
          <!-- END faculty -->
          <!-- BEGIN states -->
          <li>{EDIT_STATES_LINK}</li>
          <!-- END states -->
          <!-- BEGIN affiliation_agreement -->
          <li>{AFFIL_AGREE_LINK}</li>
          <!-- END affiliation_agreement -->
          <!-- BEGIN courses -->
          <li>{EDIT_COURSES_LINK}</li>
          <!-- END courses -->
          <!-- BEGIN ctrl_panel -->
          <li>{CONTROL_PANEL}</li>
          <!-- END ctrl_panel -->
        </ul>
      </li>
      <!-- END admin_links -->
      <li>
        <a href="#">{USER_FULL_NAME}</a>
      </li>
      <li>
        <a href="{LOGOUT_URI}"><i class="fa fa-sign-out"></i> Sign out</a>
      </li>

    </ul>

  </div><!-- /.navbar-collapse -->
