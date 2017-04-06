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
  $DB = $db; // Copy of Database Element
  $roottable = strtolower($roottable); // Roottable (i.e. sqms_syllabus)
    
  /***********************************************************/

  // Functions
  function updateCol($id, $column, $type, $content, $table, $whereCol) {
    global $DB;
    $query = "UPDATE $table SET $column = ? WHERE $whereCol = ?;";
    $stmt = $DB->prepare($query); // prepare statement
    $stmt->bind_param($type."i", $content, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }

  // Init
  $message = "";
  $isSyllabus = false;
  $isQuestion = false;
  $allowtrans = false;

  // [1] Check if syllabus or question
  if (strpos($roottable, "syllabus") >= 0) $isSyllabus = true;
  if (strpos($roottable, "question") >= 0) $isQuestion = true;

  if ($isSyllabus || $isQuestion) {
    // Plugin supports this tables

    // Look for all elements where the successor is the current element id
    $query = "SELECT * FROM $roottable WHERE sqms_syllabus_id_successor = $ID;";
    $res = $DB->query($query);

    // Read out entries
    if ($res) {
      // Predecessors found
      $r = array();
      while ($row = $res->fetch_array(MYSQLI_NUM)) {
        $r[] = $row;
      }      
      $message .= "Found ".count($r)." predecessors.\n";

      // Set to deprecated
      $cnt = 0;
      foreach ($r as $pred) {
        $predecessorID = (int)$pred[0];
        // Update the old elements
        if ($isSyllabus) {
          // Set old Syllabus to deprecated
          $query = "UPDATE $roottable SET sqms_state_id = 4 WHERE sqms_syllabus_id = $predecessorID;";
          $res = $DB->query($query);
          if ($res) $cnt += 1;
        }
        else if ($isQuestion) {
          // Set old Syllabus to deprecated
          $query = "UPDATE $roottable SET sqms_question_state_id = 4 WHERE sqms_question_id = $predecessorID;";
          $res = $DB->query($query);
          if ($res) $cnt += 1;
        }        
      }
      $message .= "$cnt predecessors where set to deprecated.";
      $allowtrans = true;
    }
    else {
      // No predessecors found
      $message .= "No predecessor found.\nSuccessfully released.";
      $allowtrans = true;
    }
  }
  else {
    // Plugin does not support other tables
    $message .= "This Plugin does not support other tables than Syllabus and Question!\n";
    $allowtrans = false;
  }

  // OUTPUT
  $script_result = array(
    "allow_transition" => $allowtrans, // Transition allowed?
    "show_message" => true,
    "message" => $message
  );
?>