<?php
	// Includes
	include_once '_dbconfig.inc.php';
	include_once './inc/RequestHandler.inc.php';
	include_once '../DB_config/login_credentials_DB_bpmspace_sqms.inc.php';
	include_once './inc/functions.inc.php';

	// Attention! Make Secure
	if (isset($_GET["c"]))
		$command = htmlspecialchars($_GET["c"]);
	else
		die("no data");
	
	// RequestHandler from EduMS
	$routes = array($command); // testing
	$handler = new RequestHandler(); // Maybe: DB-Connection parameter required
	$content = $handler->handle($routes);
	// Convert data from database into JSON Format
	$jsdata = json_encode($content);
	// Return data
	echo $jsdata;
?>