<?php

  $result = false;
  if ($syllabid % 2 == 0)
    $result = true;

  $script_result = array("result" => $result, "message" => "Übergang von NEW auf READY");

  //echo "<script>alert('test! Time=" . time()."');</script>";
  //file_put_contents("functions/tempfile".time().".txt", time());
  //echo json_encode(array("GUI_return" => $script_result));
?>