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
	<a href="#" onclick="test();" class="btn btn-large btn-success" title='Add new Syllabus'><i class="fa fa-plus"></i>&nbsp;Create</a>
	<!--<a href="#" class="btn btn-success" title='Add new Event'><i class="fa fa-plus"></i>&nbsp;Copy</a>--> 
	<a href="#" title='switch help on/off' class="btn btn-large btn-default navbar-right">
		<span class="fa-stack">
			<i class="fa fa-question fa-stack-1x"></i>
			<i class="fa fa-ban fa-stack-1x text-danger"></i>
		</span>Help</a>
</div>
<div class="clearfix"></br></div>
<!--------------- END SUB MENU --------->
<div class="container">

	<div id="test" class="modal" role="dialog" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Edit syllabus</h4>
				</div>
				<div class="modal-body">
					<div compile="formdata"></div>
				</div>
				<div class="modal-footer">
					<div class="row">
					<div class="col-xs-8">
						<button type="button" class="btn btn-default"
							ng-repeat="state in actSyllabus.availableOptions"
							ng-click="setState(state);">&rarr; {{state.name}}</button>
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" ng-click="updateSyllabus();" class="btn btn-primary">Save changes</button>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
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
				<th>Name</th>
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
				ng-class="{info: syllabus.ID === actSyllabus.ID}">
				<td><a class="btn" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a></td>
				<td>{{syllabus.ID}}</td>
				<td>{{syllabus.name}}</td>
				<td>{{syllabus.state}}</td>
				<td>{{syllabus.version}}</td>
				<td>{{syllabus.topic}}</td>
				<td>{{syllabus.owner}}</td>
				<td>{{syllabus.validity_period_from}} - {{syllabus.validity_period_to}}</td>
			</tr>
		</tbody>
	</table>
	<!-- Debugging -->
	<!-- <pre>{{actSyllabus}}</pre>-->
</div>
<!-- AngularJS -->
<script>
	'use strict';
	
	/* Controllers */
	var phonecatApp = angular.module('phonecatApp', [], function($compileProvider) {
		$compileProvider.directive('compile', function($compile) {
			return function(scope, element, attrs) {
				scope.$watch(
				  function(scope) {
					return scope.$eval(attrs.compile);
				  },
				  function(value) {
					element.html(value);
					$compile(element.contents())(scope);
				  }
				);
			};
		});
	});
	phonecatApp.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
			
		// read all syllabi
		$scope.getAllSyllabus = function () {
			$http.get('getjson.php?c=syllabus').success(function(data) {
				$scope.syllabi = data.syllabus;
			});
		}
		$scope.getSyllabusDetails = function () {
			$http({
				url: 'getjson.php?c=getsyllabusdetails',
				method: "POST",
				data: JSON.stringify($scope.actSyllabus) // params actSyllabus
			}).
			success(function(data){
				// next possible states
				$scope.actSyllabus.availableOptions = data.nextstates;
				$scope.formdata = data.formdata;
				$scope.actSyllabus.syllabelements = data.syllabuselements;
				//console.log($scope.actSyllabus.syllabelements);
				if ($scope.actSyllabus.availableOptions.length > 0)
					$scope.showNav = true;
			});
		}
		$scope.formdata = "";
		$scope.showNav = false;
		
		// Set State
		$scope.setState = function(newstate) {
			$scope.actSyllabus.selectedOption = newstate;
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
				$scope.getAllSyllabus(); // Refresh data
			}).
			error(function(error){
				$scope.status = "Error! " + error.message;
			});
		}
		$scope.updateSyllabus = function () { $scope.writeData('update_syllabus'); } // UPDATE		

		// initial selected data
		$scope.actSyllabus = {
			ID: 0,
			name: '',
			syllabelements: [],
			availableOptions: [],
			selectedOption: {sqms_state_id_TO: '1', name: 'unknown'}
		};
		$scope.setSelected = function (selElement) {
			$scope.actSyllabus = selElement;
			$scope.formdata = "<p>Loading...</p>";
			$scope.showNav = false;
			$scope.getSyllabusDetails();
		};
		
		// Initial functions
		$scope.getAllSyllabus();		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
