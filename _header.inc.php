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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="SQMSApp">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>BPMspace SQMS</title>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" media="screen">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <!--<link rel="stylesheet" href="../css/fuelux.min.css">-->
  <link rel="stylesheet" href="../css/xeditable.css">
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
  <script type="text/javascript" src="js/SQMS.js"></script>
  <!----- js scripts are loaded in the footer --------------------> 
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
    /*
    $roleIDs = $rm->getRoleIDsByLIAMid($_SESSION['user_id']); // user_id = LIAM ID
    echo "LiamID: ".$_SESSION['user_id']."<br/>Roles: ";
    //var_dump($roleIDs);
    if ($roleIDs) echo implode(", ", $roleIDs);
    //var_dump($roles);
    //echo "Logged in as [Lastname: <b>".$_SESSION['lastname'].", UserID: ".$_SESSION['user_id'].", Roles: ".print_r($roles)."]</b><br/>";
    */
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
          if ($rm->isActUserAllowed("menu_language"))
            echo '<li><a title="Show all Languages" href="#pagelanguage" data-toggle="tab"><i class="fa fa-language"></i>&nbsp;Language</a></li>';
        ?>
      </ul>
    </nav>
  </div>
  <!--------------- END MAIN MENU --------->
  <?php
    /* presente $error_messages when not empty */
    /*
    if (!empty($_GET["error_messages"])) {
      echo '<div class="container alert alert-danger 90_percent" role="alert"> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>' ;
      echo '&nbsp;error:&nbsp;' . htmlspecialchars($_GET["error_messages"]);
      echo '</br></div>';
    }
    $help_text = null; // TODO: Remove!!
    */
    /* presente file with helptxt if $help_text = "true" (or set) when not empty */
    /*
    if ($help_text) {
      echo '<div class="container bg-info 90_percent" >' ;
        echo "<a data-toggle=\"collapse\" data-target=\"#collapse_help_header\" >PSEUDO CODE FOR HEADER PHP - Later here will be the helptext&nbsp;<i class=\"fa fa-chevron-down\"></i></a>";
        echo "<div id=\"collapse_help_header\" class=\"collapse\"> ";
        include_once '_header_helptxt.inc.php';
        echo "</div>";
      echo "</div><p></p><p></p>";
    }
    */
  ?>



