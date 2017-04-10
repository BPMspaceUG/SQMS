var module = angular.module('SQMSApp', ['ngSanitize', 'xeditable', 'ui.bootstrap', 'ui.tinymce', 'ui.select'])

// Needed for inline editing
module.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // needed for inline editing
});

/***********************************************************
 *                 Modal Window Controller                 *
 ***********************************************************/
module.controller('ModalInstanceCtrl', function ($scope, $window, $http, $uibModalInstance, items, cmd, Elem) {
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
    
	/* Get authors of syllabus element to topic and save it as Data in $scope.grenze TODO: Improve SQL call and check for security issues*/
  $scope.getAuthors = function () {									
			$http.get('getjson.php?c=authortotopiclist').success(function(data) {
			$scope.authores = data.authortotopic;
			$scope.grenze = $scope.authores;
			return $scope.authores; 	
			});
	}
	/* Initialize authors at modal load. And save act values in grenze.*/
	$scope.grenze = {};
	$scope.getAuthors();
	
	/* Check if ActTopic == Topic Name of one of the Object Topics (should be) and if true return the authors for this topic. */
  $scope.actAuthor = function(Element){
	  arr = [];
	  for (i =0; i<$scope.grenze.length; i++){
		  if ($scope.grenze[i].name == Element['Topic']){
			  zw = $scope.grenze[i].sqms_LIAMUSER_id;
				for (j = 0; j <$scope.users.length; j++){
					if (zw == ($scope.users[j].id)){
						zw = $scope.users[j].lastname;
					}
				}
			  if (zw != Element['Owner']){
			  arr.push(zw);
			  }
		}
	}
	return arr.join(', ');
  } 
  
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
  /* Tool for user input. Text editor*/
  $scope.tinymceOptions = {
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table contextmenu directionality',
      'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
    ],
    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
    image_advtab: true,
    theme : 'modern'
  };
  
  /* Initial settings */
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
  
  /* For Question Selection  */
    $scope.single = {availableOptions: ["Single","Multi"], selectedOption: "Single"};
	$scope.myDropDown = 'Single';
	
	/* HTML to Json */
	$scope.jsonString = "";
  
  // Important
  $scope.Element = Elem;  
  $scope.topics = items.topics;
  $scope.users = items.users;
  $scope.languages = items.languages;
  $scope.synamelist = items.synamelist;
  $scope.syllabuselements = items.syllabuselements;
  $scope.questypes = items.questypes;
  $scope.questions = items.questionlist;
  $scope.states = items.states;
  
  // Order Questions in a new array with IDs as Indices
  $scope.allQuestions = [];
  $scope.allSyllabusElements = [];
  
  // --------- Reformat Arrays  
  if ($scope.questions) {
    for (var i=0;i<$scope.questions.length;i++) {
      var idx = $scope.questions[i].ID;
      $scope.allQuestions[idx] = $scope.questions[i];
    }
  }
  if ($scope.syllabuselements) {
    for (var i=0;i<$scope.syllabuselements.length;i++) {
      var idx = $scope.syllabuselements[i].ID;
      $scope.allSyllabusElements[idx] = $scope.syllabuselements[i];
    }
  }

  // only get list of actual SE
  $scope.getSE_Q();

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
  
  // Pre-filter syllabuselements list for Assignment Input Field
  $scope.refreshSEFilterdList = function() {
    if ($scope.object.data.ngTopic) {
      $scope.object.data.TopicID = $scope.object.data.ngTopic.id;
      $scope.object.data.Topic = $scope.object.data.ngTopic.name;
    }
    if ($scope.object.data.ngLang) {
      $scope.object.data.LangID = $scope.object.data.ngLang.sqms_language_id;
    }    
    if ($scope.syllabuselements) {
      $scope.SElementsFiltered = [];
      for (var i=0;i<$scope.syllabuselements.length;i++) {
        if (($scope.syllabuselements[i].TopicID == $scope.object.data.TopicID) &&
            ($scope.syllabuselements[i].LangID == $scope.object.data.LangID)) {
          $scope.SElementsFiltered.push($scope.syllabuselements[i]);
        }
      }
    }
  }
  $scope.refreshQFilterdList = function() {
    if ($scope.object.data.ngTopic) {
      $scope.object.data.TopicID = $scope.object.data.ngTopic.id;
      $scope.object.data.Topic = $scope.object.data.ngTopic.name;
    }
    if ($scope.object.data.ngLang) {
      $scope.object.data.LangID = $scope.object.data.ngLang.sqms_language_id;
    }
    if ($scope.questions) {
      $scope.QFiltered = [];
      for (var i=0;i<$scope.questions.length;i++) {
        if (($scope.questions[i].TopicID == $scope.object.data.TopicID) &&
            ($scope.questions[i].LangID == $scope.object.data.LangID)) {
          $scope.QFiltered.push($scope.questions[i]);
        }
      }
    }
  }
  // Call the function
  $scope.refreshSEFilterdList();
  
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
    // Return result
    $uibModalInstance.close($scope.object);
  };
  // If there are Wrong or unfinished Questions be true.
	$scope.wrong = false;
  
  	$scope.topicModel = [];
	$scope.typeModel = [];
	$scope.languageModel = [];
	$scope.zwischenspeicher = [];
	$scope.jsonString = {};
	$scope.xmlString = "";


	/* Filtered Input after selection with select boxes. */
	$scope.filteredInput;
	
	/* Export data, JSON format=0 or XML format=1 */
	$scope.exportData = function(format, input){
	$scope.returns = "";
		if (format == 0){ // case Json
		
			for (var i in $scope.filteredInput){
					$scope.returns += $scope.toJ(input[i]);
			}
		}
		else if (format == 1){ // case XML
			$scope.inner = "";
			for (var i in $scope.filteredInput){
					$scope.inner += $scope.xmldata(input[i]);
			}
			$scope.returns = "<?xml version=\"1.0\" ?><quiz>" + $scope.inner + "</quiz>"
		}
		return $scope.returns;
	}

	/* Transform single JSON Object to right json format. TODO: No hard code create questions dynamically. 
	Before create a check mechanism of which questions are allowed to export for ex. dont export new only released. */
	$scope.toJ = function (a){

	if (a.answers.length == 4){
		$scope.user = 
		{
			title: "Example Questions",
			description: "Multiple answers can be correct.",
			questions: {
				[a.ID] : {
					question: a.Question,
					answers: [{
					answer: (a.answers[0].answer),
					correct: (a.answers[0].correct)
				}, {
					answer: (a.answers[1].answer),
					correct: (a.answers[1].correct)
				}, {
					answer: (a.answers[2].answer),
					correct: (a.answers[2].correct)
				}, {
					answer: (a.answers[3].answer),
					correct: (a.answers[3].correct)
				}],
				 
			}},	
		};
	}
	else if (a.answers.length == 3){
		$scope.user = 
		{
			title: "Example Questions",
			description: "Multiple answers can be correct.",
			questions: {
				[a.ID] : {
					question: a.Question,
					answers: [{
					answer: (a.answers[0].answer),
					correct: (a.answers[0].correct)
				}, {
					answer: (a.answers[1].answer),
					correct: (a.answers[1].correct)
				}, {
					answer: (a.answers[2].answer),
					correct: (a.answers[2].correct)
				}],
				 
			}},	
		};
	}
	else if (a.answers.length == 2)
	{
		$scope.user = {
		title: "Example Questions",
		description: "Multiple answers can be correct.",
		questions: {
			 [a.ID]: {
				question: a.Question,
				answers: [{
				answer: (a.answers[0].answer),
				correct: (a.answers[0].correct)
			}, {
				answer: (a.answers[1].answer),
				correct: (a.answers[1].correct)
			}],
			
		}},	
			};
	}	
	else 
	{
		// Not enough or too many Answers
		$scope.wrong = true;
		$scope.user = "Error in the question with ID: " + a.ID ;
	}
		$scope.json = angular.toJson ($scope.user);
		$scope.jsonString = $scope.json;

		return $scope.jsonString;
  }
  	/* Escape html in export for security reasons. */
	$scope.escapehtml = function (str){
		 
	str = encodeURIComponent(str);
		return str;

	}
	 
