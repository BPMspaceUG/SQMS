<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Order #</th>
			<th>Name</th>
			<th>Severity</th>
			<th>Description</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr class="success" ng-repeat="phone in phones | filter:yourName">
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