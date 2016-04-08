'use strict';

var module = angular.module('phonecatApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce'])

// Needed for inline editing
module.run(function(editableOptions) {
	editableOptions.theme = 'bs3'; // needed for inline editing
});

// Controller of Modal Window
module.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, items, cmd) {

  // Initial settings  
  $scope.object = {
    command: cmd,
    data: {
      name: '',
      topic: items[0]
    }
  };
  $scope.items = items;

  $scope.ok = function () {    
    $uibModalInstance.close($scope.object); // Return result
  };
  
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});

// Main Controller
module.controller('PhoneListCtrl', ['$scope', '$http', '$sce', '$uibModal', function($scope, $http, $sce, $uibModal) {

  // TODO: Remove
  $scope.items = [{
    id: 1,
    name: 'aLabel'
  }, {
    id: 2,
    name: 'bLabel'
  }];

  $scope.open = function (TemplateName, command) {
    var modalInstance = $uibModal.open({
      animation: false,
      templateUrl: TemplateName,
      controller: 'ModalInstanceCtrl',
      resolve: {
        cmd: function () {
          return command;
        },
        items: function () {
          if (command == "create_syllabus" || command == "create_question") {
            $scope.getTopics(); // Refresh
            return $scope.topics;
          } else {
            return $scope.items;
          }
        }
      }
    });
    modalInstance.result.then(function (result) {
      console.log(result);
      // Send result to server
      $scope.writeData(result.command, result.data);
    }, function () {
      //$log.info('Modal dismissed at: ' + new Date());
    });
  };




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
				$scope.questions[i].state = $scope.questions[i].state.name; // TODO: only display, not replace
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
	
  $scope.getTopics = function() {
    return $scope.topics.length ? null : $http.get('getjson.php?c=topics').success(function(data) {
      $scope.topics = data;
    });
  };
  /* TODO
  $scope.$watch('user.group', function(newVal, oldVal) {
    if (newVal !== oldVal) {
      var selected = $filter('filter')($scope.groups, {id: $scope.user.group});
      $scope.user.groupName = selected.length ? selected[0].text : null;
    }
  });
  */
  
	//------------------------------- Syllabus
	$scope.getAllSyllabus = function () {
		$http.get('getjson.php?c=syllabus')
		.success(function(data) {
			
			$scope.syllabi = data.syllabus;
			$scope.syllabi_cols = Object.keys($scope.syllabi[0]); // get keys from first object
			
			//console.log($scope.syllabi_cols);
			
			// get under-elements for each syllabus
			for (var i=0;i<$scope.syllabi.length;i++){
				$scope.syllabi[i].HasNoChilds = true; // default = no children
				$scope.syllabi[i].state = $scope.syllabi[i].state.name; // TODO: only display, not replace
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
	$scope.updateSyllabus = function () { $scope.writeData('update_syllabus', $scope.actSyllabus); }
	
  //********************* Inline editing
	$scope.saveEl = function(actEl, data, cmd) {
		var c;
		//console.log(actEl);
		//console.log(data);
		switch (cmd) {
			case 'u_answer_t': c = 'update_answer'; actEl.answer = data; break;
			case 'u_answer_c': c = 'update_answer'; actEl.correct = data; break;
			case 'u_syllabel_n': c = 'update_syllabuselement'; actEl.name = data; actEl.ID = actEl.sqms_syllabus_element_id; break;
			case 'u_syllabel_s': c = 'update_syllabuselement'; actEl.severity = data; actEl.ID = actEl.sqms_syllabus_element_id; break;
			case 'u_topic_n': c = 'update_topic'; actEl.name = data; actEl.ID = actEl.id; break;
			case 'u_syllab_n': c = 'update_syllabus'; actEl.name = data; break;
			case 'u_syllab_tc': c = 'update_syllabus_topic'; actEl.TopicID = data; break;
			case 'u_question_q': c = 'update_question'; actEl.Question = data; break;
		}
		//console.log(actEl);
		return $http.post('getjson.php?c='+c, JSON.stringify(actEl)); // send new model
	}

	//********************* WRITE data to server
	$scope.writeData = function (command, data) {
		console.log("Sending command...");
		$http({
			url: 'getjson.php?c=' + command,
			method: "POST",
			data: JSON.stringify(data)
		}).
		success(function(data){
			console.log("Executed command successfully! Return: " + data);
			// TODO: ... Heavy data ... Make this callback later or at least faster
      // TODO: Only update at certain commands (create, update, ...)
			$scope.getAllSyllabus(); // Refresh data
			$scope.getAllQuestions(); // Refresh data
		}).
		error(function(error){
			console.log("Error! " + error.message);
		});
	}

	//--- Initial values
	$scope.actSyllabus = false;
	$scope.actQuestion = {};
	$scope.actTopic = {};

	//---- Initial functions
	$scope.getAllSyllabus();
	$scope.getAllQuestions();
}]);

// Directive
module.directive('statemachine', function ($compile) {
	return {
		template: '<div class="entry-photo"><h2>&nbsp;</h2><div class="entry-img"><span><a href="{{rootDirectory}}{{content.data}}"><img ng-src="{{rootDirectory}}{{content.data}}" alt="entry photo"></a></span></div><div class="entry-text"><div class="entry-title">{{content.title}}</div><div class="entry-copy">{{content.description}}</div></div></div>'
	};
});