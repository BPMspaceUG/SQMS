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

  $result = true; // Transistion is allowed
  
  // Read out state info
  $actstate = $actstateObj[0]["name"];
  $newstate = $newstateObj[0]["name"];
    
  /***********************************************************/
  // Set message
  if ($result)
    $message = "Transition from [$actstate] to [$newstate].";
  
  $script_result = array(
    "allow_transition" => $result, // Transition allowed?
    "show_message" => false,
    "message" => $message
  );
?>