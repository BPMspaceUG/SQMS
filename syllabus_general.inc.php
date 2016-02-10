<div class="panel panel-default" ng-repeat="phone in phones | filter:{'sqms_syllabus_id':'104'}:true">
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
					<p>{{phone.sqms_syllabus_id}}</p>
					<p>{{phone.version}}</p>
					<p>{{phone.name}}</p>
					<p>{{phone.validity_period_from}} to {{phone.validity_period_to}}</p>
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
					  <option>{{phone.owner}}</option>
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
					<p>{{phone.sqms_syllabus_id_predecessor}}</p>
					<p>{{phone.sqms_syllabus_id_successor}}</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h5>Description</h5>
				<textarea class="form-control" rows="3">{{phone.description}}</textarea>
			</div>
			<br/>
			<div class="col-sm-4">
				State:
			</div>
			<div class="col-sm-8">
				<form class="form-inline">					
					<select style="width: 200px;" class="form-control">
					  <option>valid & public</option>
					  <option>2</option>
					  <option>3</option>
					  <option>4</option>
					  <option>5</option>
					</select>
					<input class="btn btn-default" type="submit" value="Save & unblock">
					<input class="btn btn-default" type="submit" value="Save">
					<input class="btn btn-default" type="submit" value="Unblock w/o save">
				</form>
			</ul>
			</div>
		</div>
	</div>
</div>