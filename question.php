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
				<th>Question</th>
				<th>Author</th>
				<th>Version</th>
				<th>External ID</th>
				<th>Topic</th>
				<th>?</th>
				<th>?</th>
				<th>Question Type</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="question in questions | filter:filtertext"
				ng-click="setSelected(question)"
				ng-class="{success: question.sqms_question_id === actQuestion.sqms_question_id}">
				<td><checkbox>#</checkbox></td>
				<td>{{question.sqms_question_id}}</td>
				<td>{{question.question}}</td>
				<td>{{question.author}}</td>
				<td>{{question.version}}</td>
				<td>{{question.id_external}}</td>
				<td>{{question.sqms_topic_id}}</td>
				<td>?</td>
				<td>?</td>
				<td>{{question.sqms_question_type_id}}</td>
			</tr>
		</tbody>
	</table>
	<div class="well">
		<p>Actual Element:</p>
		<form>
			<b>ID: {{actQuestion.sqms_question_id}}</b><br/>
			<b>Version: {{actQuestion.version}}</b><br/>
			<b>Author: {{actQuestion.author}}</b><br/>
			<b>Question:</b>
			<input type="text" name="f_name" data-ng-model="actQuestion.question" placeholder="Question" required />
			<br />
			<br />
			<input type="button" value="Create" data-ng-click="createQuestion()" />
			<input type="button" value="Update" data-ng-click="updateQuestion()" />
			<input type="button" value="Delete" data-ng-click="deleteQuestion()" />
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
			$http.get('getjson.php?c=questions').success(function(data) {
				$scope.questions = data.questionlist;
			});
		}
		
		// WRITE
		$scope.writeData = function (command) {
			$scope.status = "Sending command...";
			$http({
				url: 'getjson.php?c=' + command,
				method: "POST",
				data: JSON.stringify($scope.actQuestion)
			}).
			success(function(data){
				$scope.status = "Executed command successfully! Return: " + data;
				$scope.getData(); // Refresh data
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		$scope.createQuestion = function () { $scope.writeData('create_question'); } // CREATE		
		$scope.updateQuestion = function () { $scope.writeData('update_question'); } // UPDATE		
		$scope.deleteQuestion = function () { $scope.writeData('delete_question'); } // DELETE
		
		$scope.getData(); // Load data at start
		
		// initial selected data
		$scope.actQuestion = {
			sqms_question_id: 0,
			question: ''
		};
		
		$scope.setSelected = function (selElement) {
		   $scope.actQuestion = selElement;
		};
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
