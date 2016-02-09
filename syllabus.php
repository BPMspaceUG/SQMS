<?php
	// Includes
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';

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
	<div class="panel panel-default" ng-repeat="phone in phones | filter:{'sqms_syllabus_id':  '104'}:true">
	  <div class="panel-body">
		<!-- Navigation-Syllabus -->
		<ul class="nav nav-tabs">
		  <li role="presentation" class="active"><a href="#">General</a></li>
		  <li role="presentation"><a href="#">Element</a></li>
		  <li role="presentation"><a href="#">History</a></li>
		</ul>
		<br/>
		<?php
			// Attention: Secure Input
			// TODO: Make in Javascript bzw. AJAX
			switch ($_GET['tab']) {
				case "general":
					include_once("syllabus_general.inc.php");
					break;
					
				case "elements":
					include_once("syllabus_element.inc.php");
					break;
					
				default:
					include_once("syllabus_general.inc.php");
					break;
			}
		?>
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
<?php
	include_once '_footer.inc.php';
?>