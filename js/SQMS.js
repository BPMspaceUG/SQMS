var module = angular.module('SQMSApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce', 'ui.select'])

// Needed for inline editing
module.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // needed for inline editing
});

/***********************************************************
 *                 Modal Window Controller                 *
 ***********************************************************/
module.controller('ModalInstanceCtrl', function ($scope, $http, $uibModalInstance, items, cmd, Elem) {
  
  // bugFix because of Angular UI Bug
  // More info: https://github.com/angular-ui/ui-select/issues/852
  $scope.bugFix = {};
  $scope.bugFix.SEQuestions = [];
  $scope.bugFix.QSyllabusElements = [];
  
  $scope.SE_Q = []; // get Questions by SE-ID
  $scope.Q_SE = []; // get SyllabusElements by Q-ID
  
  $scope.format = 'yyyy-MM-dd';
  $scope.p1 = { opened: false };
  $scope.p2 = { opened: false };

  // Date format when creating
  var ds = new Date();  
  var ds2 = new Date();
  ds2.setYear(ds2.getFullYear() + 1);
  
  $scope.getSE_Q = function() {
    $http.get('getjson.php?c=syllabuselementsquestions').success(function(data) {
      var allElms = data.se_q_list;      
      // loop through elements
      for (var i=0;i<allElms.length;i++) {
        var idx1 = allElms[i].SEID;
        var idx2 = allElms[i].QID;
        //--- Array 1
        if (!$scope.SE_Q[idx1])
          $scope.SE_Q[idx1] = [allElms[i].QID]; // a new array is born
        else
          $scope.SE_Q[idx1].push(allElms[i].QID);
        //--- Array 2
        if (!$scope.Q_SE[idx2])
          $scope.Q_SE[idx2] = [allElms[i].SEID]; // a new array is born
        else
          $scope.Q_SE[idx2].push(allElms[i].SEID);
      }
      console.log("---------------------- SE <-> Q");
      console.log($scope.SE_Q);
      console.log($scope.Q_SE);
      
      /******** only select relevant Questions for this Element ********/    
      
      $scope.thisSE_Q = $scope.SE_Q[$scope.Element.ID];
      if (!$scope.thisSE_Q) $scope.thisSE_Q = []; // empty list
      
      $scope.thisQ_SE = $scope.Q_SE[$scope.Element.ID];
      if (!$scope.thisQ_SE) $scope.thisQ_SE = []; // empty list
      
      /******  get Questions for SyllabusElement into bugfix Object for Plugin *******/
      
      for (var i=0;i<$scope.thisSE_Q.length;i++){
        var QID = $scope.thisSE_Q[i];
        $scope.bugFix.SEQuestions.push($scope.allQuestions[QID]);
      }
      
      for (var i=0;i<$scope.thisQ_SE.length;i++){
        var tmpID = $scope.thisQ_SE[i];
        $scope.bugFix.QSyllabusElements.push($scope.allSyllabusElements[tmpID]);
      }
      
    });
  }
  
  // Initial settings
  $scope.object = {
    command: cmd,
    data: {
      name: '',
      parentID: -1,
      element_order: 1,
      severity: 25,
      answer: '',
      Owner: '',
      Version: 1,
      From: ds,
      To: ds2,
      description: '<p>Enter a description...</p>',
      correct: false,
      ngTopic: {},
      ngOwner: {},
      ngLang: {},
      ngQuesType: {},
      ngParent: {}
    }
  };
  
  $scope.tinymceOptions = {
    inline: false,
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table contextmenu directionality',
      'emoticons template paste textcolor colorpicker textpattern imagetools'
    ],
    skin: 'lightgray',
    theme : 'modern'
  };

  // Important
  $scope.Element = Elem;  
  $scope.topics = items.topics;
  $scope.users = items.users;
  $scope.languages = items.languages;
  $scope.synamelist = items.synamelist;
  $scope.syllabuselements = items.syllabuselements;
  $scope.questypes = items.questypes;
  $scope.questions = items.questionlist;
  
  // Order Questions in a new array with IDs as Indices
  $scope.allQuestions = [];
  $scope.allSyllabusElements = [];
  
  for (var i=0;i<$scope.questions.length;i++) {
    var idx = $scope.questions[i].ID;
    $scope.allQuestions[idx] = $scope.questions[i];
  }
  for (var i=0;i<$scope.syllabuselements.length;i++) {
    var idx = $scope.syllabuselements[i].ID;
    $scope.allSyllabusElements[idx] = $scope.syllabuselements[i];
  }

  // only get list of actual SE
  $scope.getSE_Q();
  
  // Debugging
  console.log("============================");
  console.log("--- Modal Window opened. ---");
  console.log("============================");
  console.log("Element:");
  console.log($scope.Element);
  console.log("----------------------------");
  console.log("Items:");
  console.log(items);
  console.log("----------------------------");
  console.log("Scope:");
  console.log($scope);
  console.log("----------------------------");
  
  /*****************************************************
    Default values should go in here
   ****************************************************/  
  $scope.getActTopic = function() {
    for (var i = 0; i<$scope.topics.length; i++) {
      if ($scope.topics[i].id == $scope.Element.TopicID)
        $scope.object.data.ngTopic = $scope.topics[i];
    }
  }
  $scope.getActLang = function() {
    for (var i = 0; i<$scope.languages.length; i++) {
      if ($scope.languages[i].sqms_language_id == $scope.Element.LangID)
        $scope.object.data.ngLang = $scope.languages[i];
    }
  }
  $scope.getActQwner = function() {
    for (var i = 0; i<$scope.users.length; i++) {
      if ($scope.users[i].lastname == $scope.Element.Owner)
        $scope.object.data.ngOwner = $scope.users[i];
    }
  }
  $scope.getActQuesType = function() {
    $scope.object.data.ngQuesType = $scope.questypes[0]; // default value
    for (var i = 0; i<$scope.questypes.length; i++) {
      if ($scope.questypes[i].ID == $scope.Element.TypeID) {
        $scope.object.data.ngQuesType = $scope.questypes[i];
      }
    }
  }
  $scope.getActParentSyllabElement = function() {
    for (var i = 0; i<$scope.synamelist.length; i++) {
      if ($scope.synamelist[i].ID == $scope.object.data.parentID) {
        $scope.object.data.ngParent = $scope.synamelist[i];
      }
    }
  }
        
  if (cmd.indexOf("create") >= 0) {
    // Creating -> use default data, except parent
    $scope.object.data.parentID = $scope.Element.ID;
  } else
    $scope.object.data = $scope.Element; // Load data from selection
  

  // format dates correctly
  if ($scope.object.data.From != null) $scope.object.data.From = new Date($scope.object.data.From);    
  if ($scope.object.data.To != null) $scope.object.data.To = new Date($scope.object.data.To);

  // --- [OK] clicked
  $scope.ok = function () {
    
    // Set the new Topic if it has changed
    if ($scope.object.data.ngTopic) {
      $scope.object.data.TopicID = $scope.object.data.ngTopic.id;
      $scope.object.data.Topic = $scope.object.data.ngTopic.name;
    }
    if ($scope.object.data.ngLang) {
      $scope.object.data.LangID = $scope.object.data.ngLang.sqms_language_id;
    }
    if ($scope.object.data.ngParent && $scope.object.command != "create_answer") {
      $scope.object.data.parentID = $scope.object.data.ngParent.ID;
    }
    
    // Save all Question IDs from n:m
    $scope.object.data.QuestionIDs = []; // init new array  
    for (var i=0;i<$scope.bugFix.SEQuestions.length;i++) {
      $scope.object.data.QuestionIDs.push($scope.bugFix.SEQuestions[i].ID);
    }
    // Save all SyllabusElement IDs from n:m
    $scope.object.data.SyllabusElementIDs = []; // init new array  
    for (var i=0;i<$scope.bugFix.QSyllabusElements.length;i++) {
      $scope.object.data.SyllabusElementIDs.push($scope.bugFix.QSyllabusElements[i].ID);
    }
    
    console.log("============================");
    console.log("--- Modal Button OK clicked");    
    // Return result
    $uibModalInstance.close($scope.object);
  };
  // --- [Cancel] clicked
  $scope.cancel = function () {
    console.log("============================");
    console.log("--- Modal Button Cancel clicked");
    $uibModalInstance.dismiss('cancel');
  };
});

