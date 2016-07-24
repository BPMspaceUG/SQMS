<?php
  /************************************************************
    Transistion Script - Will be executed when changing from
    one state to another state
  *************************************************************
    $result variable can be set if the transition should be
    allowed or not. Set $result to TRUE if transition should
    be allowed and set $result to FALSE if transition should
    be disallowed.
  
    To give feedback back to the user, you can set a text in
    the "result" field of $script_result variable.
  ************************************************************/

  $result = false; // Transistion is not allowed
  
  // Read out state info
  $actstate = $actstateObj[0]["name"];
  $newstate = $newstateObj[0]["name"];
  
  // If SyllabusID is even then allow transition
  if ($syllabid % 2 == 0) {
    $result = true; // Transistion is allowed
  }
  
  // TODO: Check parameters like if entered text is valid
  
  /***********************************************************/
  // Set message
  if ($result)
    $message = "Transition from [$actstate] to [$newstate] is allowed (ID is even).";
  else
    $message = "Transition from [$actstate] to [$newstate] is not allowed if SyllabusID odd.";
  
  $script_result = array(
    "result" => $result,
    "message" => $message);
?>