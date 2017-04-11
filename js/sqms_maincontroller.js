
/***********************************************************
 *                    Main Controller                      *
 ***********************************************************/
module.controller('SQMSController',
  ['$scope', '$http', '$sce', '$uibModal',
  function($scope, $http, $sce, $uibModal)
  {
    //--------------------------------------------------------- ModalWindow
    $scope.open = function (TemplateName, command, Element) {
      var modalInstance = $uibModal.open({
        animation: false,
        templateUrl: TemplateName,
        controller: 'ModalInstanceCtrl', // pass controller
        resolve: {
          cmd: function () {
            return command;
          },
          items: function () {
            return {
              topics: $scope.topics,
              users: $scope.users,
              languages: $scope.languages,
              synamelist: $scope.syllabi,
              questypes: $scope.questypes,
              syllabuselements: $scope.syllabuselements,
              questionlist: $scope.questions,
              states: $scope.states

            };
          },
          Elem: function () {
            return Element;
          }
        }
      });
      modalInstance.result.then(
      	function (result) {
        	$scope.writeData(result.command, result.data); // Send result to server
        }
        );
    };
    //--------------------------------------------------------- Select Element
    $scope.setSelection = function(el){ $scope.actSelection = el;};
	//--------------------------------------------------------- Get Selection
	$scope.sel = $scope.actSelection;
	$scope.getSelection = function(){ return $scope.actSelection; }
    //--------------------------------------------------------- Edit Element
    $scope.editEl = function(el) {
      // -- Syllabus
      if (el.ElementType == "S") {
        if (el.state.id == 1) // Only open modal in state "new"
          $scope.open('modalSyllabus.html', 'update_syllabus', el);
      }
      // -- Question
      else if (el.ElementType == "Q") {
        if (el.state.id == 1)
          $scope.open('modalNewQuestion.html', 'update_question', el);
      }
      // -- SyllabusElement
      else if (el.ElementType == "SE") {
        $scope.open('modalSyllabusElement.html', 'update_syllabuselement', el);
      }
      // -- Answer
      else if (el.ElementType == "A") {
        $scope.open('modalAnswer.html', 'update_answer', el);
      }
    }
    //--------------------------------------------------------- Create Successor
    $scope.createsuccessor = function(el) {
      // Element should not be in state NEW
      if (el.state != 'new') {
        // Syllabus
        var ElemName = "Syllabus";
        var Command = "create_successor_s";
        var Name = el.Name;
        // Question
        if (el.ElementType == "Q") {
          ElemName = "Question";
          Command = "create_successor_q";
          Name = el.Question;
        }
        // Ask for confirmation
        if (confirm("Are you sure that you want to create a successor of this "+ElemName+"\n\n'"+Name+"'?")){
          $scope.writeData(Command, el);
        }
      }
    }
    //--------------------------------------------------------- StateMachine
    $scope.setstate = function(newstate, Element) {
      var cmd = '';
      // Syllabus or Question ?
      if (Element.ElementType == 'S')
        cmd = 'update_syllabus_state';
      else if (Element.ElementType == 'Q')
        cmd = 'update_question_state';    
      // Write Command to Server
      $scope.writeData(
        cmd, {
          elementid: $scope.actSelection.ID,
          stateid: newstate
        }
        );
    }
    //--------------------------------------------------------- Sorting Tables
    // TODO: remove redundant code
    $scope.predicate_s = 'Topic';
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
    //--------------------------------------------------------- Dashboard
    $scope.getAllReports = function () {
      $http.get('getjson.php?c=getreports').success(function(data) {
        $scope.reports = data.reports;
      });
      return $scope.reports;
    }
    //--------------------------------------------------------- Get Data Functions

    $scope.getTopics = function() {
      $http.get('getjson.php?c=topics').success(function(data) {
        $scope.topics = data.topiclist; // store in scope
      });
      return $scope.topics;
    }
    $scope.getUsers = function() {
      $http.get('getjson.php?c=users').success(function(data) {
        $scope.users = data.userlist; // store in scope
      });
      return $scope.users;
    }
    $scope.getLanguages = function() {
      $http.get('getjson.php?c=languages').success(function(data) {
        $scope.languages = data.langlist; // store in scope
      });
      return $scope.languages;
    }
    $scope.getQTypes = function() {
      $http.get('getjson.php?c=questiontypes').success(function(data) {
        $scope.questypes = data.qtypelist; // store in scope
      });    
      return $scope.questypes;    
    }
    $scope.getStates = function(){
     $http.get('getjson.php?c=getStates').success(function(data) {
        $scope.states = data.statelist; // store in scope
      });
   }

   $scope.filterHTMLTags = function(html) {	
    var div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
  }    
    //--------------------------------------------------------- #Question  
    $scope.expandedQuestionIDs = [];
    //============================================ Load Q
    $scope.getAllQuestions = function () {
      // Save expanded Elements
      if ($scope.questions) {
        $scope.expandedQuestionIDs = $scope.questions.filter(x => x.showKids === true).map(x=> x.ID);
      }
      // Load data from server
      $http.get('getjson.php?c=questions')
      .success(function(data) {
        $scope.questions = data.questionlist;
        // When full data is loaded
        if ($scope.questions && $scope.answers)
          $scope.assignQ2A();
      });
    }

    //============================================ Load A
    $scope.getAllAnswers = function() {
      $http.get('getjson.php?c=answers').success(function(data) {
        $scope.answers = data.answers; // store in scope
        // When full data is loaded
        if ($scope.questions && $scope.answers)
          $scope.assignQ2A();
      });
    };
    //============================================ Assign Q -> A
    $scope.assignQ2A = function() {
      // Delete all assignments
      for (var i=0;i<$scope.questions.length;i++){
        $scope.questions[i].answers = undefined;
      }
      // Loop throug all A
      for (var i=0;i<$scope.answers.length;i++){
        // Format param (int -> bool)
        $scope.answers[i].correct = ($scope.answers[i].correct == 1);
        // 1. Find Q by ID
        var QID = $scope.answers[i].parentID;
        var Q = $scope.questions.find(x => x.ID === QID);
        // 2. Assign A to Q
        if (Q) {
          // if array is undef., create a new one, and/or add to back
          (Q.answers = Q.answers || []).push($scope.answers[i]);
          // if it was expanded before then expand again
          if ($scope.expandedQuestionIDs.indexOf(Q.ID) >= 0)
            Q.showKids = true;
        }
      }
    }
    //--------------------------------------------------------- #Syllabus
    $scope.expandedSyllabusIDs = [];
    //============================================ Load S
    $scope.getAllSyllabus = function () {
      // Save expanded Elements
      if ($scope.syllabi) {
        $scope.expandedSyllabusIDs = $scope.syllabi.filter(x => x.showKids === true).map(x=> x.ID);
      }
      // Load data from server
      $http.get('getjson.php?c=syllabus')
      .success(function(data) {
        $scope.syllabi = data.syllabus;
        // When full data is loaded
        if ($scope.syllabi && $scope.syllabuselements)
          $scope.assignS2SE();
      });
    }
    //============================================ Load SE
    $scope.getAllSyllabusElements = function() {
      $http.get('getjson.php?c=syllabuselements').success(function(data) {
        $scope.syllabuselements = data.syllabuselements; // store in scope
        // When full data is loaded
        if ($scope.syllabi && $scope.syllabuselements)
          $scope.assignS2SE();
      });
    }
    //============================================ Assign S -> SE
    $scope.assignS2SE = function() {
      // Delete all assignments
      for (var i=0;i<$scope.syllabi.length;i++){
        $scope.syllabi[i].syllabuselements = undefined;
      }
      // Loop throug all SE
      for (var i=0;i<$scope.syllabuselements.length;i++){
        // 1. Find S by ID
        var SID = $scope.syllabuselements[i].parentID;
        var S = $scope.syllabi.find(x => x.ID === SID);
        // 2. Assign SE to S
        if (S) {
          // if array is undef., create a new one, and/or add to back
          (S.syllabuselements = S.syllabuselements || []).push($scope.syllabuselements[i]);
          // if it was expanded before then expand again
          if ($scope.expandedSyllabusIDs.indexOf(S.ID) >= 0)
            S.showKids = true;
        }
      }
    }

  //--------------------------------------------------------- Inline editing
  // (can be implemented in writeData() maybe)
  $scope.saveEl = function(actEl, data, cmd) {
    var c; 
    switch (cmd) {
      case 'u_answer_c': c = 'update_answer'; actEl.correct = data; break;
      case 'u_syllabel_n': c = 'update_syllabuselement'; actEl.name = data; break;
      case 'u_syllabel_s': c = 'update_syllabuselement'; actEl.severity = data; break;
      case 'u_syllabel_ord': c = 'update_syllabuselement'; actEl.element_order = data; break;
      case 'u_topic_n': c = 'update_topic'; actEl.name = data; actEl.ID = actEl.id; break;
      case 'u_syllab_n': c = 'update_syllabus_name'; actEl.name = data; break;
      case 'u_syllab_tc': c = 'update_syllabus_topic'; actEl.TopicID = data; break;
      case 'u_question_tc': c = 'update_question_topic'; actEl.TopicID = data; break;
    }
    return $scope.writeData(c, actEl);
  }  
  //--------------------------------------------------------- WRITE data to server
  $scope.writeData = function (command, data) {
    // JSON --> String
    seen = [];
    json = JSON.stringify(data, function(key, val) {
      if (val != null && typeof val == "object") {
        if (seen.indexOf(val) >= 0) {
          return;
        }
        seen.push(val);
      }
      return val;
    });    
    
    // Send Data to Server
    $http({
      url: 'getjson.php?c=' + command,
      method: "POST",
      data: json
    }).
    success(function(response){
      //========================
      // STATE TRANSITION
      //========================
      if (command.indexOf("_state") >= 0) {
        // feedback to user
        if (response.show_message)
          alert(response.message);
        if (!response.allow_transition)
          return // do not refresh anything
      }
      //========================
      // Refresh data
      // TODO: Only refresh relevant things based on commands
      //       => but not everytime sure because of dependencies
      $scope.getAllSyllabus();
      $scope.getAllSyllabusElements();
      $scope.getAllQuestions();
      $scope.getAllAnswers();
    }).
    error(function(error) {
      console.log("Error! " + error);
    });
  }

  //--- Initial values
  $scope.actSelection = {};
  
  //---- Initial function calls
  // Load all needed data at page-start
  $scope.getTopics();
  $scope.getUsers();
  $scope.getLanguages();
  $scope.getQTypes();
  $scope.getStates();
  
  $scope.getAllSyllabus();
  $scope.getAllSyllabusElements();
  $scope.getAllQuestions();
  $scope.getAllAnswers();
  $scope.getAllReports(); // Dashboard
  
}]);

module.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value);
      });
    }
  };
});

module.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);

      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
  
});

/* Filter which filters array after models selected in the <select> above */
module.filter('statefilter', function(){
	return function(items, stateModel) {
		var filtered = [];

		if (!stateModel){
			// return filtered then nothing is shown until a state is chosen.
			return items;
		}
		for(var i = 0; i< items.length; i++){
			var item = items[i];
			if ((item.state.name) == (stateModel.name)){
				filtered.push(item);
			}
		}
		return filtered;
	};
});