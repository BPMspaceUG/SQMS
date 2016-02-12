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
<!--
<div class="clearfix"></div>
<div class="container 90_percent" >
	<a href="#" onclick="test();" class="btn btn-success" title='Add new Syllabus'><i class="fa fa-plus"></i>&nbsp;New</a>
	<a href="#" class="btn btn-success" title='Add new Event'><i class="fa fa-plus"></i>&nbsp;Copy</a>  
	<a href="#" class="btn btn-danger" title='Add new Organization'><i class="fa fa-minus"></i>&nbsp;Delete</a>  
	<a href="#" title='switch help on/off' class="btn btn-large btn-default navbar-right">
		<span class="fa-stack">
			<i class="fa fa-question fa-stack-1x"></i>
			<i class="fa fa-ban fa-stack-1x text-danger"></i>
		</span>Help</a>
</div>
<div class="clearfix"></br></div>
-->
<!--------------- END SUB MENU --------->
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h2 style="margin:0;">Syllabi</h2>
		</div>
		<div class="col-sm-4">
			<input type="text" ng-model="filtertext" class="form-control pull-right" style="width:200px;" placeholder="filter">
		</div>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				<th>State</th>
				<th>Version</th>
				<th>Topic</th>
				<th>Owner</th>
				<th>block</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="syllabus in syllabi | filter:filtertext"
				ng-click="setSelected(syllabus)"
				ng-class="{success: syllabus.sqms_syllabus_id === actSyllabus.sqms_syllabus_id}">
				<td><checkbox>#</checkbox></td>
				<td>{{syllabus.sqms_syllabus_id}}</td>
				<td>{{syllabus.sqms_state_id}}</td>
				<td>{{syllabus.version}}</td>
				<td>{{syllabus.sqms_topic_id}}</td>
				<td>{{syllabus.owner}}</td>
				<td>{{syllabus.validity_period_from}} ?</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- AngularJS -->
<script>
	'use strict';
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', []);

	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
		
		// READ
		$scope.getData = function () {
			$http.get('getjson.php?c=syllabus').success(function(data) {
				$scope.syllabi = data.syllabus;
				$scope.topics = data.topiclist;
				// TODO: owner
			});
		}
		
		// WRITE
		$scope.writeData = function (command) {
			$scope.status = "Sending command...";
			$http({
				url: 'getjson.php?c=' + command,
				method: "POST",
				data: JSON.stringify($scope.actSyllabus)
			}).
			success(function(data){
				$scope.status = "Executed command successfully! Return: " + data;
				$scope.getData(); // Refresh data
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		$scope.createSyllabus = function () { $scope.writeData('create_syllabus'); } // CREATE		
		$scope.updateSyllabus = function () { $scope.writeData('update_syllabus'); } // UPDATE		
		$scope.deleteSyllabus = function () { $scope.writeData('delete_syllabus'); } // DELETE
		
		$scope.getData(); // Load data at start
		
		// initial selected data
		$scope.actSyllabus = {
			sqms_syllabus_id: 0,
			name: ''
		};
		
		$scope.setSelected = function (selElement) {
		   $scope.actSyllabus = selElement;
		};
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
