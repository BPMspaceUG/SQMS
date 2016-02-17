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
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="phonecatApp">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>BPMspace SQMS</title>
	<!-- CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="http://www.fuelcdn.com/fuelux/3.13.0/css/fuelux.min.css">
	<link rel="stylesheet" href="custom/custom.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.js"></script>
	<!----- js scripts are loaded in the footer --------------------> 
</head>
<body ng-controller="PhoneListCtrl">
	<div class="container">
		<div class="container">
			<div class="col-md-12"><?php include_once '../_header_LIAM.inc.php'; ?></div>
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
	<div class="container">
		<nav class="navbar navbar-light bg-faded">
			<ul class="nav navbar-nav">
				<li class="active">
					<a href="dashboard.php" title='Dashboard'><i class="fa fa-tachometer"></i>&nbsp;Dashboard</a>
				</li>
				<li>
					<a title='Show Syllabus' href="syllabus.php"><i class="fa fa-table"></i>&nbsp;Syllabus</a>
				</li>
				<li>
					<a title='Show Syllabus Elemtents' href="syllabuselement.php">
						<i class="fa fa-table"></i>&nbsp;Syllabus-Element
					</a>
				</li>
				<li>
					<a title='Show all questions' href="question.php">
						<i class="fa fa-question"></i>&nbsp;Question</span>
					</a>
				</li>
				<li>
					<a href="topic.php" title='Show all Topics'><i class="fa fa-table"></i>&nbsp;Topic</a>
				</li>
				<li class="dropdown">
					<a title='Admin' class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-user-secret"></i>&nbsp;Admin<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="user.php" title='user'><i class="fa fa-user-plus"></i>&nbsp;Language</a></li>
						<li><a href="topic.php" title='Topic'><i class="fa fa-folder-o"></i>&nbsp;Topic</a></li>
						<li><a href="trainer.php" title='Trainer'><i class="fa fa-graduation-cap"></i>&nbsp;Trainer</a></li>
						<li><a href="course.php" title='Course'><i class="fa fa-university"></i>&nbsp;Course</a></li>
					</ul>
				</li> 		
				<li>
					<a href="../phpSecureLogin/includes/logout.php" title='Logout'>
						<i class="fa fa-sign-out"></i>&nbsp;Logout
					</a>
				</li>
			</ul>
		</nav>
	</div>
<?php
	/* presente $error_messages when not empty */
	if (!empty($_GET["error_messages"])) {
		echo '<div class="container alert alert-danger 90_percent" role="alert"> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>' ;
		echo '&nbsp;error:&nbsp;' . htmlspecialchars($_GET["error_messages"]);
		echo '</br></div>';
	}
?>
<!--------------- END MAIN MENU --------->
<?php
	$help_text = null; // TODO: Remove!!
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



