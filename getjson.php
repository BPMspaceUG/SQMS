<?php
	// Includes
	include_once '_dbconfig.inc.php';
	include_once './inc/RequestHandler.inc.php';
	include_once '../DB_config/login_credentials_DB_bpmspace_sqms.inc.php';
	include_once './inc/functions.inc.php';

	// Command (via GET)
	if (isset($_GET["c"]))
		$command = htmlspecialchars($_GET["c"]);
	else
		die("no data");
	
	// Angular transmits data in JSON Format
	$params = json_decode(file_get_contents('php://input'), true);
	
	// RequestHandler from EduMS
	$handler = new RequestHandler(); // Maybe: DB-Connection parameter required
	$content = $handler->handle($command, $params);
	
	// Return data
	if ($content == "" || $content == "goaway")
		http_response_code(400); // Bad Request
	else
		echo $content;
	
	/****************************************************/
	// TODO:
	
	// Implement datevis in client (only read out min-date until now)
	// --- More info: http://cal-heatmap.com/
?>