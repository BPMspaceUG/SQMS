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

	<!-- Vorlage
	<div class="well">
		<p>Actual Element:</p>
		<form>
			<b>ID: </b><br/>
			<b>Name:</b>
			<input type="text" name="f_name" data-ng-model="actTopic.name" placeholder="Name" required />
			<br />
			<br />
			<input type="button" value="Create" data-ng-click="createTopic()" />
			<input type="button" value="Update" data-ng-click="updateTopic()" />
			<input type="button" value="Delete" data-ng-click="deleteTopic()" />
			<br />
			<p>Status: {{status}}</p>
		</form>
	</div>  -->

	<div class="well">
	
<div class="panel panel-default">
  <div class="panel-body">
	<!-- Navigation-Syllabus -->
	<ul class="nav nav-tabs">
	  <li role="presentation" class="active"><a href="#" id="tab_general">General</a></li>
	  <li role="presentation"><a href="#" id="tab_element">Element</a></li>
	  <li role="presentation"><a href="#" id="tab_history">History</a></li>
	</ul>
	<br/>
	<div id="tab_content">
		<div class="row">
			<div class="col-sm-6">
				<div class="col-sm-4">
					<p>ID</p>
					<p>Version</p>
					<p>Name</p>
					<p>Validity period</p>
					<p>Topic</p>
				</div>
				<div class="col-sm-8">
					<p>{{actSyllabus.ID}}</p>
					<p>{{actSyllabus.version}}</p>
					<p>{{actSyllabus.name}}</p>
					<p>{{actSyllabus.validity_period_from}} to {{actSyllabus.validity_period_to}}</p>
					<p>
						<!-- TODO: default selection -->
						<!-- Help: https://docs.angularjs.org/api/ng/directive/ngOptions -->
						<select class="form-control" ng-options="topic as topic.name for topic in topics track by topic.sqms_topic_id" ng-model="selected_topic"></select>
					</p>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="col-sm-4">
					<p>Owner</p>
					<p>Group</p>
					<p>Predecessor</p>
					<p>Successor</p>
				</div>
				<div class="col-sm-8">
					<p><select class="form-control">
					  <option>{{actSyllabus.owner}}</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select></p>
					<p><select class="form-control">
					  <option>1</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select></p>
					<p>{{actSyllabus.sqms_syllabus_id_predecessor}}</p>
					<p>{{actSyllabus.sqms_syllabus_id_successor}}</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h5>Description</h5>
				<textarea class="form-control" rows="3">{{actSyllabus.description}}</textarea>
			</div>
			<br/>
			<div class="col-sm-4">
				State:
			</div>
			<div class="col-sm-8">
				<form class="form-inline">
					<select class="form-control" ng-options="state as state.name for state in actSyllabus.NextStates" ng-model="actSyllabus.NextStates"></select>
					<input class="btn btn-default" type="submit" value="Save & unblock">
					<input class="btn btn-default" type="submit" value="Save">
					<input class="btn btn-default" type="submit" value="Unblock w/o save">
				</form>
			</ul>
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
				<td><checkbox>#</checkbox></td>
				<td>{{syllabus.ID}}</td>
				<td>{{syllabus.name}}</td>
				<td>{{syllabus.state}}</td>
				<td>{{syllabus.version}}</td>
				<td>{{syllabus.topic}}</td>
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
			ID: 0,
			name: ''
		};
		
		$scope.setSelected = function (selElement) {
		   $scope.actSyllabus = selElement;
		   
			$http.get('getjson.php?c=getnextstates').success(function(data) {
				$scope.actSyllabus.NextStates = data.nextstates;
			});
			
		};
		
	}]);
</script>
<?php
	include_once '_footer.inc.php';
?>
