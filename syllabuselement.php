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
<!--------------- SUB MENU --------->
<div class="clearfix"></div>
<div class="container 90_percent" >
	<a href="#" class="btn btn-success" title='Add new Participant'><i class="fa fa-plus"></i>&nbsp;New</a>
	<a href="#" class="btn btn-success" title='Add new Event'><i class="fa fa-plus"></i>&nbsp;Copy</a>  
	<a href="#" class="btn btn-danger" title='Add new Organization'><i class="fa fa-minus"></i>&nbsp;Delete</a>  
	<a href="#" title='switch help on/off' class="btn btn-large btn-default navbar-right">
		<span class="fa-stack">
			<i class="fa fa-question fa-stack-1x"></i>
			<i class="fa fa-ban fa-stack-1x text-danger"></i>
		</span>Help</a>
</div>
<div class="clearfix"></br></div>
<!--------------- END SUB MENU --------->
<div class="container">
	<?php
		//include_once("syllabus_general.inc.php");
	?>
	<!-- List of Syllabus Elements -->
	<h2>Syllabus Elements</h2>
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">
	</div>	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				<th>Order</th>
				<th>Name (english)</th>
				<th>Syllabus</th>
				<th>Severity</th>
				<th>block</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="phone in phones | filter:yourName">
				<td><checkbox>#</checkbox></td>
				<td>{{phone.sqms_syllabus_element_id}}</td>
				<td>{{phone.element_order}}</td>
				<td>{{phone.name}}</td>
				<td>{{phone.sqms_syllabus_id}}</td>
				<td>{{phone.severity}}</td>
				<td>?</td>
				<td>?</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
<!-- AngularJS -->
<script>
	'use strict';
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', []);

	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
	  $http.get('getjson.php?c=syllabuselements').success(function(data) {
		$scope.phones = data.syllabuselements;
	  });
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>