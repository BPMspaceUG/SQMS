<?php
  include_once '../DB_config/login_credentials_DB_bpmspace_sqms.inc.php';

  try
  {
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e)
  {
    echo $e->getMessage();
  }
  
  include_once '_class_crud.inc.php';
  $crud = new crud($DB_con);
?>