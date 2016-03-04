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
				<div compile="actSyllabus.formdata"></div>
			</div>
			<!-- Footer -->
			<div class="modal-footer">
				<div class="row">
					<div class="col-xs-8">
						<div class="pull-left">
							<span class="text-success">{{actSyllabus.state}}</span>
							<button type="button" class="btn btn-default"
								ng-repeat="state in actSyllabus.availableOptions"
								ng-click="setState(state);">{{state.name}}</button>
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


<div class="modal" id="copysyllab" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<!-- Header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Copy syllabus</h4>
			</div>
			<!-- Body -->
			<div class="modal-body">
				<div>Do you really want to copy this syllabus?</div>
				<h2>{{actSyllabus.name}}</h2>
			</div>
			<!-- Footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dont copy</button>
				<button type="button" ng-click="copySyllabus();" data-dismiss="modal" class="btn btn-success">Copy</button>
			</div>
		</div>
	</div>
</div>

<!-- Page -->
<div class="container">
	
	<div class="tab-content">
	
		<!-- Page: Dashboard -->
		<div id="pagedashboard" class="tab-pane active">
		
			<div class="row bg-primary">
				<div class="col-sm-12">
					<h2>Dashboard</h2>
				</div>
			</div>
			<br>
		
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
			
			<div class="row bg-primary">
				<div class="col-sm-4">
					<h2>Syllabus</h2>
				</div>
				<div class="col-sm-4">
					<span>
						<a href="#" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;Create new</a>
						<a data-target="#copysyllab" data-toggle="modal" ng-disabled="SelNavDisabled" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;Copy</a>
						<a href="#" title='switch help on/off' class="btn btn-default pull-right">
							<span class="fa-stack">
								<i class="fa fa-question fa-stack-1x"></i>
								<i class="fa fa-ban fa-stack-1x text-danger"></i>
							</span>Help</a>
					</span>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
			
			<table class="table" style="width: 100%;">
				<thead>
					<tr>
						<th style="min-width: 95px;">&nbsp;</th>
						<th class="text-muted"><small>ID</small></th>
						<th>Name</th>
						<th>State</th>
						<th>Version</th>
						<th>Topic</th>
						<th>Owner</th>
						<th>block</th>
					</tr>
				</thead>
				<tbody ng-repeat="s in syllabi">
					<tr ng-click="setSelectedSyllabus(s)" ng-class="{info: s.ID === actSyllabus.ID}">
						<td>
							<a class="btn pull-left" ng-hide="s.HasNoChilds" ng-click="displ(s)">
								<i class="fa fa-plus" ng-show="!s.showKids"></i>
								<i class="fa fa-minus" ng-hide="!s.showKids"></i>
							</a>
							<a class="btn pull-left" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a>
						</td>
						<td class="text-muted"><small>{{s.ID}}</small></td>
						<td>{{s.name}}</td>
						<td>{{s.state}}</td>
						<td>{{s.version}}</td>
						<td>{{s.topic}}</td>
						<td>{{s.owner}}</td>
						<td><small>{{s.validity_period_from}} - {{s.validity_period_to}}</small></td>
					</tr>
					<tr ng-hide="s.HasNoChilds || !s.showKids">
						<td colspan="8" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
							<table class="table table-striped table-condensed" style="margin:0;">
								<thead>
									<tr>
										<th>Order</th>
										<th>Name</th>
										<th>Description</th>
										<th>Severity</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="se in s.syllabuselements">
									<td>{{se.element_order}}</td>
									<td>{{se.name}}</td>
									<td>{{se.description}}</td>
									<td>{{se.severity}}%</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Page: Question -->
		<div id="pagequestion" class="tab-pane">
			<!-- Header -->
			<div class="row bg-primary">
				<div class="col-sm-8">
					<h2>Question</h2>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
			<!-- Content -->
			<table class="table">
				<thead>
					<tr>
						<th style="min-width: 95px;">&nbsp;</th>
						<th class="text-muted"><small>ID</small></th>
						<th>Question</th>
						<th>Author</th>
						<th>Vers.</th>
						<th>Ext. ID</th>
						<th>Topic</th>
						<th>?</th>
						<th>?</th>
						<th>Question Type</th>
					</tr>
				</thead>
				<tbody ng-repeat="q in questions | filter:filtertext"
					ng-click="setSelected(q)"
					ng-class="{success: q.sqms_question_id === actQuestion.sqms_question_id}">
					<tr>
						<td>
							<a class="btn pull-left" ng-hide="q.HasNoChilds" ng-click="displ(q)">
								<i class="fa fa-plus" ng-show="!q.showKids"></i>
								<i class="fa fa-minus" ng-hide="!q.showKids"></i>
							</a>
							<a class="btn pull-left" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a>
						</td>
						<td class="text-muted"><small>{{q.sqms_question_id}}</small></td>
						<td>{{q.question}}</td>
						<td>{{q.author}}</td>
						<td>{{q.version}}</td>
						<td>{{q.id_external}}</td>
						<td>{{q.name}}</td>
						<td>?</td>
						<td>?</td>
						<td>{{q.sqms_question_type_id}}</td>
					</tr>
					<tr ng-hide="q.HasNoChilds || !q.showKids">
						<td colspan="10" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
							<table class="table table-striped table-condensed" style="margin:0;">
								<thead>
									<tr>
										<th style="width:95px;">ID</th>
										<th style="width:75%;">Answer</th>
										<th>Correct</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="an in q.answers">
									<td>{{an.sqms_answer_id}}</td>
									<td>{{an.answer}}</td>
									<td>{{an.correct}}</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<!-- Page: Topic -->
		<div id="pagetopic" class="tab-pane">
			<!-- Header -->
			<div class="row bg-primary">
				<div class="col-sm-8">
					<h2>Topic</h2>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
			<!-- Content -->
			<table class="table">
				<thead>
					<tr>
						<th style="width:95px;">&nbsp;</th>
						<th class="text-muted"><small>ID</small></th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody ng-repeat="topic in topics | filter:filtertext"
					ng-click="setSelected(topic)"
					ng-class="{success: topic.sqms_topic_id === actTopic.sqms_topic_id}">
					<tr>
						<td>
							<a class="btn pull-left" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a>
						</td>
						<td class="text-muted"><small>{{topic.sqms_topic_id}}</small></td>
						<td>{{topic.name}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
</div>

<script src="custom/custom.js"></script>
<?php
	include_once '_footer.inc.php';
?>
