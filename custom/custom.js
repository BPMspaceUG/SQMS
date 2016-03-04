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
					// find parent
					for (var k=0;k<$scope.questions.length;k++) {
						if ($scope.questions[k].sqms_question_id == a.parentID) {
							
							// save all data in the element
							//$scope.questions[k].availableOptions = a.nextstates; // next states
							//$scope.questions[k].formdata = a.formdata; // formular data

							// if has children
							if (a.answers.length > 0) {
								$scope.questions[k].HasNoChilds = false; // has now children
								$scope.questions[k].answers = a.answers;
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
				
				// request details for each syllabus
				$http({
					url: 'getjson.php?c=getsyllabusdetails',
					method: "POST",
					data: JSON.stringify($scope.syllabi[i])
				})
				.success(function(a){
					
					// find parent
					for (var k=0;k<$scope.syllabi.length;k++) {
						if ($scope.syllabi[k].ID == a.parentID) {
							
							// save all data in the element
							$scope.syllabi[k].availableOptions = a.nextstates; // next states
							$scope.syllabi[k].formdata = a.formdata; // formular data

							// if has children
							if (a.syllabuselements.length > 0) {
								$scope.syllabi[k].HasNoChilds = false; // has now children
								$scope.syllabi[k].syllabuselements = a.syllabuselements;
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
	$scope.setSelectedSyllabus = function (el) {
		$scope.actSyllabus = el;
	};
	
	// Set State
	$scope.setState = function(newstate) {
		$scope.actSyllabus.selectedOption = newstate;
	}
	$scope.copySyllabus = function () { console.log("copying syllabus..."); $scope.writeData('copy_syllabus'); }
	$scope.updateSyllabus = function () { $scope.writeData('update_syllabus'); }
	
	$scope.actSyllabus = {};
	
	// WRITE
	$scope.writeData = function (command) {
		$scope.status = "Sending command...";
		console.log("Sending command...");
		$http({
			url: 'getjson.php?c=' + command,
			method: "POST",
			data: JSON.stringify($scope.actSyllabus)
		}).
		success(function(data){
			$scope.status = "Executed command successfully! Return: " + data;
			console.log("Executed command successfully! Return: " + data);
			$scope.getAllSyllabus(); // Refresh data
		}).
		error(function(error){
			$scope.status = "Error! " + error.message;
			console.log("Error! " + error.message);
		});
	}

	//---- Initial functions
	$scope.getAllSyllabus();
	$scope.getAllQuestions();
}]);