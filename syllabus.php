<?php
	// Includes
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
	include_once './inc/RequestHandler.inc.php';
	include_once '../DB_config/login_credentials_DB_bpmspace_sqms.inc.php';
	include_once './inc/functions.inc.php';

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
		$routes = array("syllabus"); // testing
		$handler = new RequestHandler(); // TODO: DB-Connection parameter required
		$content = $handler->handle($routes);
		//var_dump($content);
		// Convert data from database into JSON Format
		$jsdata = json_encode($content);
		// Write JSON Data into file -> not the best solution probably
		file_put_contents("data.json", $jsdata);
	?>
	<input type="hidden" name="form_id" value="104" ng-model="search.sqms_syllabus_id">
	<div class="panel panel-default" ng-repeat="phone in phones | filter:search">
	  <div class="panel-body">
		<!-- Navigation-Syllabus -->
		<ul class="nav nav-tabs">
		  <li role="presentation" class="active"><a href="#">General</a></li>
		  <li role="presentation"><a href="#">Element</a></li>
		  <li role="presentation"><a href="#">History</a></li>
		</ul>
		<br/>
		<div class="row">
			<div class="col-sm-6">
				<div class="col-sm-4">
					<p>ID</p>
					<p>Version</p>
					<p>Name</p>
					<p>Validity period</p>
					<p>Topic</p>
				</div>
				<div class="col-sm-8">
					<p>{{phone.sqms_syllabus_id}}</p>
					<p>{{phone.version}}</p>
					<p>{{phone.name}}</p>
					<p>{{phone.validity_period_from}} to {{phone.validity_period_to}}</p>
					<p><select class="form-control">
				  <option>{{phone.sqms_topic_id}}</option>
				  <option>2</option>
				  <option>3</option>
				  <option>4</option>
				  <option>5</option>
				</select></p>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="col-sm-4">
					<p>Owner</p>
					<p>Group</p>
					<p>Predecessor</p>
					<p>Successor</p>
				</div>
				<div class="col-sm-8">
					<p><select class="form-control">
					  <option>{{phone.owner}}</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select></p>
					<p><select class="form-control">
					  <option>1</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select></p>
					<p>{{phone.sqms_syllabus_id_predecessor}}</p>
					<p>{{phone.sqms_syllabus_id_successor}}</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h5>Description</h5>
				<textarea class="form-control" rows="3">{{phone.description}}</textarea>
			</div>
			<br/>
			<div class="col-sm-4">
				State:
			</div>
			<div class="col-sm-8">
				<form class="form-inline">					
					<select style="width: 200px;" class="form-control">
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
	  </div>
	</div>
	<!-- List of Syllabuses -->
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">
	</div>
	<h2>Syllabi</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>&nbsp;</th>
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
				<td>{{phone.sqms_syllabus_id}}</td>
				<td>{{phone.sqms_state_id}}</td>
				<td>{{phone.version}}</td>
				<td>{{phone.sqms_topic_id}}</td>
				<td>{{phone.owner}}</td>
				<td>{{phone.validity_period_from}} ?</td>
			</tr>
		</tbody>
	</table>
</div>
</div>

