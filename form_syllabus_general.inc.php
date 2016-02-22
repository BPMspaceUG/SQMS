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
						<table>
							<tr>
								<td>ID</td>
								<td>{{actSyllabus.ID}}</td>
								<td rowspan="2">
									<small>validity period</small><br/>
									<p><input type="text" style="width:100px;" class="form-control" data-ng-model="actSyllabus.validity_period_from"/> to <input type="text" style="width:100px;" class="form-control" data-ng-model="actSyllabus.validity_period_to"/></p>
								</td>
							</tr>
							<tr>
								<td>Version</td>
								<td>{{actSyllabus.version}}</td>
							</tr>
							<tr>
								<td>Name</td>
								<td colspan="2"><input type="text" class="form-control" data-ng-model="actSyllabus.name"/></td>
							</tr>
							<tr>
								<td>Topic</td>
								<td colspan="2">
									<select class="form-control">
										<option>1</option>
										<option>2</option>
										<option>3</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
					
					<div class="col-sm-6">
						<table>
							<tr>
								<td>Owner</td>
								<td>
									<select class="form-control">
										<option>1</option>
										<option>2</option>
										<option>3</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Group</td>
								<td>
									<select class="form-control">
										<option>1</option>
										<option>2</option>
										<option>3</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Predecessor</td>
								<td>{{actSyllabus.sqms_syllabus_id_predecessor}}</td>
							</tr>
							<tr>
								<td>Successor</td>
								<td>{{actSyllabus.sqms_syllabus_id_successor}}</td>
							</tr>
						</table>
					</div>
					
					<!--
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
								<p><input type="text" class="form-control" data-ng-model="actSyllabus.name"/></p>
								<p></p>
								<!--<p>-->
									<!-- TODO: default selection -->
									<!-- Help: https://docs.angularjs.org/api/ng/directive/ngOptions -->
								<!--	<select class="form-control" ng-options="topic as topic.name for topic in topics track by topic.sqms_topic_id" ng-model="selected_topic"></select>
								</p>-->
								<!--
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
						<div class="col-sm-4">State: {{status}}</div>
						<div class="col-sm-8">
							<form class="form-inline">
								<select name="mySelect" id="mySelect" class="form-control"
									ng-options="state.name as for state in actSyllabus.availableOptions track by state.sqms_state_id_TO"
									ng-model="actSyllabus.selectedOption">
								</select>
								<p>Selected state: {{actSyllabus.selectedOption}}</p>

								<input class="btn btn-default" type="submit" value="Save & unblock">
								<input class="btn btn-success" type="submit" ng-click="updateSyllabus()" value="Save">
								<input class="btn btn-default" type="submit" value="Unblock w/o save">
							</form>
						</ul>
						</div>
					</div>
					-->
					
					</div>
				</div>
			</div>
		</div>