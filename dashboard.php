<?php
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
?>
<div class="clearfix"></div>
<div class="container">
	<p>example <a href="http://bootstrapmaster.com/live/perfectum/">Dashboard Example</a></p>
	<div class="row" ng-repeat="report in reports">
		<div class="col-sm-3">
			<div class="well text-center">
				<!--<i class="fa fa-tachometer fa-5x text-warning"></i>-->
				<h2>{{report.NrOfQuestionsWOQmarks}}</h2>Questions without Questionmarks
			</div>
		</div>
	</div>
</div>
<!-- AngularJS -->
<script>
	'use strict';
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', []);

	// TODO: Read all reports at the same time
	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
	  $http.get('getjson.php?c=report_questionswithoutquestionmarks').success(function(data) {
		$scope.reports = data.reports;
	  });
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
 