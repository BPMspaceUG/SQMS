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
<div class="clearfix"></div>
<div class="container">
	<div class="panel panel-default" ng-repeat="phone in phones | filter:{'sqms_syllabus_element_id':  '50'}:true">
	  <div class="panel-body">
		<!-- Navigation-Syllabus -->
		<ul class="nav nav-tabs">
		  <li role="presentation" class="active"><a href="#">General</a></li>
		  <li role="presentation"><a href="#">Questions</a></li>
		  <li role="presentation"><a href="#">History</a></li>
		</ul>
		<br/>
		<div class="row">
			
			<div class="col-sm-12">
				<p>ID {{phone.sqms_syllabus_element_id}}&nbsp;&nbsp;&nbsp;&nbsp;Syllabus&nbsp;<select class="form-control" style="display:inline;width:200px;">
					  <option>{{phone.sqms_syllabus_id}}</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select>&nbsp;&nbsp;&nbsp;Order&nbsp;<select class="form-control" style="display:inline;width:100px;">
					  <option>{{phone.element_order}}</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select>&nbsp;&nbsp;&nbsp;Severity % &nbsp;<select class="form-control" style="display:inline;width:100px;">
					  <option>{{phone.severity}}</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select></p>
			</div>
			
			<div class="col-sm-12">
				<h5>Name</h5>
				<input type="text" class="form-control" value="{{phone.name}}"/>
			</div>
			
			<div class="col-sm-12">
				<h5>Description</h5>
				<textarea class="form-control" rows="3">{{phone.description}}</textarea>
			</div>
			
			<br/>

			<div class="col-sm-12">
				<form class="form-inline">
					<input class="btn btn-default" type="submit" value="Save & unblock">
					<input class="btn btn-default" type="submit" value="Save">
					<input class="btn btn-default" type="submit" value="Unblock w/o save">
				</form>
			</ul>
			</div>
		</div>
	  </div>
	</div>
	<!-- List of Syllabus Elements -->
	<div class="pull-right">
		<input type="text" ng-model="yourName" class="form-control" style="width:200px;" placeholder="filter">
	</div>
	<h2>Syllabus Elements</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>SyllabusEl_ID</th>
				<th>Order</th>
				<th>Name(english)</th>
				<th>Syllabus</th>
				<th>Severity</th>
				<th>block</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr class="success" ng-repeat="phone in phones | filter:yourName">
				<td><checkbox>#</checkbox></td>
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
</div>
</div>
<?php
	include_once '_footer.inc.php';
?>