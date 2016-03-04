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
	$scope.getAllQuestions = function () {$http.get('getjson.php?c=questions')
		.success(function(data) {
			$scope.questions = data.questionlist;
			// get answers for each question
			for (var i=0;i<$scope.questions.length;i++){
				$scope.questions[i].HasNoChilds = true; // default = no children
				$http({
					url: 'getjson.php?c=getanswers',
					method: "POST",
					data: JSON.stringify($scope.questions[i])
				})
				.success(function(a) {
					console.log(a);
					// if has children
					if (a.answers.length > 0) {
						// for all children
						for (var j=0;j<a.answers.length;j++) {
							// find parent
							for (var k=0;k<$scope.questions.length;k++) {
								// if parent ID = childrens parent ID
								if ($scope.questions[k].sqms_question_id == a.answers[j].sqms_question_id) {
									$scope.questions[k].answers = a.answers;
									$scope.questions[k].HasNoChilds = false; // has children
								}
							}
						}
					}
				})
			}
		}
	)};
	
	//------------------------------- Topic
	$http.get('getjson.php?c=topics').success(function(data) {
		$scope.topics = data.topiclist;
	});
	
	//------------------------------- Syllabus
	$scope.getAllSyllabus = function () {
		$http.get('getjson.php?c=syllabus')
		.success(function(data) {
			$scope.syllabi = data.syllabus;
			// get under-elements for each syllabus
			for (var i=0;i<$scope.syllabi.length;i++){
				$scope.syllabi[i].HasNoChilds = true; // default = no children
				$http({
					url: 'getjson.php?c=getsyllabusdetails',
					method: "POST",
					data: JSON.stringify($scope.syllabi[i])
				})
				.success(function(a){
					// if has children
					if (a.syllabuselements.length > 0) {
						// for all children
						for (var j=0;j<a.syllabuselements.length;j++){
							// find parent
							for (var k=0;k<$scope.syllabi.length;k++){
								// if parent ID = childrens parent ID
								if ($scope.syllabi[k].ID == a.syllabuselements[j].sqms_syllabus_id) {
									$scope.syllabi[k].syllabuselements = a.syllabuselements;
									$scope.syllabi[k].HasNoChilds = false; // has children
								}
							}
						}
					}
				})
			}
		});
	}
	
	// Toggle function
	$scope.displ = function(el){
		el.showKids = !el.showKids;
	}
	
	// TODO: obsolete
	/*
	$scope.getSyllabusDetails = function (syllab) {
		var  ergebnis = $http({
			url: 'getjson.php?c=getsyllabusdetails',
			method: "POST",
			data: JSON.stringify(syllab)
		}).
		success(function(data){
			// next possible states
			//$scope.actSyllabus.availableOptions = data.nextstates;
			//$scope.formdata = data.formdata;
			//$scope.actSyllabus.syllabelements = data.syllabuselements;
			//console.log($scope.actSyllabus.syllabelements);
			/*if ($scope.actSyllabus.availableOptions.length > 0)
				$scope.showNav = true;
			//console.log(data);
			//return data.syllabuselements;
		});
		return ergebnis
	}
	*/
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
	
	//---- Initial functions
	$scope.getAllSyllabus();
	$scope.getAllQuestions();
}]);