'use strict';

var module = angular.module('SQMSApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce'])

// Needed for inline editing
module.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // needed for inline editing
});

// Controller of Modal Window
module.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, items, cmd, parentid) {

  // <START>

  /*
  $scope.today = function() {
    $scope.From = new Date();
  };
  $scope.today();

  $scope.clear = function() {
    $scope.dt = null;
  };
  */
  
  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };

  $scope.toggleMin();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };
  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.dt = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[2];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = { opened: false };
  $scope.popup2 = { opened: false };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }
    return '';
  }
  
  // <END>


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
      owner: '',
      From: ds, // .getFullYear() + '-' + (ds.getMonth()+1) + '-' + ds.getUTCDate(),
      To: ds2, // + '-' + (ds.getMonth()+1) + '-' + ds.getUTCDate(),
      description: '<p>Please enter a description</p>',
      correct: false,
      ngTopic: {},
      ngOwner: {}
    }
  };
  // Save topics in scope
  $scope.topics = items.topics;
  $scope.users = items.users;
  
  console.log($scope.object);
  
  // Set selected item from syllabus 
  $scope.getActTopicSyllab = function() {
    for (var i = 0; i<$scope.topics.length; i++) {
      if ($scope.topics[i].id == $scope.$$prevSibling.actSyllabus.TopicID) {
        $scope.object.data.ngTopic = $scope.topics[i];
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
  console.log("Modal opened.");
  
  // TODO: Improve code, so that the indexes remain the same name
  if (cmd == 'update_syllabus') {
    $scope.object.data = $scope.$$prevSibling.actSyllabus;
  }
  else if (cmd == 'update_question') {
    $scope.object.data.ID = $scope.$$prevSibling.actQuestion.ID;
    $scope.object.data.name = $scope.$$prevSibling.actQuestion.Question;
    //$scope.object.data.description = $scope.$$prevSibling.actQuestion.description;   
    $scope.object.data.owner = $scope.$$prevSibling.actQuestion.Author;
    $scope.object.data.topic = $scope.$$prevSibling.actQuestion.topic;
    $scope.object.data.lang = $scope.$$prevSibling.actQuestion.Language;
    $scope.object.data.extid = $scope.$$prevSibling.actQuestion.ExtID;
    //$scope.items = $scope.$$prevSibling.getTopics();
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
    if ($scope.object.data.ngOwner) {
      $scope.object.data.Owner = $scope.object.data.ngOwner.lastname;
    }
    // Return result
    $uibModalInstance.close($scope.object);
    console.log("Modal ok clicked");
  };
  // --- [Cancel] clicked
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
    console.log("Modal cancel clicked");
  };
});

// Main Controller
module.controller('SQMSController', ['$scope', '$http', '$sce', '$uibModal', function($scope, $http, $sce, $uibModal) {

  $scope.setSelectedSyllabus = function (el) {$scope.actSyllabus = el;};
  $scope.setSelectedQuestion = function (el) {$scope.actQuestion = el;};
  $scope.setSelectedTopic = function (el) {$scope.actTopic = el;};

  /**************************************************************
    Modal Window + Templates
  **************************************************************/
  $scope.open = function (TemplateName, command) {
    var modalInstance = $uibModal.open({
      animation: false,
      templateUrl: TemplateName,
      controller: 'ModalInstanceCtrl',
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
            users: $scope.users
          };
        }
      }
    });
    modalInstance.result.then(function (result) {
      console.log(result);
      $scope.writeData(result.command, result.data); // Send result to server
    }, function () {
      // Cancel Button clicked
      //$log.info('Modal dismissed at: ' + new Date());
    });
  };
  $scope.editsyllabus = function(el) {
    $scope.setSelectedSyllabus(el);
    // Only open edit form in state "new"
    if (el.state == 'new')
      $scope.open('modalEditSyllabus.html', 'update_syllabus');
  }
  $scope.successorsyllabus = function(el) {
    $scope.setSelectedSyllabus(el);
    // not possible in state NEW
    if (el.state != 'new') {
      // Ask user for confirmation
      var res = confirm("Are you sure that you want to create a successor of the Syllabus \n'"+el.Name+
        "'?\n\nInfo: This will create a new Version of this Syllabus and sets the current to DEPRECATED.");
      if (res) {
        //if OK was clicked
        $scope.writeData('create_successor', el);
      }
    }
  }
  $scope.editsyllabuselement = function(el) {
    $scope.actSyllabusElement = el;
    $scope.open('modalEditSyllabusElement.html', 'update_syllabuselement');
  }
  $scope.editquestion = function(el) {
    $scope.setSelectedQuestion(el);
    if (el.state == 'new')
      $scope.open('modalEditQuestion.html', 'update_question');
  }
  $scope.deleteanswer = function(answer) {
    // the php script will check if it is acually    
    $scope.writeData('delete_answer', answer);
    console.log(answer.ID);
  }
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
  
  //------------------------------- Topic
  
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
              //$scope.syllabi[k].formdata = a.formdata; // formular data
              // if has children
              if (a.syllabuselements.length > 0) {
                $scope.syllabi[k].HasNoChilds = false; // has now children
                $scope.syllabi[k].syllabuselements = a.syllabuselements;
                // Convert Angulartext into real HTML
                for (var j=0;j<$scope.syllabi[k].syllabuselements.length;j++) {
                  
                  var html = $scope.syllabi[k].syllabuselements[j].description;
                  var div = document.createElement("div");
                  div.innerHTML = html;
                  var txt = div.textContent || div.innerText || "";
                  
                  $scope.syllabi[k].syllabuselements[j].displDescr = txt;
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
      case 'u_topic_n': c = 'update_topic'; actEl.name = data; actEl.ID = actEl.id; break;
      case 'u_syllab_n': c = 'update_syllabus_name'; actEl.name = data; break;
      case 'u_syllab_tc': c = 'update_syllabus_topic'; actEl.TopicID = data; break;
      case 'u_question_tc': c = 'update_question_topic'; actEl.TopicID = data; break;
      case 'u_question_q': c = 'update_question'; actEl.Question = data; break;
    }
    // TODO:
    return $scope.writeData(c, actEl); //$http.post('getjson.php?c='+c, JSON.stringify(actEl)); // send new model
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
    success(function(data){
      
      // Statemachine return
      if (command.indexOf("update_syllabus_state") >= 0) {
        console.log(data);     
        alert((data.result ? "YES" : "NO") + "\n\n" + data.message);
      }      
      
      console.log("Executed command successfully! Return: " + data);
      // TODO: ... Heavy data ... Make this callback later or at least faster
      // TODO: Only update at certain commands (create, update, ...)
      if (command.indexOf("syll") >= 0)
        $scope.getAllSyllabus(); // Refresh data
      if (command.indexOf("que") >= 0)
        $scope.getAllQuestions(); // Refresh data
    }).
    error(function(error){      
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
  
  //---- Initial functions
  $scope.getAllSyllabus();
  $scope.getAllQuestions();
  $scope.getTopics();
  $scope.getUsers();
}]);