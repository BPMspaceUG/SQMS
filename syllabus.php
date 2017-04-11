<?php
  // Includes
  include_once '../DB_config/login_credentials_DB_bpmspace_sqms.inc.php';
  include_once '_header.inc.php';
?>
<!-- Page -->
<div class="container">
  <div class="tab-content">
    <?php
      // ########################################## Page: Dashboard
      include_once("page_dashboard.html");
      // ########################################## Page: Syllabus
      include_once("page_syllabus.html"); 
      // ########################################## Page: Question
      include_once("page_question.html"); 
      // ########################################## Page: Topic
      include_once("page_topic.html");
    ?>
  </div>
</div>
<!-- Footer -->
<?php
  // Angular Templates
  include_once("templates.html"); // Include all HTML templates for AngularJS
  include_once("tmpl_syllabus.html");
  include_once("tmpl_question.html");
  // Footer
  include_once '_footer.inc.php';
?>
