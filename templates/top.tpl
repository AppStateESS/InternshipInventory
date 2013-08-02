<div class="navbar">
  <a class="navbar-brand" href="#">Internship Inventory</a>
  <ul class="nav navbar-nav">
    <li><a href="index.php?module=intern&action=edit_internship"><i class="icon-plus"></i> Add Internship</a></li>
    <li><a href="index.php?module=intern&action=search"><i class="icon-search"></i> Search</a></li>
    <li><a href="mailto:websupport@tux.appstate.edu?subject=Intern Inventory Help Request"><i class="icon-question-sign"></i> Get Help</a></li>
  </ul>

  <ul class="nav navbar-nav pull-right">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> {USER_FULL_NAME} <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li>{LOGOUT}</li>
      </ul>
    </li>
  </ul>
</div>


<!-- BEGIN notification -->
<div class="container">{NOTIFICATIONS}</div>
<!-- END notification -->


<div id="container">{CONTENT}</div>
