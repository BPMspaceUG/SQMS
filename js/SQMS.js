var module = angular.module('SQMSApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce'])

// Needed for inline editing
module.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // needed for inline editing
});

/***********************************************************
 *                 Modal Window Controller                 *
 ***********************************************************/
module.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, items, cmd, parentid) {
  // <START>
  $scope.format = 'yyyy-MM-dd';
  $scope.p1 = { opened: false };
  $scope.p2 = { opened: false };
  // <END>

  // Date format when creating
  var ds = new Date();  
  var ds2 = new Date();
  ds2.setYear(ds2.getFullYear() + 1);
  
  // Initial settings
  $scope.object = {
    command: cmd,
    data: {
      name: '',
      ID: -1,
      parentID: parentid,
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

  $scope.topics = items.topics;
  $scope.users = items.users;
  $scope.languages = items.languages;
  $scope.synamelist = items.synamelist;
  $scope.questypes = items.questypes;
  $scope.questions = items.questionlist;
  
  //console.log($scope.users);
  console.log($scope);
  
  // Set selected item from syllabus 
  $scope.getActTopicSyllab = function() {
    for (var i = 0; i<$scope.topics.length; i++) {
      if ($scope.topics[i].id == $scope.$$prevSibling.actSyllabus.TopicID) {
        $scope.object.data.ngTopic = $scope.topics[i];
      }
    }
  }
  $scope.getActLangSyllab = function() {
    for (var i = 0; i<$scope.languages.length; i++) {
      if ($scope.languages[i].sqms_language_id == $scope.$$prevSibling.actSyllabus.LangID) {
        $scope.object.data.ngLang = $scope.languages[i];
      }
    }
  }
  $scope.getActQwnerSyllab = function() {
    for (var i = 0; i<$scope.users.length; i++) {
      if ($scope.users[i].lastname == $scope.$$prevSibling.actSyllabus.Owner) {
        $scope.object.data.ngOwner = $scope.users[i];
      }
    }
  }
  $scope.getActQwnerQuestion = function() {
    for (var i = 0; i<$scope.users.length; i++) {
      if ($scope.users[i].lastname == $scope.$$prevSibling.actQuestion.Owner) {
        $scope.object.data.ngOwner = $scope.users[i];
      }
    }
  }
  $scope.getActLangQuestion = function() {
    for (var i = 0; i<$scope.languages.length; i++) {
      if ($scope.languages[i].sqms_language_id == $scope.$$prevSibling.actQuestion.LangID) {
        $scope.object.data.ngLang = $scope.languages[i];
      }
    }
  }  
  $scope.getActTopicQuestion = function() {    
    for (var i = 0; i<$scope.topics.length; i++) {
      if ($scope.topics[i].id == $scope.$$prevSibling.actQuestion.TopicID) {
        $scope.object.data.ngTopic = $scope.topics[i];
      }
    }
  }
  $scope.getActQuesType = function() {
    $scope.object.data.ngQuesType = $scope.questypes[0]; // default value
    for (var i = 0; i<$scope.questypes.length; i++) {
      if ($scope.questypes[i].ID == $scope.$$prevSibling.actQuestion.TypeID) {
        $scope.object.data.ngQuesType = $scope.questypes[i];
      }
    }
  }
  $scope.getActParentSyllabElement = function() {
    for (var i = 0; i<$scope.synamelist.length; i++) {
      if ($scope.synamelist[i].ID == $scope.$$prevSibling.actSyllabus.ID) {
        $scope.object.data.ngParent = $scope.synamelist[i];
      }
    }
  }
  
  console.log("Modal Window opened.");

  // TODO: Improve code, so that the indexes remain the same name
  if (cmd == 'update_syllabus') {
    $scope.object.data = $scope.$$prevSibling.actSyllabus;
    // DATE Objects
    if ($scope.object.data.From != null) 
      $scope.object.data.From = new Date($scope.object.data.From);    
    if ($scope.object.data.To != null)
      $scope.object.data.To = new Date($scope.object.data.To);
  }
  else if (cmd == 'update_question') {
    $scope.object.data = $scope.$$prevSibling.actQuestion;
  }
  else if (cmd == 'update_syllabuselement') {
    $scope.object.data.ID = $scope.$$prevSibling.actSyllabusElement.sqms_syllabus_element_id;
    $scope.object.data.name = $scope.$$prevSibling.actSyllabusElement.name;
    $scope.object.data.description = $scope.$$prevSibling.actSyllabusElement.description;
    $scope.object.data.severity = $scope.$$prevSibling.actSyllabusElement.severity;
    $scope.object.data.element_order = $scope.$$prevSibling.actSyllabusElement.element_order;
    $scope.object.data.parentID = $scope.$$prevSibling.actSyllabusElement.sqms_syllabus_id; 
  }
  else if (cmd == 'create_answer') {
    $scope.object.data.parentID = $scope.$$prevSibling.actQuestion.ID;
  }
   
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
    // Return result
    $uibModalInstance.close($scope.object);
    console.log("Modal Button OK clicked");
  };
  // --- [Cancel] clicked
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
    console.log("Modal Button Cancel clicked");
  };
});

