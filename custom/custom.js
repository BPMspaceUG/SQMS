'use strict';

/* Controllers */
angular.module('phonecatApp', ["xeditable"], function($compileProvider) {
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
.run(function(editableOptions) {
	editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
})
.controller('PhoneListCtrl', ['$scope', '$http', function($scope, $http) {
	
	/************************************** General **************************/
	
	// Sorting Tables, TODO: remove redundant code
	$scope.predicate_s = 'ID';
	$scope.predicate_q = 'ID';
	$scope.reverse_s = false;
	$scope.reverse_q = false;
	
	$scope.order_s = function(predicate) {
		$scope.reverse_s = ($scope.predicate_s === predicate) ? !$scope.reverse_s : false;
		$scope.predicate_s = predicate;
	};
	$scope.order_q = function(predicate) {
		$scope.reverse_q = ($scope.predicate_q === predicate) ? !$scope.reverse_q : false;
		$scope.predicate_q = predicate;
	};
	
	
	
	//------------------------------- Dashboard
	$http.get('getjson.php?c=getreports').success(function(data) {
		$scope.reports = data.reports;
	});
	
	//------------------------------- Question
	$scope.getAllQuestions = function () {$http.get('getjson.php?c=questions')
		.success(function(data) {
			$scope.questions = data.questionlist;
			$scope.question_cols = Object.keys($scope.questions[0]); // get keys from first object
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
						if ($scope.questions[k].ID == a.parentID) {
							// if has children
							if (a.answers.length > 0) {
								$scope.questions[k].HasNoChilds = false; // has now children
								// Special function --> convert TinyInt to JSBoolean
								for (var l=0;l<a.answers.length;l++) {
									a.answers[l].correct = (a.answers[l].correct != 0);
								}
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
			$scope.syllabi_cols = Object.keys($scope.syllabi[0]); // get keys from first object
			
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
	
	// Selection function
	$scope.displ = function(el){el.showKids = !el.showKids;}
	
	// TODO: only one function for this
	$scope.setSelectedSyllabus = function (el) {$scope.actSyllabus = el;};
	$scope.setSelectedQuestion = function (el) {$scope.actQuestion = el;};
	$scope.setSelectedTopic = function (el) {$scope.actTopic = el;};
	
	$scope.setState = function(newstate) {$scope.actSyllabus.selectedOption = newstate;}
	$scope.copySyllabus = function () { console.log("copying syllabus..."); $scope.writeData('copy_syllabus', $scope.actSyllabus);}
	$scope.updateSyllabus = function () { $scope.writeData('update_syllabus', $scope.actSyllabus); }
	
	$scope.saveEl = function(actEl, data, cmd) {
		console.log(actEl);
		console.log(data);
		console.log(cmd);
		//$scope.writeData('update_question', data);
	};
	
	// WRITE data to server
	$scope.writeData = function (command, data) {
		console.log("Sending command...");
		$http({
			url: 'getjson.php?c=' + command,
			method: "POST",
			data: JSON.stringify(data)
		}).
		success(function(data){
			console.log("Executed command successfully! Return: " + data);
			// TODO: Make this callback later
			$scope.getAllSyllabus(); // Refresh data
		}).
		error(function(error){
			console.log("Error! " + error.message);
		});
	}
	
	//--- Initial values
	$scope.actSyllabus = {};
	$scope.actQuestion = {};
	$scope.actTopic = {};

	//---- Initial functions
	$scope.getAllSyllabus();
	$scope.getAllQuestions();
}]);