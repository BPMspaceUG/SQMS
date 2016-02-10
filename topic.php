<?php
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
?>
<div class="clearfix"></div>
<div class="container">
	<h2>Topics</h2>
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">
	</div>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				<th>Name</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="topic in topics | filter:yourName">
				<td><checkbox>#</checkbox></td>
				<td>{{topic.sqms_topic_id}}</td>
				<td>{{topic.name}}</td>
			</tr>
		</tbody>
	</table>
	<p>Create new topic:</p>
	<form>
		<b>Name:</b>
		<input type="text" name="Name" id="Name" data-ng-model="newperson.Name" placeholder="Name" required />
		<br />
		<br />
		<input type="button" value="Save" data-ng-show="DisplaySave" data-ng-click="createTopic()" />
		<br />
		<p>{{status}}</p>
    </form>
</div>
<!-- AngularJS -->
<script>
	'use strict';
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', []);

	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
		
		// get data her
		$http.get('getjson.php?c=topics').success(function(data) {
			$scope.topics = data.topiclist;
		});
		
		//For creating a new person
		$scope.createTopic = function () {
			$scope.status = "Sending...";
			$http({
				url: 'getjson.php?c=create_topic',
				method: "POST",
				data: JSON.stringify($scope.newperson)
			}).
			success(function(data){
				$scope.status = "Successfully added!";
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		
		// Display
		$scope.DisplaySave = true;
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
