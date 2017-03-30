<?php
  /************************************************************
    Transistion Script - Will be executed when changing from
    one state to another state
  *************************************************************
    This plugin does the following steps

    1. Get current item
    2. Look if there is any predecessor in DB
    3. if the current item transition is from
      [ready] -> [released] then set the predecessor to deprecated

  ************************************************************/

  // INPUT VARIABLES
  $actstate = $actstateObj[0]["name"];
  $newstate = $newstateObj[0]["name"];
  $ID = $ElementID; // Id of the current element (Syllabus or Question ID)
  $DB = $db; // Database Object
    
  /***********************************************************/

  // [1] Make query
  $query = "SELECT COUNT(*) AS cnt FROM sqms_syllabus;";
  $res = $this->db->query($query);
  // Read out entries
  $r = array();
  while ($row = $res->fetch_assoc()) {
    $r[] = $row;
  }
  //var_dump($results_array);

  // Select predecessor from ID

  // If result is not empty -> update

  // Set message
  $message = "Sorry this plugin is in development. Please try later! res = ".$r[0]['cnt'];

  $script_result = array(
    "allow_transition" => false, // Transition allowed?
    "show_message" => true,
    "message" => $message
  );
?>