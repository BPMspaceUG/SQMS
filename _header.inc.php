<?php
  // Includes
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
<!DOCTYPE html>
<html ng-app="SQMSApp">
<head>
  <meta charset="UTF-8">
  <title>BPMspace SQMS</title>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/xeditable.css">
  <link rel="stylesheet" href="../css/select.min.css">
  <link rel="stylesheet" href="css/SQMS.css">
  <!-- JS -->
  <script type="text/javascript" src="../js/angular.min.js"></script>
  <script type="text/javascript" src="../js/angular-sanitize.min.js"></script>
  <script type="text/javascript" src="../js/ui-bootstrap-1.3.1.min.js"></script>
  <script type="text/javascript" src="../js/ui-bootstrap-tpls-1.3.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script> 
  <script type="text/javascript" src="../js/tinymce/jquery.tinymce.min.js"></script>
  <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>  
  <script type="text/javascript" src="../js/tinymceng.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/xeditable.min.js"></script>
  <script type="text/javascript" src="../js/select.min.js"></script>
  <script type="text/javascript" src="js/SQMS.js"></script>  
  <script type="text/javascript" src="js/sqms_modalcontroller.js"></script>
  <script type="text/javascript" src="js/sqms_maincontroller.js"></script>
</head>
<body ng-controller="SQMSController">
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
  <?php
    include_once("inc/RequestHandler.inc.php");    
    $rm = new RoleManager();
  ?>
  <div class="container">
    <nav class="navbar navbar-light bg-faded">
      <ul class="nav nav-tabs">
        <?php
          if ($rm->isActUserAllowed("menu_dashboard"))
            echo '<li><a title="Dashboard" href="#pagedashboard" data-toggle="tab"><i class="fa fa-tachometer"></i>&nbsp;Dashboard</a></li>';
          if ($rm->isActUserAllowed("menu_syllabus"))
            echo '<li><a title="Show Syllabus" href="#pagesyllabus" data-toggle="tab"><i class="fa fa-table"></i>&nbsp;Syllabus</a></li>';
          if ($rm->isActUserAllowed("menu_question"))
            echo '<li><a title="Show all questions" href="#pagequestion" data-toggle="tab"><i class="fa fa-question"></i>&nbsp;Question</span></a></li>';
          if ($rm->isActUserAllowed("menu_topic"))
            echo '<li><a title="Show all Topics" href="#pagetopic" data-toggle="tab"><i class="fa fa-table"></i>&nbsp;Topic</a></li>';
        ?>
      </ul>
    </nav>
  </div>
  <!--------------- END MAIN MENU --------->
