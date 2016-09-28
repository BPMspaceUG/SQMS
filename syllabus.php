<?php
  // Includes
  include_once '_dbconfig.inc.php';
  include_once '_header.inc.php';

  /* presente $help_text when not empty */
  /*
  if ($help_text) {
    echo '<div class="container bg-info 90_percent" >' ;
    echo "<a data-toggle=\"collapse\" data-target=\"#collapse_help_event\" >PSEUDO CODE FOR EVENT_GRID PHP - Later here will be the helptext&nbsp;<i class=\"fa fa-chevron-down\"></i></a>";
    echo "<div id=\"collapse_help_event\" class=\"collapse\"> ";
    include_once 'syllabus_helptxt.inc.php';
    echo "</div>";
    echo "</div><p></p><p></p>";
  }
  */
?>
<!--------------- SUB MENU --------->
<div class="clearfix"></div>
<!--------------- END SUB MENU --------->

<!-- Page -->
<div class="container">
  <div class="tab-content">
  
    <!--
    ########################################## Page: Dashboard 
    -->
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
                  <div style="height: 50px;">{{report.attr}}</div>
                </div>
              </div>
            </div>
            <!-- TODO: Link to Elements -->
            <a href="#">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  
    <!--
    ########################################## Page: Syllabus 
    -->
    <div id="pagesyllabus" class="tab-pane">
      <div class="row bg-primary">
        <h2 class="col-sm-3">Syllabus</h2>
        <div class="col-sm-5">
          <!-- Menu Buttons -->
          <span>
            <button type="button" class="btn btn-success menuitem" ng-click="open('modalSyllabus.html', 'create_syllabus')">
              <i class="fa fa-plus"></i> New Syllabus
            </button>
            <button type="button" class="btn btn-success menuitem" ng-disabled="!actSyllabus || actSyllabus.state != 'new'" ng-click="open('modalSyllabusElement.html', 'create_syllabuselement')">
              <i class="fa fa-plus"></i> New SyllabusElement
            </button>
            <!-- TODO: Button to Export all Syllabus to mitsm Homepage -->
            <button type="button" class="btn btn-default menuitem" ng-click="open('modalExportSyllabus.html', 'export_syllabus')">
              <i class="fa fa-download"></i> Export
            </button>
          </span>
        </div>
        <div class="col-sm-4">
          <input type="text" ng-model="filtertext_sy" class="form-control pull-right menuitem" style="width:200px;" placeholder="filter">
        </div>
      </div>
      <br/>
      <!-- Table -->
      <table class="table datalist">
        <thead>
          <tr>
            <th style="min-width: 95px;">&nbsp;</th>
            <th style="min-width: 50px;" class="sortable" ng-click="order_s('ID')">ID<span class="sortorder" ng-show="predicate_s === 'ID'" ng-class="{reverse:reverse_s}"></span></th>
            <th style="min-width: 250px;" class="sortable" ng-click="order_s('Name')">Name<span class="sortorder" ng-show="predicate_s === 'Name'" ng-class="{reverse:reverse_s}"></span></th>
            <th style="width: 50px; text-align: center;" class="sortable visible-lg visible-md" ng-click="order_s('Version')">Version<span class="sortorder" ng-show="predicate_s === 'Version'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable" ng-click="order_s('Topic')">Topic<span class="sortorder" ng-show="predicate_s === 'Topic'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable visible-lg visible-md" ng-click="order_s('Owner')">Owner<span class="sortorder" ng-show="predicate_s === 'Owner'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable visible-lg" ng-click="order_s('From')">From<span class="sortorder" ng-show="predicate_s === 'From'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable visible-lg" ng-click="order_s('To')">To<span class="sortorder" ng-show="predicate_s === 'To'" ng-class="{reverse:reverse_s}"></span></th>
            <th class="sortable visible-lg visible-md" ng-click="order_s('Language')">Language<span class="sortorder" ng-show="predicate_s === 'Language'" ng-class="{reverse:reverse_s}"></span></th>
            <th style="width: 90px;" class="sortable" ng-click="order_s('state')">State<span class="sortorder" ng-show="predicate_s === 'state'" ng-class="{reverse:reverse_s}"></span></th>
          </tr>
        </thead>
        <tbody ng-repeat="s in syllabi | filter:filtertext_sy | orderBy:predicate_s:reverse_s">
          <tr ng-click="setSelectedSyllabus(s)"
            ng-class="{success: s.state == 'new', danger: s.state == 'deprecated', warning: s.state == 'ready', info: s.state == 'released'}">
            <td class="tablemenu">
              <!-- Tickmark -->
              <span title="Select Syllabus"><i ng-class="{'fa fa-fw fa-check-square-o': s.ID === actSyllabus.ID, 'fa fa-fw fa-square-o': s.ID != actSyllabus.ID}"></i></span>
              <!-- Expand or Collapse -->
              <span ng-hide="s.HasNoChilds" ng-click="displ(s)">
                <i class="fa fa-fw fa-plus-square" ng-show="!s.showKids" title="Expand"></i>
                <i class="fa fa-fw fa-minus-square" ng-hide="!s.showKids" title="Collapse"></i>
              </span>
              <!-- Dummy Icon for design -->
              <span ng-show="s.HasNoChilds"><i class="fa fa-fw fa-square icon-invisible"></i></span>
              <!-- Edit Icon -->
              <span ng-show="s.state == 'new'"><a ng-click="editsyllabus(s)" title="Edit Syllabus..."><i class="fa fa-fw fa-pencil"></i></a></span>
              <!-- Successor Icon -->
              <span ng-show="s.state != 'new' && s.SuccID == null"><a ng-click="successorsyllabus(s)" title="Create Successor..."><i class="fa fa-fw fa-share"></i></a></span>
            </td>
            <!-- ID -->
            <td><small><a ng-click="editsyllabus(s)">{{s['ID']}}</a></small></td>
            <!-- Name (inlineediting) -->
            <td>
              <div class="popover-wrapper">
                <a editable-text="s['Name']" onbeforesave="saveEl(s, $data, 'u_syllab_n')" edit-disabled="s.state != 'new'">{{s['Name'] || 'empty' }}</a>
              </div>
            </td>
            <!-- Version -->
            <td style="width: 50px; text-align: center;" class="visible-lg visible-md">{{s['Version']}}</td>
            <!-- Topic (inlineediting) -->
            <td style="width: 100px;">
              <div class="popover-wrapper">
                <a onbeforesave="saveEl(s, $data, 'u_syllab_tc')" onshow="getTopics()" edit-disabled="s.state != 'new'"
                e-ng-options="t.id as t.name for t in topics" editable-select="s['TopicID']">{{s['Topic'] || "empty"}}</a>
              </div>
            </td>
            <!-- Other Infos -->
            <td style="width: 100px;" class="visible-lg visible-md">{{s['Owner']}}</td>
            <td style="width: 70px;" class="visible-lg">{{s['From'] | date:'yyyy-MM-dd'}}</td>
            <td style="width: 70px;" class="visible-lg">{{s['To'] | date:'yyyy-MM-dd'}}</td>
            <td style="width: 100px;" class="visible-lg visible-md">{{s['Language']}}</td>
            <!-- Statemachine -->
            <td>
              <button uib-popover-template="'popoverStatemachineSyllabus.html'" popover-trigger="focus"
                ng-disabled="s['state'] == 'deprecated'" type="button" class="btn btn-default btn-sm">{{s['state']}}</button>                
            </td>
          </tr>
          <tr ng-hide="s.HasNoChilds || !s.showKids">
            <!-- Nested table -->
            <td colspan="10" style="padding:0; background-color: #ddd;">
              <table class="table table-condensed" style="font-size: .85em; margin:0;">
                <thead>
                  <tr>
                    <th style="width:50px;">&nbsp;</th>
                    <th style="width:50px;">ID</th>
                    <th style="width:50px;">Order</th>
                    <th style="width:250px">Name</th>
                    <th>Description</th>
                    <th style="width:100px;">Severity</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="se in s.syllabuselements">
                    <!-- Edit SyllabusElement -->
                    <td>
                      <!-- Edit Icon -->
                      <span ng-show="s.state == 'new'">
                        <a class="btn pull-left" ng-click="editsyllabuselement(se)" title="Edit SyllabusElement...">
                          <i class="fa fa-fw fa-pencil"></i>
                        </a>
                      </span>
                      <!-- Successor Icon -->
                      <span ng-show="s.state != 'new'">
                        <a class="btn pull-left">
                          <i class="fa fa-fw fa-square icon-invisible"></i>
                        </a>
                      </span>
                    </td>
                    <td>{{se.sqms_syllabus_element_id}}</td>
                    <!-- Order (inlineediting) -->
                    <td>
                      <div class="popover-wrapper">
                        <a editable-text="se.element_order" onbeforesave="saveEl(se, $data, 'u_syllabel_ord')" edit-disabled="s.state != 'new'">{{se.element_order || 'empty' }}</a>
                      </div>
                    </td>
                    <!-- Name (inlineediting) -->
                    <td>
                      <div class="popover-wrapper">
                        <a editable-text="se.name" onbeforesave="saveEl(se, $data, 'u_syllabel_n')" edit-disabled="s.state != 'new'">{{se.name || 'empty' }}</a>
                      </div>
                    </td>
                    <!-- Description -->
                    <td><div style="max-height: 60px; overflow: auto;">{{se.displDescr}}</div></td>
                    <!-- Severity -->
                    <td>
                      <div class="popover-wrapper">
                        <a editable-number="se.severity" onbeforesave="saveEl(se, $data, 'u_syllabel_s')"
                          e-min="1" e-max="100"  edit-disabled="s.state != 'new'">{{se.severity | number:0}}%</a>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--
    ########################################## Page: Question 
    -->
    <div id="pagequestion" class="tab-pane">
      <!-- Header -->
      <div class="row bg-primary">
        <h2 class="col-sm-3">Question</h2>
        <div class="col-sm-5">
          <!-- Menu Buttons -->
          <span>
            <button type="button" class="btn btn-success menuitem" ng-click="open('modalNewQuestion.html', 'create_question')">
              <i class="fa fa-plus"></i> New Question
            </button>
            <button type="button" class="btn btn-success menuitem" ng-disabled="!actQuestion || actQuestion.state != 'new'" ng-click="open('modalNewAnswer.html', 'create_answer')">
              <i class="fa fa-plus"></i> New Answer
            </button>
            <!-- TODO: Button to Export all Questions to mitsm Homepage -->
            <button type="button" class="btn btn-default menuitem" ng-click="open('modalExportQuestions.html', 'export_questions')">
              <i class="fa fa-download"></i> Export
            </button>
          </span>
        </div>
        <div class="col-sm-4">
          <input type="text" ng-model="filtertext_qu" class="form-control pull-right menuitem" style="width:200px;" placeholder="filter">
        </div>
      </div>
      <br/>
      <!-- Table -->
      <table class="table datalist">
        <thead>
          <tr>
            <th style="min-width: 95px;">&nbsp;</th>
            <th style="min-width: 50px;" class="sortable" ng-click="order_q('ID')">ID<span class="sortorder" ng-show="predicate_q === 'ID'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable" ng-click="order_q('Topic')">Topic<span class="sortorder" ng-show="predicate_q === 'Topic'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable" ng-click="order_q('Question')">Question<span class="sortorder" ng-show="predicate_q === 'Question'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable visible-lg" ng-click="order_q('owner')">Owner<span class="sortorder" ng-show="predicate_q === 'owner'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable visible-lg visible-md" ng-click="order_q('Language')">Language<span class="sortorder" ng-show="predicate_q === 'Language'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable visible-lg visible-md" ng-click="order_q('Version')"  style="width: 50px; text-align: center;" >Version<span class="sortorder" ng-show="predicate_q === 'Version'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable visible-lg" ng-click="order_q('ExtID')">Ext.ID<span class="sortorder" ng-show="predicate_q === 'ExtID'" ng-class="{reverse:reverse_q}"></span></th>
            <th class="sortable" ng-click="order_q('Type')">Type<span class="sortorder" ng-show="predicate_q === 'Type'" ng-class="{reverse:reverse_q}"></span></th>
            <th style="width: 90px;" class="sortable" ng-click="order_q('State')">State<span class="sortorder" ng-show="predicate_q === 'State'" ng-class="{reverse:reverse_q}"></span></th>
          </tr>
        </thead>
        <tbody ng-repeat="q in questions | filter:filtertext_qu | orderBy:predicate_q:reverse_q">
          <tr ng-click="setSelectedQuestion(q)"
            ng-class="{'seltbl': q.ID === actQuestion.ID, success: q.state == 'new',
              danger: q.state == 'deprecated', 'warning': q.state == 'ready', 'warning': q.state == 'released'}">
            <td class="tablemenu">
              <!-- Tickmark -->
              <span title="Select Question"><i ng-class="{'fa fa-fw fa-check-square-o': q.ID === actQuestion.ID, 'fa fa-fw fa-square-o': q.ID != actQuestion.ID}"></i></span>
              <!-- Children -->
              <span ng-hide="q.HasNoChilds" ng-click="displ(q)">
                <i class="fa fa-fw fa-plus-square" ng-show="!q.showKids"></i>
                <i class="fa fa-fw fa-minus-square" ng-hide="!q.showKids"></i>
              </span>
              <!-- Dummy Icon for design -->
              <span ng-show="q.HasNoChilds"><i class="fa fa-fw fa-square icon-invisible"></i></span>
              <!-- Edit Icon -->
              <span ng-show="q.state == 'new'"><a ng-click="editquestion(q)" title="Edit Question..."><i class="fa fa-fw fa-pencil"></i></a></span>
              <!-- Successor Icon -->
              <span ng-show="q.state != 'new' && q.SuccID == 0">
                <a ng-click="successorquestion(q)" title="Create Successor..."><i class="fa fa-fw fa-share"></i></a>
              </span>
            </td>
            <!-- ID -->
            <td><small>{{q['ID']}}</small></td>
            <!-- Topic -->
            <td style="width: 100px;">
              <div class="popover-wrapper">
                <a style="white-space: nowrap;" onbeforesave="saveEl(q, $data, 'u_question_tc')" onshow="getTopics()" edit-disabled="q.state != 'new'"
                e-ng-options="t.id as t.name for t in topics" editable-select="q['TopicID']">{{q['Topic'] || "empty"}}</a>
              </div>
            </td>
            <!-- Question (inlineediting) -->
            <td>
              <small>
              <div class="popover-wrapper">
                <a editable-text="q['Question']" onbeforesave="saveEl(q, $data, 'u_question_q')" edit-disabled="q.state != 'new'">{{q['Question'] || 'empty' }}</a>
              </div>
              </small>
            </td>
            <td class="visible-lg"><small>{{q['owner']}}</small></td>
            <td class="visible-lg visible-md"><small>{{q['Language']}}</small></td>
            <td style="width: 50px; text-align: center;">{{q['Version']}}</td>
            <td class="visible-lg">{{q['ExtID']}}</td>
            <td><small>{{q['Type']}}</small></td>
            <!-- StateMachine Popover -->
            <td>
              <button uib-popover-template="'popoverStatemachineQuestion.html'" popover-trigger="focus" ng-disabled="q['state'] == 'deprecated'"
                type="button" class="btn btn-default btn-sm">{{q['state']}}</button>
            </td>
          </tr>
          <tr ng-hide="q.HasNoChilds || !q.showKids">
            <td colspan="10" style="padding:0; background-color: #ddd; border: 1px solid #ccc;">
              <table class="table table-condensed" style="font-size: .85em; margin:0;">
                <thead>
                  <tr style="font-size: .9em;">
                    <!--<th style="width:95px;">&nbsp;</th>-->
                    <th style="width:95px;">ID</th>
                    <th style="width:75%;">Answer</th>
                    <th>Correct</th>
                  </tr>
                </thead>
                <tr ng-repeat="an in q.answers" ng-class="[{danger: !an.correct}, {success: an.correct}]">
                  <!--<td><a href="#" ng-click="deleteanswer(an)"><i class="fa fa-fw fa-trash-o"></i>Delete</a></td>-->
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
    <!--
    ########################################## Page: Topic
    -->
    <div id="pagetopic" class="tab-pane">
      <!-- Header -->
      <div class="row bg-primary">
        <div class="col-sm-4">
          <h2>Topic</h2>
        </div>
        <div class="col-sm-4">
          <button type="button" class="btn btn-default" ng-click="open('modalNewTopic.html', 'create_topic')">
            <i class="fa fa-plus"></i> New Topic
          </button>
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
  </div>
</div>
<!-- Footer -->
<?php
  include_once("templates.html"); // Include all HTML templates for AngularJS
  include_once '_footer.inc.php';
?>