/* Function to create an XML Export element in Moodle XML. */
  $scope.xmldata = function(a){
	  
	  /* Returns the single answers dependent of their number nr in moodle/XML format.  */
   $scope.ans = function(nr){
	  if ( nr > (a.answers.length -1)){
		return "";
	  } 
	  else {
	  return "<answer fraction=\"" + $scope.fraction(nr) + "\"><text>"
	  + $scope.escapehtml(a.answers[nr].answer)+ "</text></answer>"
	  }
	}
	
	/* Returns a string with all answers of actQuestion in XML format*/
	$scope.answ = function(){
		var q = "";
		for(var i in a.answers){
			q += ($scope.ans(i));
		}
		return q;
	}
	/* Cuts the decimal number to fit the moodle format of 5 decimal numbers. */
	$scope.truncate = function (num, digits) {
		var numS = num.toString(),
			decPos = numS.indexOf('.'),
			substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
			trimmedResult = numS.substr(0, substrLength),
			finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;
		return parseFloat(finalResult);
	}
	
	/* Returns the fraction dependent of how many right answers there are for example 2 right answers: return 50 for each right answer and 0 for every false answer. */
    $scope.fraction = function (nr){
	   $scope.right = function (){
		   $scope.a = 0;
		   for(var i=0; i<a.answers.length; i++){
			   if (a.answers[i].correct == true){
				   $scope.a += 1;
			   }
		   }
			return $scope.a;
	   }
	  if (a.answers[nr].correct == false){
		  return "0"}
	  else return ($scope.truncate(100/($scope.right()), 5));
  }
	  
	  /* Returns the complete XML string of the conversion */
	  $scope.question = "<question type=\"multichoice\"><name><text>" 
	  + (a.ID) + "</text></name><questiontext format=\"html\"><text>" // Added the ID of the question to the short description field for moodle, so that there is a reference in moodle to SQMS.
	  + $scope.escapehtml(a.Question) + "</text></questiontext>"
	  + $scope.answ()
	  + "<single>false</single></question>";
	  
	  return $scope.question;
  }
	
  // --- [Cancel] clicked
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
  // --- [Export] clicked (not used)
  $scope.export = function () {

  };

  /* Filter the HTML tags for UI */
  $scope.filterHTMLTags = function(html) {		
      var div = document.createElement("div");
      div.innerHTML = html;
      return div.textContent || div.innerText || "";
  };
	
  /* Function to save data to a file on PC. */
  $scope.saveToPc = function (data, filename, type) {
	  if (!data) {
		console.error('No data');
		return;
	  }
	  if (!filename) {
		filename = 'download.json';
	  }	  
	  if (!type) {
		console.error('No type');
	  }
	  var data2 = $scope.exportData(type, data);

	  if (typeof data2 === 'object') {
		data2 = JSON.stringify(data, undefined, 2);
	  }
	  var blob = new Blob([data2], {type: 'text/json'});

	  // FOR IE:
	  if (window.navigator && window.navigator.msSaveOrOpenBlob) {
		  window.navigator.msSaveOrOpenBlob(blob, filename);
	  }
	  else{
		  var e = document.createEvent('MouseEvents'),
			  a = document.createElement('a');

		  a.download = filename;
		  a.href = window.URL.createObjectURL(blob);
		  a.dataset.downloadurl = ['text/json', a.download, a.href].join(':');
		  e.initEvent('click', true, false, window,
			  0, 0, 0, 0, 0, false, false, false, false, 0, null);
		  a.dispatchEvent(e);
	  }
	};
	
});

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


//Used to select and copy the Json result. TODO: Transform to angular. 
// TODO: Add something like $window.alert if there are unfinished or wrong Questions that where transformed to Json.
function selectJson(nfield){
  var e = document.getElementsByTagName('FIELDSET')[nfield]; // Watch out: If you create a second <fieldset> tag you have to increment [0].
  var r = document.createRange();
  r.selectNodeContents(e);
  var s = window.getSelection();
  s.removeAllRanges();
  s.addRange(r);
  document.execCommand("copy");  
} 

