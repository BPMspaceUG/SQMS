<?php
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
?>
<div class="clearfix"></div>
<div class="container">
	<h2>Questions</h2>
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">
	</div>
	<table class="table table-striped table-hover">
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
			<tr ng-repeat="question in questions | filter:yourName">
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
</div>
<!-- AngularJS -->
<script>
	'use strict';
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', []);

	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
	  $http.get('getjson.php?c=questions').success(function(data) {
		$scope.questions = data.questionlist;
	  });
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
