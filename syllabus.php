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
<div class="container">
	<?php
		// RequestHandler from EduMS
		$routes = array("","","location");
	
		// Transmit login data to RequestHandler object
		$handler = new RequestHandler("partner1", "abc", $db); // [user, token, db] - TODO: Use real data - fo now only test data
		$content = $handler->handle($routes);
		
		// Convert data from database into JSON Format
		$jsdata = json_encode($content);
		
		// Write JSON Data into file -> not the best solution probably
		file_put_contents("data.json", $jsdata);
			
		// Copy JSON Data to client source code
		/*
		echo "<script>";
		//include_once("test.js");
		echo "var data = '".$jsdata."';";
		//echo "$scope.phones";
		//echo "alert(data);";
		echo "</script>";
		*/
	?>
	<div class="panel panel-default">
	  <div class="panel-body">
		<!-- Navigation-Syllabus -->
		<ul class="nav nav-tabs">
		  <li role="presentation" class="active"><a href="#">General</a></li>
		  <li role="presentation"><a href="#">Element</a></li>
		  <li role="presentation"><a href="#">History</a></li>
		</ul>
		<ul>
			<li>ID</li>
			<li>Version</li>
			<li>Validity period: from + to</li>
			<li>Name</li>
			<li>Topic<select class="form-control">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select></li>
			<li>Owner: <select class="form-control">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select></li>
			<li>Group</li>
			<li>Predecessor</li>
			<li>Successor</li>
			<hr>
			<h3>Description</h3>
			<textarea class="form-control" rows="3"></textarea>
			<form class="form-inline">
				<p>State:</p><select style="width: 200px;" class="form-control">
				  <option>valid & public</option>
				  <option>2</option>
				  <option>3</option>
				  <option>4</option>
				  <option>5</option>
				</select>
				<input class="btn btn-default" type="submit" value="Save & unblock">
				<input class="btn btn-default" type="submit" value="Save">
				<input class="btn btn-default" type="submit" value="Unblock w/o save">
			</form>
		</ul>
	  </div>
	</div>
	<!-- List of Syllabuses -->
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">

	</div>
	<h1>Filter {{yourName}}!</h1>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Syllabus ID</th>
				<th>Syllabus State</th>
				<th>Syllabus Version</th>
				<th>Syllabus Topic</th>
				<th>Syllabus Owner</th>
				<th>block</th>
			</tr>
		</thead>
		<tbody>
			<tr class="success" ng-repeat="phone in phones | filter:yourName">
				<td><checkbox>#</checkbox></td>
				<td>{{phone.event_id}}</td>
				<td>{{phone.course_name}}</td>
				<td>{{phone.location_name}}</td>
				<td>{{phone.start_date}}</td>
				<td>Max Muster</td>
				<td>{{phone.location_id}}</td>
			</tr>
		</tbody>
	</table>
</div>
</div>

