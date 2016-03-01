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
<div class="clearfix"></br></div>
<!--------------- END SUB MENU --------->

<!-- Modal window -->
<div class="modal" id="test" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<!-- Header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Edit syllabus</h4>
			</div>
			<!-- Body -->
			<div class="modal-body">
				<!-- <pre>{{actSyllabus}}</pre> -->
				<div compile="formdata"></div>
			</div>
			<!-- Footer -->
			<div class="modal-footer">
				<div class="row">
					<div class="col-xs-8">
						<div class="pull-left">
							<span class="text-success">{{actSyllabus.state}}</span>
							&rarr;
							<button type="button" class="btn btn-default"
								ng-repeat="state in actSyllabus.availableOptions"
								ng-click="setState(state);"
								ng-class="{active actSyllabus.selectedOption.sqms_state_id_TO === state.sqms_state_id_TO}">{{state.name}}</button>
						</div>
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" ng-click="updateSyllabus();" data-dismiss="modal" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Page -->
<div class="container">
	
	<div class="tab-content">
	
		<!-- Page: Dashboard -->
		<div id="pagedashboard" class="tab-pane active">
			<!-- <p>example <a href="http://bootstrapmaster.com/live/perfectum/">Dashboard Example</a></p>-->
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
	
		<!-- Page: Syllabus -->
		<div id="pagesyllabus" class="tab-pane">
		
			<!-- Sub menu -->
			<div>
				<a href="#" class="btn btn-large btn-success"><i class="fa fa-plus"></i>&nbsp;Create new</a>
				<a href="#" ng-disabled="SelNavDisabled" class="btn btn-large btn-success"><i class="fa fa-plus"></i>&nbsp;Copy</a>
				<a href="#" title='switch help on/off' class="btn btn-large btn-default pull-right">
					<span class="fa-stack">
						<i class="fa fa-question fa-stack-1x"></i>
						<i class="fa fa-ban fa-stack-1x text-danger"></i>
					</span>Help</a>
			</div>

			<div class="row">
				<div class="col-sm-8">
					<h2 style="margin:0;">Syllabi</h2>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<table class="table table-hover">
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
					<div >
					<tr ng-repeat="syllabus in syllabi | filter:filtertext"
						ng-click="setSelected(syllabus)"
						ng-class="{info: syllabus.ID === actSyllabus.ID}">
						<td>
							<a class="btn"><i class="fa fa-plus"></i></a>
							<a class="btn" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a>
						</td>
						<td>{{syllabus.ID}}</td>
						<td>{{syllabus.name}}</td>
						<td>{{syllabus.state}}</td>
						<td>{{syllabus.version}}</td>
						<td>{{syllabus.topic}}</td>
						<td>{{syllabus.owner}}</td>
						<td>{{syllabus.validity_period_from}} - {{syllabus.validity_period_to}}</td>
					</tr>
					<tr ng-repeat="syllabus in syllabi | filter:filtertext">
						<td colspan="8" ></td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Page: Question -->
		<div id="pagequestion" class="tab-pane">
			<div class="row">
				<div class="col-sm-8">
					<h2 style="margin:0;">Questions</h2>
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
		
		
		<!-- Page: Topic -->
		<div id="pagetopic" class="tab-pane">
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
	
	</div>

</div>


<!-- AngularJS -->
<script>
	'use strict';
	
	/* Controllers */
	angular.module('phonecatApp', [], function($compileProvider) {
		
		// for loading HTML from Database
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
		
	})
	.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
		
		//------------------------------- Dashboard
		$http.get('getjson.php?c=getreports').success(function(data) {
			$scope.reports = data.reports;
		});
		
		//------------------------------- Question
		$http.get('getjson.php?c=questions').success(function(data) {
			$scope.questions = data.questionlist;
		});		
		
		//------------------------------- Topic
		$http.get('getjson.php?c=topics').success(function(data) {
			$scope.topics = data.topiclist;
		});
		
		//------------------------------- Syllabus
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
			selectedOption: null
		};
		$scope.SelNavDisabled = true;
		$scope.setSelected = function (selElement) {
			$scope.actSyllabus = selElement;
			$scope.actSyllabus.selectedOption = null;
			$scope.formdata = "<p>Loading...</p>";
			$scope.showNav = false;
			$scope.getSyllabusDetails();
			$scope.SelNavDisabled = false;
		};		
		// Initial functions
		$scope.getAllSyllabus();
		
	}]) // http://www.angularjshub.com/examples/customdirectives/template/
	.directive("nghTemplateDir", function () {
		return {
			template: 'This is <strong>nghTemplateDir</strong> directive printing <em>{{myScopeVar}}</em>'
		};
	});
</script>
<?php
	include_once '_footer.inc.php';
?>
