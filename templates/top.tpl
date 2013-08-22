<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Internship Inventory</a>
  </div>
<!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li><a href="index.php?module=intern&action=edit_internship"><i class="icon-plus"></i> Add Internship</a></li>
      <li><a href="index.php?module=intern&action=search"><i class="icon-search"></i> Search</a></li>
      <li><a href="mailto:websupport@tux.appstate.edu?subject=Intern Inventory Help Request"><i class="icon-question-sign"></i> Get Help</a></li>
    </ul>
    
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{USER_FULL_NAME} <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li>{LOGOUT}</li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>


<!-- BEGIN notification -->
<div class="container">{NOTIFICATIONS}</div>
<!-- END notification -->


<div id="container">{CONTENT}</div>
