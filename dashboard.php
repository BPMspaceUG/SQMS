<?php
	include_once '_dbconfig.inc.php';
	include_once '_header.inc.php';
?>
<div class="clearfix"></div>
<div class="container">
	<p>example <a href="http://bootstrapmaster.com/live/perfectum/">Dashboard Example</a></p>
	<div class="row">
	
		<!-- Report -->
		<div class="col-lg-3 col-md-6" ng-repeat="report in reports">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa {{report.icon}} fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{{report.value}}</div>
							<div>{{report.attr}}</div>
						</div>
					</div>
				</div>
				<!-- TODO: Link to Elements
				<a href="#">
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
				-->
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
		$http.get('getjson.php?c=getreports').success(function(data) {
			$scope.reports = data.reports;
		});	
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
 