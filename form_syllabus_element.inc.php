<div class="panel panel-default" ng-repeat="phone in phones | filter:{'sqms_syllabus_element_id':'50'}:true">
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