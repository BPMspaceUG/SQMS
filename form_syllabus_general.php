<div class="panel panel-default">
	<div class="panel-body">
		
		<!-- Navigation-Syllabus -->
		<ul class="nav nav-tabs" role="tablist">
		  <li class="active"><a href="#general" data-toggle="tab">General</a></li>
		  <li><a href="#element" data-toggle="tab">Element</a></li>
		  <li><a href="#history" data-toggle="tab">History</a></li>
		</ul>
		<br/>
		
		<div class="tab-content">
			<div class="tab-pane active" id="general">			
				<div class="row">				
					<div class="col-sm-6">
						<table style="width:100%;">
							<tr>
								<td width="70px">ID</td>
								<td>{{actSyllabus.ID}}</td>
								<td rowspan="2" width="300px">
									<small>validity period</small><br/>
									<form class="form-inline">
									<p><input type="text" style="width:100px;" class="form-control" data-ng-model="actSyllabus.validity_period_from"/> to <input type="text" style="width:100px;" class="form-control" data-ng-model="actSyllabus.validity_period_to"/></p>
									</form>
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
						<table width="100%">
							<tr>
								<td width="100px">Owner</td>
								<td><input type="text" class="form-control" /></td>
							</tr>
							<tr>
								<td>Group</td>
								<td>No Group</td>
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
					<!-- Descr -->
					<div class="col-xs-12">
						<p>Description</p>
						<textarea class="form-control">{{actSyllabus.description}}</textarea>
					</div>
				</div>				
			</div>
			
			<div class="tab-pane" id="element">
				<table width="100%" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="50px">Order</th>
							<th>Name</th>
							<th width="50px">Severity</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="syE in actSyllabus.syllabelements">
							<td>{{syE.element_order}}</td>
							<td>{{syE.name}}</td>
							<td>{{syE.severity}}%</td>
							<td>{{syE.description}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="tab-pane" id="history">
				<p>Test historie</p>
			</div>			
		</div>		
	</div>
</div>