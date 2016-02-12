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
-->
<!--------------- END SUB MENU --------->
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h2 style="margin:0;">Syllabus Elements</h2>
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
				<th>Order</th>
				<th>Name (english)</th>
				<th>Syllabus</th>
				<th>Severity</th>
				<th>block</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="syllabuselement in syllabuselements | filter:filtertext"
				ng-click="setSelected(syllabuselement)"
				ng-class="{success: syllabuselement.sqms_syllabus_element_id === actSyllabusElement.sqms_syllabus_element_id}">
				<td><checkbox>#</checkbox></td>
				<td>{{syllabuselement.sqms_syllabus_element_id}}</td>
				<td>{{syllabuselement.element_order}}</td>
				<td>{{syllabuselement.name}}</td>
				<td>{{syllabuselement.sqms_syllabus_id}}</td>
				<td>{{syllabuselement.severity}}</td>
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
		
		// READ
		$scope.getData = function () {			
			$http.get('getjson.php?c=syllabuselements').success(function(data) {
				$scope.syllabuselements = data.syllabuselements;
			});
		}
		
		// WRITE
		$scope.writeData = function (command) {
			$scope.status = "Sending command...";
			$http({
				url: 'getjson.php?c=' + command,
				method: "POST",
				data: JSON.stringify($scope.actSyllabusElement)
			}).
			success(function(data){
				$scope.status = "Executed command successfully! Return: " + data;
				$scope.getData(); // Refresh data
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		$scope.createSyllabusElement = function () { $scope.writeData('create_syllabuselement'); } // CREATE		
		$scope.updateSyllabusElement = function () { $scope.writeData('update_syllabuselement'); } // UPDATE		
		$scope.deleteSyllabusElement = function () { $scope.writeData('delete_syllabuselement'); } // DELETE
		
		$scope.getData(); // Load data at start
		
		// initial selected data
		$scope.actSyllabusElement = {
			sqms_syllabus_element_id: 0,
			name: ''
		};
		
		$scope.setSelected = function (selElement) {
		   $scope.actSyllabusElement = selElement;
		};
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>