/***********************************************************
 *                    Main Controller                      *
 ***********************************************************/
module.controller('SQMSController', ['$scope', '$http', '$sce', '$uibModal',
  function($scope, $http, $sce, $uibModal) {

  // TODO: Make _ONE_ ObjectModel for Syllabus, Question and Topic => saves code

  $scope.setSelectedSyllabus = function (el) {$scope.actSyllabus = el;};
  $scope.setSelectedQuestion = function (el) {$scope.actQuestion = el;};
  $scope.setSelectedTopic = function (el) {$scope.actTopic = el;};

  // Modal Window + Templates Functions
  
  $scope.open = function (TemplateName, command) {
    var modalInstance = $uibModal.open({
      animation: false,
      templateUrl: TemplateName,
      controller: 'ModalInstanceCtrl', // pass controller
      resolve: {
        cmd: function () {
          return command;
        },
        parentid: function() {
          return $scope.actSyllabus.ID;
        },
        items: function () {
          return {
            topics: $scope.topics,
            users: $scope.users,
            languages: $scope.languages,
            synamelist: $scope.syllabi,
            questypes: $scope.questypes,
            questionlist: $scope.questions
          };
        }
      }
    });
    modalInstance.result.then(function (result) {
      console.log(result);
      $scope.writeData(result.command, result.data); // Send result to server
    }, function () {
      // Cancel Button clicked
      console.log("Modal Window closed.");
      //$log.info('Modal dismissed at: ' + new Date());
    });
  };
  
  
  $scope.editsyllabus = function(el) {
    $scope.setSelectedSyllabus(el);    
    if (el.state == 'new') // Only open modal in state "new"
      $scope.open('modalSyllabus.html', 'update_syllabus');
  }
  $scope.successorsyllabus = function(el) {
    $scope.setSelectedSyllabus(el);
    if (el.state != 'new') { // Create Successor not possible in state NEW
      // Ask for confirmation
      var res = confirm("Are you sure that you want to create a successor of the Syllabus \n'"+el.Name+
        "'?\n\nInfo: This will create a new Version of this Syllabus and sets the current to DEPRECATED.");
      if (res) {
        $scope.writeData('create_successor_s', el);
      }
    }
  }
  $scope.successorquestion = function(el) {
    console.log(el);
    $scope.setSelectedQuestion(el);
    if (el.state != 'new') { // Create Successor not possible in state NEW
      // Ask for confirmation
      var res = confirm("Are you sure that you want to create a successor of the Question \n'"+el.Question+
        "'?\n\nInfo: This will create a new Version of this Question and sets the current to DEPRECATED.");
      if (res) {
        $scope.writeData('create_successor_q', el);
      }
    }
  }
  $scope.editsyllabuselement = function(el) {
    $scope.actSyllabusElement = el;
    $scope.open('modalSyllabusElement.html', 'update_syllabuselement');
  }
  $scope.editquestion = function(el) {
    $scope.setSelectedQuestion(el);
    if (el.state == 'new')
      $scope.open('modalNewQuestion.html', 'update_question');
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
              // save all data in the element
              $scope.questions[k].availableOptions = a.nextstates; // next states
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
  
  $scope.refreshSyllabus = function(ID) {
    $http.get('getjson.php?c=syllabus')
    .success(function(data) {
      var slist = data.syllabus;
      for (var i=0;i<slist.length;i++){
        if (slist[i].ID == ID) {
          console.log(slist[i]);
          // Refresh only this syllabus
          var tmpKids = $scope.syllabi[i].syllabuselements; // save
          
          $scope.syllabi[i] = slist[i]; // replace
          
          $scope.syllabi[i].syllabuselements = tmpKids; // re-insert
          // TODO: only expand when it was expanded before
          $scope.syllabi[i].showKids = true; // ausklappen
          $scope.syllabi[i].state = $scope.syllabi[i].state.name;
          $scope.syllabi[i].HasNoChilds = false;
        }
      }
    });
  }
  
  
  //------------------------------- Syllabus
  $scope.getAllSyllabus = function () {
    $http.get('getjson.php?c=syllabus')
    .success(function(data) {
      $scope.syllabi = data.syllabus;
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
        .success(function(resp_data){          
          // find parent
          for (var k=0;k<$scope.syllabi.length;k++) {
            if ($scope.syllabi[k].ID == resp_data.parentID) {
              // save all data in the element
              $scope.syllabi[k].availableOptions = resp_data.nextstates; // next states
              // if has children
              if (resp_data.syllabuselements.length > 0) {
                $scope.syllabi[k].HasNoChilds = false; // has now children
                $scope.syllabi[k].syllabuselements = resp_data.syllabuselements;
                // Convert Angulartext into real HTML
                for (var j=0;j<$scope.syllabi[k].syllabuselements.length;j++) {
                  
                  // filter HTML Tags
                  var html = $scope.syllabi[k].syllabuselements[j].description;
                  var div = document.createElement("div");
                  div.innerHTML = html;
                  $scope.syllabi[k].syllabuselements[j].displDescr = div.textContent || div.innerText || "";
                  
                  // Format number
                  $scope.syllabi[k].syllabuselements[j].severity = Math.round($scope.syllabi[k].syllabuselements[j].severity);
                }
              }
            }
          }         
        })
      }
    });
  }
  
  // Selection function
  $scope.displ = function(el){el.showKids = !el.showKids;}
  $scope.setState = function(newstate) {$scope.actSyllabus.selectedOption = newstate;}

  //********************* Inline editing (can be implemented in writeData() maybe)
  $scope.saveEl = function(actEl, data, cmd) {
    var c;
    
    console.log(actEl);
    console.log(data);
    console.log(cmd);
    
    // TODO: Optimize code
    switch (cmd) {
      case 'u_answer_t': c = 'update_answer'; actEl.answer = data; break;
      case 'u_answer_c': c = 'update_answer'; actEl.correct = data; break;
      case 'u_syllabel_n': c = 'update_syllabuselement'; actEl.name = data; actEl.ID = actEl.sqms_syllabus_element_id; break;
      case 'u_syllabel_s': c = 'update_syllabuselement'; actEl.severity = data; actEl.ID = actEl.sqms_syllabus_element_id; break;
      case 'u_syllabel_ord': c = 'update_syllabuselement'; actEl.element_order = data; actEl.ID = actEl.sqms_syllabus_element_id; break;
      case 'u_topic_n': c = 'update_topic'; actEl.name = data; actEl.ID = actEl.id; break;
      case 'u_syllab_n': c = 'update_syllabus_name'; actEl.name = data; break;
      case 'u_syllab_tc': c = 'update_syllabus_topic'; actEl.TopicID = data; break;
      case 'u_question_tc': c = 'update_question_topic'; actEl.TopicID = data; break;
      case 'u_question_q': c = 'update_question_question'; actEl.Question = data; break;
    }
    // TODO:
    return $scope.writeData(c, actEl);
  }

  //********************* WRITE data to server
  $scope.writeData = function (command, data) {
    console.log("Sending command ("+command+") ...");
    console.log(data);
    
    $http({
      url: 'getjson.php?c=' + command,
      method: "POST",
      data: JSON.stringify(data)
    }).
    success(function(response){
      
      // Statemachine return
      if (command.indexOf("update_syllabus_state") >= 0) {
        /*****************************************
                   STATE TRANSITION
        *****************************************/
        console.log(response);
        //alert((data.result ? "YES" : "NO") + "\n\n" + data.message);
      }
      
      console.log("Executed command successfully! Return: " + response);
      
      // TODO: ... Heavy data ... Make this callback later or at least faster
      // TODO: Only update at certain commands (create, update, ...)

      // Refresh syllabus
      if (command.indexOf("syll") >= 0) {
        console.log("Refresh this syllabus....");
        console.log(data);
        console.log("Refreshing....");
        $scope.refreshSyllabus(data.ID);
        console.log("...");
        //$scope.getAllSyllabus(); // Refresh data
      }      
      
      if (command.indexOf("que") >= 0)
        $scope.getAllQuestions(); // Refresh data
      
      
    }).
    error(function(error) {
      console.log("Error! " + error);
    });
  }

  //--- Initial values
  $scope.actSyllabus = false;
  $scope.actSyllabusElement = false;
  $scope.actQuestion = false;
  $scope.actTopic = false;
  
  $scope.setstate = function(cmd, newstate) {
    $scope.writeData(cmd, {
      syllabusid: $scope.actSyllabus.ID,
      questionid: $scope.actQuestion.ID,
      stateid: newstate
    });
  }
  
  //---- Initial function calls
  $scope.getAllSyllabus();
  $scope.getAllQuestions();
  $scope.getTopics();
  $scope.getUsers();
  $scope.getLanguages();
  $scope.getQTypes();
}]);