/***********************************************************
 *                    Main Controller                      *
 ***********************************************************/
module.controller('SQMSController', ['$scope', '$http', '$sce', '$uibModal',
  function($scope, $http, $sce, $uibModal) {
  
  $scope.setSelection = function(el){
    $scope.actSelection = el;
  };
  
  // Modal Window + Templates Functions
  
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
            questionlist: $scope.questions
          };
        },
        Elem: function () {
          return Element;
        }
      }
    });
    modalInstance.result.then(function (result) {
      console.log(result);
      $scope.writeData(result.command, result.data); // Send result to server
    }, function () {
      console.log('Modal Window closed at: ' + new Date());
    });
  };
    
  $scope.editEl = function(el) {
    console.log("--- Edit element clicked...");
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
  
  /******************************************************* Create Successor */
  $scope.createsuccessor = function(el) {
    if (el.state != 'new') {      
      var ElemName = "Syllabus";
      var Command = "create_successor_s";
      if (el.ElementType == "S") {
      } else if (el.ElementType == "Q") {
        ElemName = "Question";
        Command = "create_successor_q";
      }
      // Ask for confirmation
      if (confirm("Are you sure that you want to create a successor of this "+ElemName+" \n'"+el.Name+
        "'?\n\nInfo: This will create a new "+ElemName+
        " with a higher Version-Number and also sets the current "+ElemName+" to state DEPRECATED.")){
        $scope.writeData(Command, el);
      }
    }
  }
  /******************************************************* STATE MACHINE */
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
  
  // Sorting Tables
  // TODO: remove redundant code
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

  $scope.getTopics = function() {
    $http.get('getjson.php?c=topics').success(function(data) {
      $scope.topics = data.topiclist; // store in scope
    });
    return $scope.topics;
  };
  
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
  
  $scope.filterHTMLTags = function(html) {
    var div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
  }
  
  //------------------------------- Syllabus
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
  };
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
  
  // Selection function
  $scope.displ = function(el){
    el.showKids = !el.showKids;
  }
  
  //********************* Inline editing (can be implemented in writeData() maybe)
  $scope.saveEl = function(actEl, data, cmd) {
    var c;    
    console.log(actEl);
    console.log(data);
    console.log(cmd);    
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
  
  //********************* WRITE data to server
  $scope.writeData = function (command, data) {
    console.log("--- Sending command ("+command+") ...");

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
      console.log("--- Executed command successfully! Return: " + response);
      //========================
      // STATE TRANSITION
      //========================
      if (command.indexOf("_state") >= 0) {
        console.log(response);
        //alert((data.result ? "YES" : "NO") + "\n\n" + data.message);
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
  $scope.getAllSyllabus();
  $scope.getAllSyllabusElements();
  $scope.getAllQuestions();
  $scope.getAllAnswers();
  
  $scope.getTopics();
  $scope.getUsers();
  $scope.getLanguages();
  $scope.getQTypes();
  
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