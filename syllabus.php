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

<!-- Modal window 
<div class="modal" id="test" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Edit syllabus</h4>
			</div>
			<div class="modal-body">
				<div compile="actSyllabus.formdata"></div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-xs-8">
						<div class="pull-left">
							<div statemachine></div>
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
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Copy syllabus</h4>
			</div>
			<div class="modal-body">
				<div>Do you really want to copy this syllabus?</div>
				<h2>{{actSyllabus.name}}</h2>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Dont copy</button>
				<button type="button" ng-click="copySyllabus();" data-dismiss="modal" class="btn btn-success">Copy</button>
			</div>
		</div>
	</div>
</div>
-->

<modal visible="showModal">
	<div ng-bind-html="modalcontent"></div>
</modal>

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
						<button class="btn btn-success" ng-click="m_createsyllabus()"><i class="fa fa-plus"></i>&nbsp;Create new</button>
						<button class="btn btn-success" ng-click="m_copysyllabus()"><i class="fa fa-plus"></i>&nbsp;Copy</button>
					</span>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext_sy" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>

			<table class="table">
				<thead>
					<tr>
						<th style="min-width: 95px;">&nbsp;</th>
						<th ng-repeat="sc in syllabi_cols" ng-click="order_s(sc)" class="sortable">{{sc}}<span class="sortorder" ng-show="predicate_s === sc" ng-class="{reverse:reverse_s}"></span></th>
					</tr>
				</thead>
				<tbody ng-repeat="s in syllabi | filter:filtertext_sy | orderBy:predicate_s:reverse_s">
					<tr ng-click="setSelectedSyllabus(s)" ng-class="{info: s.ID === actSyllabus.ID}">
						<td>
							<a class="btn pull-left" ng-hide="s.HasNoChilds" ng-click="displ(s)">
								<i class="fa fa-plus" ng-show="!s.showKids"></i>
								<i class="fa fa-minus" ng-hide="!s.showKids"></i>
							</a>
							<button class="btn pull-left" ng-click="m_editsyllabus(s)"><i class="fa fa-pencil"></i></button>
						</td>
						<td ng-repeat="sc in syllabi_cols">{{s[sc]}}</td>
					</tr>
					<tr ng-hide="s.HasNoChilds || !s.showKids">
						<td colspan="8" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
							<table class="table table-striped table-condensed" style="margin:0;">
								<thead>
									<tr>
										<th>Order</th>
										<th>Name</th>
										<!--<th>Description</th>-->
										<th>Severity</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="se in s.syllabuselements">
									<td>{{se.element_order}}</td>
									<td><a href="#" editable-text="se.name" onbeforesave="saveEl(se, $data, 'u_syllabel_n')">{{se.name || "empty"}}</a></td>
									<!--<td>{{se.description}}</td>-->
									<td><a href="#" editable-range="se.severity" onbeforesave="saveEl(se, $data, 'u_syllabel_s')" e-step="5">{{se.severity}}%</a></td>
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
				<div class="col-sm-4">
					<h2>Question</h2>
				</div>
				<div class="col-sm-4">
					<span>
						<button class="btn btn-success" ng-click="m_createquestion()"><i class="fa fa-plus"></i>&nbsp;Create new</button>
					</span>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext_qu" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
			<!-- Content -->
			<table class="table">
				<thead>
					<tr>
						<th style="min-width: 95px;">&nbsp;</th>
						<th ng-repeat="qu in question_cols"
							ng-click="order_q(qu)" class="sortable">{{qu}}<span class="sortorder"
							ng-show="predicate_q === qu" ng-class="{reverse:reverse_q}"></span></th>
					</tr>
				</thead>
				<tbody ng-repeat="q in questions | filter:filtertext_qu | orderBy:predicate_q:reverse_q">
					<tr ng-click="setSelectedQuestion(q)" ng-class="{info: q.ID === actQuestion.ID}">
						<td>
							<a class="btn pull-left" ng-hide="q.HasNoChilds" ng-click="displ(q)">
								<i class="fa fa-plus" ng-show="!q.showKids"></i>
								<i class="fa fa-minus" ng-hide="!q.showKids"></i>
							</a>
							<button ng-click="m_editquestion(q)" class="btn pull-left"><i class="fa fa-pencil"></i></button>
						</td>
						<td ng-repeat="qu in question_cols">{{q[qu]}}</td>
					</tr>
					<tr ng-hide="q.HasNoChilds || !q.showKids">
						<td colspan="10" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
							<table class="table table-striped table-condensed" style="margin:0;">
								<thead>
									<tr style="font-size: .9em;">
										<th style="width:95px;">ID</th>
										<th style="width:75%;">Answer</th>
										<th>Correct</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="an in q.answers" ng-class="[{danger: !an.correct}, {success: an.correct}]">
									<td>{{an.ID}}</td>
									<td><a href="#" editable-text="an.answer" onbeforesave="saveEl(an, $data, 'u_answer_t')">{{an.answer || "empty"}}</a></td>
									<td><a href="#" editable-checkbox="an.correct" e-title="Correct?"
										onbeforesave="saveEl(an, $data, 'u_answer_c')">{{an.correct && "☑ Correct" || "☐ Wrong" }}</a></td>
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
						<th>ID</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody ng-repeat="topic in topics | filter:filtertext"
					ng-click="setSelectedTopic(topic)"
					ng-class="{success: topic.sqms_topic_id === actTopic.sqms_topic_id}">
					<tr>
						<td>
							<a class="btn pull-left" data-toggle="modal" data-target="#test"><i class="fa fa-pencil"></i></a>
						</td>
						<td>{{topic.sqms_topic_id}}</td>
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
