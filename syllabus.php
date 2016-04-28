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
            <button type="button" class="btn btn-default" ng-click="open('modalNewSyllabus.html', 'create_syllabus')">
              <i class="fa fa-plus"></i> Syllabus</button>
            <button type="button" class="btn btn-default" ng-disabled="!actSyllabus" ng-click="open('modalNewSyllabusElement.html', 'create_syllabuselement')">
              <i class="fa fa-plus"></i> Syllabus-Element ({{actSyllabus.ID}})</button>
					</span>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext_sy" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
      
      <!-- Debugging -->
      <pre ng-show="debugMode">{{actSyllabus}}</pre>
			
      <table class="table">
				<thead>
					<tr>
						<th>&nbsp;</th>
            <th class="sortable" ng-click="order_s('ID')">ID<span class="sortorder" ng-show="predicate_s === 'ID'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Name')">Name<span class="sortorder" ng-show="predicate_s === 'Name'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Version')">Version<span class="sortorder" ng-show="predicate_s === 'Version'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Topic')">Topic<span class="sortorder" ng-show="predicate_s === 'Topic'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Owner')">Owner<span class="sortorder" ng-show="predicate_s === 'Owner'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Language')">Language<span class="sortorder" ng-show="predicate_s === 'Language'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('state')">State<span class="sortorder" ng-show="predicate_s === 'state'" ng-class="{reverse:reverse_s}"></span></th>
					</tr>
				</thead>
				<tbody ng-repeat="s in syllabi | filter:filtertext_sy | orderBy:predicate_s:reverse_s">
					<tr ng-click="setSelectedSyllabus(s)"
            ng-class="{'seltbl': s.ID === actSyllabus.ID, success: s.state == 'new',
              danger: s.state == 'deprecated', 'warning': s.state == 'ready', 'warning': s.state == 'released'}">
						<td style="width: 150px;">
              <a class="btn pull-left"><i ng-class="{'fa fa-fw fa-check-square-o': s.ID === actSyllabus.ID, 'fa fa-fw fa-square-o': s.ID != actSyllabus.ID}"></i></a>
							<a class="btn pull-left" ng-hide="s.HasNoChilds" ng-click="displ(s)">
								<i class="fa fa-fw fa-plus-square" ng-show="!s.showKids"></i>
								<i class="fa fa-fw fa-minus-square" ng-hide="!s.showKids"></i>
							</a>
              <a class="btn pull-left" ng-show="s.HasNoChilds"><i class="fa fa-fw fa-square icon-invisible"></i></a>
							<a class="btn pull-left" ng-click="editsyllabus(s)"><i ng-class="{'fa fa-fw fa-pencil': s.state == 'new', 'fa fa-share': s.state != 'new'}"></i></a>
						</td>
						<td style="width: 50px;">{{s['ID']}}</td>
						<td style="width: 250px;"><a href="#" onbeforesave="saveEl(s, $data, 'u_syllab_n')" editable-text="s['Name']">{{s['Name'] || "empty"}}</a></td>
						<td style="width: 50px; text-align: center;">{{s['Version']}}</td>
						<td style="width: 100px;"><a href="#" onbeforesave="saveEl(s, $data, 'u_syllab_tc')" onshow="getTopics()" e-ng-options="t.id as t.name for t in topics" editable-select="s['Topic']">{{s['Topic' || "empty"]}}</a></td>
						<td>{{s['Owner']}}</td>
						<td>{{s['Language']}}</td>
						<td style="width: 90px;">
              <!-- StateMachine Popover -->
              <button uib-popover-template="'popoverStatemachineSyllabus.html'" popover-trigger="focus" ng-disabled="s['state'] == 'deprecated'"
                type="button" class="btn btn-default btn-sm">{{s['state']}}</button>                
            </td>
					</tr>
					<tr ng-hide="s.HasNoChilds || !s.showKids">
						<td colspan="8" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
							<table class="table table-striped table-condensed" style="margin:0;">
								<thead>
									<tr>
										<th>ID</th>
										<th>Order</th>
										<th>Name</th>
										<th>Description</th>
										<th>Severity</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="se in s.syllabuselements">
									<td>{{se.sqms_syllabus_element_id}}</td>
									<td>{{se.element_order}}</td>
									<td><a href="#" editable-text="se.name" onbeforesave="saveEl(se, $data, 'u_syllabel_n')">{{se.name || "empty"}}</a></td>
									<td>{{se.description}}</td>
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
            <button type="button" class="btn btn-default" ng-click="open('modalNewQuestion.html', 'create_question')">
              <i class="fa fa-plus"></i> Question</button>
            <button type="button" class="btn btn-default" ng-disabled="!actQuestion" 
            ng-click="open('modalNewAnswer.html', 'create_answer')">
              <i class="fa fa-plus"></i> Answer ({{actQuestion.ID}})</button>
					</span>
				</div>
				<div class="col-sm-4">
					<input type="text" ng-model="filtertext_qu" class="form-control pull-right" style="width:200px;" placeholder="filter">
				</div>
			</div>
			<br/>
      
			<!-- Content -->
      <pre ng-show="debugMode">{{actQuestion}}</pre>
      
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
					<tr ng-click="setSelectedQuestion(q)" ng-class="{info: q.ID === actQuestion.ID, success: q.state == 'new', danger: q.state == 'deprecated'}">
						<td>
							<a class="btn pull-left" ng-hide="q.HasNoChilds" ng-click="displ(q)">
								<i class="fa fa-plus" ng-show="!q.showKids"></i>
								<i class="fa fa-minus" ng-hide="!q.showKids"></i>
							</a>
							<button ng-click="m_editquestion(q)" class="btn pull-left"><i class="fa fa-pencil"></i></button>
						</td>
						<td>{{q['ID']}}</td>
						<td>{{q['Topic']}}</td>
						<td><a href="#" onbeforesave="saveEl(q, $data, 'u_question_q')" editable-text="q['Question']">{{q['Question'] || "empty"}}</a></td>
						<td>{{q['Author']}}</td>
						<td>{{q['Language']}}</td>
						<td>{{q['Vers']}}</td>
						<td>{{q['ExtID']}}</td>
						<td>{{q['Type']}}</td>
						<td>
							<!-- StateMachine Popover -->
              <button uib-popover-template="'popoverStatemachineQuestion.html'" popover-trigger="focus" ng-disabled="q['state'] == 'deprecated'"
                type="button" class="btn btn-default btn-sm">{{q['state']}}</button>
            </td>
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
				<div class="col-sm-4">
					<h2>Topic</h2>
				</div>
				<div class="col-sm-4">
          <button type="button" class="btn btn-default" ng-click="open('modalNewTopic.html', 'create_topic')">
            <i class="fa fa-plus"></i> Topic</button>
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
						<th style="width:100px;">ID</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody ng-repeat="topic in topics | filter:filtertext"
					ng-click="setSelectedTopic(topic)"
					ng-class="{success: topic.id === actTopic.id}">
					<tr>
						<td>{{topic.id}}</td>
						<td><a href="#" editable-text="topic.name" onbeforesave="saveEl(topic, $data, 'u_topic_n')">{{topic.name || "empty"}}</a></td>
					</tr>
				</tbody>
			</table>
		</div>

    
    
    <!-- Template Modal "Create Topic" -->
    <script type="text/ng-template" id="modalNewTopic.html">
        <div class="modal-header">
            <h3 class="modal-title">Create new topic</h3>
        </div>
        <div class="modal-body">
            <form class="form-horizontal">
            <fieldset>
              <legend>Create syllabus</legend>
              <label>Topic name</label>
              <input type="text" class="form-control" placeholder="Topicname" ng-model="object.data.name"/>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Create</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <!-- Template Modal "Create Syllabus" -->
    <script type="text/ng-template" id="modalNewSyllabus.html">
        <div class="modal-header">
            <h3 class="modal-title">Create new syllabus</h3>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
          <fieldset>
          <legend>Create syllabus</legend>
          <label class="control-label">Syllabus name</label>
          <input ng-model="object.data.name" placeholder="Syllabusname" class="form-control" type="text" />
          <label class="control-label">Topic</label>
          <select class="form-control" ng-options="item as item.name for item in items track by item.id" ng-model="object.data.topic"></select>
          <label class="control-label">Owner</label>
          <input ng-model="object.data.owner" placeholder="Max Mustermann" class="form-control" type="text" />
          <label class="control-label">Description</label>
          <textarea data-ui-tinymce ng-model="object.data.description"></textarea>
          <label class="control-label">Version</label>
          <input placeholder="1" class="form-control" type="text" disabled/>
          </fieldset>
          </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Create</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>
    
    <!-- Template Modal "Edit Syllabus" -->
    <script type="text/ng-template" id="modalEditSyllabus.html">
        <div class="modal-header">
            <h3 class="modal-title">Edit syllabus</h3>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
          <fieldset>
          <legend>Edit syllabus</legend>
          <label class="control-label">Syllabus name</label>
          <input ng-model="object.data.name" placeholder="Syllabusname" class="form-control" type="text" />
          <label class="control-label">Topic</label>
          <select class="form-control" ng-options="item as item.name for item in items track by item.id" ng-model="object.data.topic"></select>
          <label class="control-label">Owner</label>
          <input ng-model="object.data.owner" placeholder="Max Mustermann" class="form-control" type="text" />
          <label class="control-label">Description</label>
          <textarea data-ui-tinymce ng-model="object.data.description"></textarea>
          <label class="control-label">Version</label>
          <input placeholder="1" class="form-control" type="text" disabled/>
          </fieldset>
          </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Save</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <!-- Template Modal "Create Question" -->
    <script type="text/ng-template" id="modalNewQuestion.html">
        <div class="modal-header">
            <h3 class="modal-title">Create new Question</h3>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
          <fieldset>
          <legend>Create question</legend>
          <label class="control-label">Question text</label>
          <input ng-model="object.data.question" placeholder="What is the answer to the universe and everything?" class="form-control" type="text" />
          <label class="control-label">Topic</label>
          <select class="form-control" ng-options="item as item.name for item in items track by item.id" ng-model="object.data.topic"></select>
          <label class="control-label">Author</label>
          <input ng-model="object.data.author" placeholder="Max Mustermann" class="form-control" type="text" />
          <label class="control-label">Version</label>
          <input placeholder="1" class="form-control" type="text" disabled/>
          </fieldset>
          </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Create</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <!-- Template Modal "Create Answer" -->
    <script type="text/ng-template" id="modalNewAnswer.html">
        <div class="modal-header">
            <h3 class="modal-title">Create new Answer</h3>
        </div>
        <div class="modal-body">
            <form class="form-horizontal">
            <fieldset>
              <legend>Create answer</legend>
              <label>Answertext</label>
              <input type="text" class="form-control" placeholder="Answer is 42" ng-model="object.data.answer"/>
              <label>Correct?</label>
              <input type="text" class="form-control" placeholder="Answer is 42" ng-model="object.data.correct"/>
            </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Create</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <!-- Template Modal "Create Syllabus Element" -->
    <script type="text/ng-template" id="modalNewSyllabusElement.html">
        <div class="modal-header">
            <h3 class="modal-title">Create new syllabuselement</h3>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
          <fieldset>
          <legend>Create syllabuselement</legend>
          <label class="control-label">Parent Syllabus</label>
          <input class="form-control" ng-model="object.data.parentID" placeholder="105" type="text" />
          <label class="control-label">Order</label>
          <input class="form-control" ng-model="object.data.element_order" placeholder="1" type="text" />
          <label class="control-label">Severity</label>
          <input class="form-control" ng-model="object.data.severity" placeholder="105" type="text" />
          <label class="control-label">Syllabus-Element name</label>
          <input ng-model="object.data.name" placeholder="Syllabuselementname" class="form-control" type="text" />
          <label class="control-label">Description</label>
          <textarea data-ui-tinymce ng-model="object.data.description"></textarea>
          </fieldset>
          </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Create</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <!-- Template: StateMachine Syllabus -->
    <script type="text/ng-template" id="popoverStatemachineSyllabus.html">
      <div><b>goto:</b>&nbsp;<span ng-repeat="state in actSyllabus.availableOptions">
          <button type="button" ng-click="setstate('update_syllabus_state', state.id)" class="btn btn-default btn-sm">{{state.name}}</button>
        </span>
      </div>
    </script>
    
    <!-- Template: StateMachine Question -->
    <script type="text/ng-template" id="popoverStatemachineQuestion.html">
      <div><b>goto:</b>&nbsp;<span ng-repeat="state in actQuestion.availableOptions">
          <button type="button" ng-click="setstate('update_question_state', state.id)" class="btn btn-default btn-sm">{{state.name}}</button>
        </span>
      </div>
    </script>
    
	</div>
</div>

<script src="custom/custom.js"></script>
<?php
	include_once '_footer.inc.php';
?>
