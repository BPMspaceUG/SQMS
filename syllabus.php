<?php
	// Includes
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
	// TODO: Move file RequestHandler to LIAM dir
	include_once '../EduMS/api/RequestHandler.inc.php';
	include_once '../DB_config/login_credentials_DB_bpmspace_edums_API.inc.php';
	include_once '../EduMS/api/functions.inc.php';

	/* presente $help_text when not empty */
	if ($help_text) {
		echo '<div class="container bg-info 90_percent" >' ;
			echo "<a data-toggle=\"collapse\" data-target=\"#collapse_help_event\" >PSEUDO CODE FOR EVENT_GRID PHP - Later here will be the helptext&nbsp;<i class=\"fa fa-chevron-down\"></i></a>";
			echo "<div id=\"collapse_help_event\" class=\"collapse\"> ";
			include_once 'syllabus_helptxt.inc.php';
			echo "</div>";
		echo "</div><p></p><p></p>";		
	}
?>
<div class="clearfix"></div>
<div class="container 90_percent">
	<?php
		// RequestHandler from EduMS
		$routes = array("","","location");
	
		// Transmit login data to RequestHandler object
		$handler = new RequestHandler("partner1", "abc", $db); // [user, token, db] - TODO: Use real data - fo now only test data
		$content = $handler->handle($routes);
		
		// Convert data from database into JSON Format
		$jsdata = json_encode($content);
		
		// only for testing
		echo "<pre>";
		echo $jsdata;
		echo "</pre>";
		
		// Copy JSON Data to client source code
		echo "<script>var mydata = '$jsdata';</script>";
	?>
</div>
</div>

