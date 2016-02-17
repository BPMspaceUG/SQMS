<?php
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
?>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h2 style="margin:0;">Topics</h2>
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
				<th>Name</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="topic in topics | filter:filtertext" ng-click="setSelected(topic)" ng-class="{success: topic.sqms_topic_id === actTopic.sqms_topic_id}">
				<td><checkbox>#</checkbox></td>
				<td>{{topic.sqms_topic_id}}</td>
				<td>{{topic.name}}</td>
			</tr>
		</tbody>
	</table>
	<div class="well">
		<p>Actual Element:</p>
		<form>
			<b>ID: {{actTopic.sqms_topic_id}}</b><br/>
			<b>Name:</b>
			<input type="text" name="f_name" data-ng-model="actTopic.name" placeholder="Name" required />
			<br />
			<input type="button" value="Create" data-ng-click="createTopic()" />
			<input type="button" value="Update" data-ng-click="updateTopic()" />
			<input type="button" value="Delete" data-ng-click="deleteTopic()" />
			<br />
			<p>Status: {{status}}</p>
		</form>
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
			$http.get('getjson.php?c=topics').success(function(data) {
				$scope.topics = data.topiclist;
			});
		}
		
		// WRITE
		$scope.writeData = function (command) {
			$scope.status = "Sending command...";
			$http({
				url: 'getjson.php?c=' + command,
				method: "POST",
				data: JSON.stringify($scope.actTopic)
			}).
			success(function(data){
				$scope.status = "Executed command successfully! Return: " + data;
				$scope.getData(); // Refresh data
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		$scope.createTopic = function () { $scope.writeData('create_topic'); } // CREATE		
		$scope.updateTopic = function () { $scope.writeData('update_topic'); } // UPDATE		
		$scope.deleteTopic = function () { $scope.writeData('delete_topic'); } // DELETE
		
		$scope.getData(); // Load data at start
		
		// initial selected data
		$scope.actTopic = {
			sqms_topic_id: 0,
			name: ''
		};
		
		$scope.setSelected = function (selElement) {
		   $scope.actTopic = selElement;
		};
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
