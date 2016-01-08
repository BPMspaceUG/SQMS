<?php
include_once '../phpSecureLogin/includes/db_connect.inc.php';
include_once '../phpSecureLogin/includes/functions.inc.php';
sec_session_start(); 
if(login_check($mysqli) != true) {
  header("Location: ../index.php?error_messages='You are not logged in!'");
  exit();
}
  else {
  	$logged = 'in';
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BPMspace SQMS</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="http://www.fuelcdn.com/fuelux/3.13.0/css/fuelux.min.css">
<link rel="stylesheet" href="custom/custom.css">

<!----- js scripts are loadede in the footer --------------------> 
</head>	
<body>


<div class="container">
	<div class="container">
		<div class="col-md-10"></div>
	  	<div class="col-md-2"><?php include_once '../_header_LIAM.inc.php'; ?></div>
	</div>

    <div class="container text-right">
	<a href='#' class="btn collapsed row" data-toggle="collapse" data-target="#logo"><i class="fa fa-caret-square-o-down"></i></a>
	</div>
	<div class="row collapse in" id="logo">
	  <div class="col-md-6"><img src="../images/yourLogo.png" alt="your logo"></div>
	  <div class="col-md-6"><img class="pull-right" src="../images/bpmspace_icon-SQMS-right-200px-text.png" alt="BPMspace Logo Syllabus Question Managment System" width=80% height=80%></div>
	</div>
</div>
</br>
<div class="clearfix"></div>
<!--------------- MAIN MENU --------->
<nav class="navbar">
  <div class="container" >
    <div class="navbar-inner ">
      <ul role="navigation" class="nav  nav-justified">
        <li><a href="dashboard.php" title='Dashboard' class="btn btn-large btn-primary"><i class="fa fa-tachometer"></i>&nbsp;Dashboard</a></li>
		<li><a href="syllabus.php" title='Show Syllabus' class="btn btn-large btn-primary"><i class="fa fa-table"></i>&nbsp;Syllabus</a></li> 
        <li><a href="question.php" title='Show all questions' class="btn btn-large btn-primary"><i class="fa fa-question"></i>&nbsp;Question</a></li>  
        <li><a href="topic.php" title='Show all Topics' class="btn btn-large btn-primary"><i class="fa fa-table"></i>&nbsp;Topic</a></li>
		<li class="dropdown">
			<a title='Admin'  data-toggle="dropdown" class="dropdown-toggle btn btn-large btn-primary"><i class="fa fa-user-secret"></i>&nbsp;Admin<b class="caret"></b></a>
				<ul class="dropdown-menu">
        <li><a href="user.php" title='user'  class="btn btn-large btn-primary"><i class="fa fa-user-plus"></i>&nbsp;Langua</a></li>
				<li><a href="topic.php" title='Topic'  class="btn btn-large btn-primary"><i class="fa fa-folder-o"></i>&nbsp;Topic</a></li>
				<li><a href="trainer.php" title='Trainer' class="btn btn-large btn-primary"><i class="fa fa-graduation-cap"></i>&nbsp;Trainer</a></li>
				<li><a href="course.php" title='Course' class="btn btn-large btn-primary"><i class="fa fa-university"></i>&nbsp;Course</a></li>
			</ul>
		</li> 		
        <li><a href="../phpSecureLogin/includes/logout.php" title='Logout' class="btn btn-large btn-primary"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>
	</ul>
    </div>
  </div>
</nav>

<?php
/* presente $error_messages when not empty */
if (!empty($_GET["error_messages"])) {
	  echo '<div class="container alert alert-danger 90_percent" role="alert"> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>' ;
		echo '&nbsp;error:&nbsp;' . htmlspecialchars($_GET["error_messages"]);
		echo '</br></div>';
}
?>
<!--------------- END MAIN MENU --------->
<!--------------- SUB MENU --------->
<div class="clearfix"></div>
<div class="container 90_percent" >
	<a href="#" class="btn btn-default" title='Add new Participant'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-user"></i></a>  
	<a href="#" class="btn btn-default" title='Add new Event'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-calendar"></i></a>  
	<a href="#" class="btn btn-default" title='Add new Organization'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-cubes"></i></a>  
	<a href="#" class="btn btn-default" title='Add new Location'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-location-arrow"></i></a>  
	<a href="#" class="btn btn-default" title='book participant on event'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-user"></i>&nbsp;<i class="fa fa-calendar"></i></a>
	<a href="#" class="btn btn-default" title='book trainer on event'><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-graduation-cap"></i>&nbsp;<i class="fa fa-calendar"></i></a>
	<a href="#" title='switch help on/off' class="btn btn-large btn-default navbar-right">
			<span class="fa-stack  ">
				  <i class="fa fa-question fa-stack-1x"></i>
				  <i class="fa fa-ban fa-stack-1x text-danger"></i>
			</span>Help</a> 	
</div>
<div class="clearfix"></br></div>
<!--------------- END SUB MENU --------->

<?php
$help_text = "true";
/* presente file with helptxt if $help_text = "true" (or set) when not empty */
if ($help_text) {
		echo '<div class="container bg-info 90_percent" >' ;
			echo "<a data-toggle=\"collapse\" data-target=\"#collapse_help_header\" >PSEUDO CODE FOR HEADER PHP - Later here will be the helptext&nbsp;<i class=\"fa fa-chevron-down\"></i></a>";
			echo "<div id=\"collapse_help_header\" class=\"collapse\"> ";
			include_once '_header_helptxt.inc.php';
			echo "</div>";
		echo "</div><p></p><p></p>";
		
}
?>



