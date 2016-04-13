<?php
	echo "<script>alert('test! Time=" . time()."');</script>";
  file_put_contents("functions/tempfile".time().".txt", time());
?>