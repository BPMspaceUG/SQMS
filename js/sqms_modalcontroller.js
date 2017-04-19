/***********************************************************
 *                 Modal Window Controller                 *
 ***********************************************************/
angular.module('SQMSApp').controller('ModalInstanceCtrl',
  function ($scope, $window, $http, $uibModalInstance, items, cmd, Elem) {

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
    
  // Get authors of syllabus element to topic and save it as Data in $scope.grenze TODO: I think taht the DB Authors is not in the format expected in the live production.
  $scope.getAuthors = function () {
      $http.get('getjson.php?c=authortotopiclist').success(function(data) {
      $scope.authores = data.authortotopic;
      $scope.grenze = $scope.authores;
      return $scope.authores;
    });
  }
  // Initialize Authors at modal load. And save act values in grenze.
  $scope.grenze = {};
  $scope.getAuthors();
  
  // Check if ActTopic == Topic Name of one of the Object Topics (should be) and if true return the Authors for this Topic.
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
  
  // Initial settings
  $scope.object = {
    command: cmd,
    data: {
      name: '', parentID: -1, element_order: 1, severity: 25, answer: '', Owner: '', Version: 1,
      From: ds, To: ds2, description: '<p>Enter a description...</p>', correct: false, ngTopic: {},
      ngOwner: {}, ngLang: {}, ngQuesType: {}, ngParent: {}
    }
  };
  
  // For Question Selection
  
  $scope.single = {availableOptions: ["Single","Multi"], selectedOption: "Single"};
  $scope.myDropDown = 'Single';
  // HTML to Json 
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

  $scope.okandnew = function () {    
    // Reopen
    console.log("XXX", $scope);
    $scope.$$prevSibling.open('modalAnswer.html', 'create_answer', $scope.Element);
    // Save
    $scope.ok();
  };


  // If there are Wrong or unfinished Questions be true.
  $scope.wrong = false;
  
  $scope.topicModel = [];
  $scope.typeModel = [];
  $scope.languageModel = [];
  $scope.zwischenspeicher = [];
  $scope.jsonString = {};
  $scope.xmlString = "";


  // Filtered Input
  $scope.filteredInput;
  // Export data, JSON format=0 or XML format=1
  $scope.exportData = function(format, input){
  	console.log(input);
  $scope.returns = "";
    if (format == 0){ // case Json multi
    
      for (var i in input){
       // console.log("Input ", input);
          $scope.returns += $scope.toJ(input[i]);
      }
    }
    else if (format == 02){ // case Json single
      $scope.returns += $scope.toJ(input);
    }
    else if (format == 1){ // case XML multi
    $scope.inner = "";
      for (var i in input){
          $scope.inner += $scope.xmldata(input[i]);
      }
      $scope.returns = "<?xml version=\"1.0\" ?><quiz>" + $scope.inner + "</quiz>"
    }
    else if (format == 12){ // case XML single
    $scope.inner = $scope.xmldata(input);
      $scope.returns = "<?xml version=\"1.0\" ?><quiz>" + $scope.inner + "</quiz>"
    }
    return $scope.returns
}

  // Transform single JSON Object to right json format.
  $scope.toJ = function (a){
//console.log(a);
    if (a.answers.length == 3){
  $scope.user = 
    {
      title: "Example Questions",
      description: "Only one answer is correct.",
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
    description: "Only one answer is correct.",
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
    $scope.user = "Fehlerhafte Frage";
  }
    $scope.json = angular.toJson ($scope.user);
    $scope.jsonString = $scope.json;

  //  $scope.fullJsonMulti = {}; // Erstmal String zwischenspeicher wieder leeren.
  //  $scope.fullJsonMulti += $scope.jsonString; 

    return $scope.jsonString;
  }
    // Escape html in export for security reasons.
  // $scope.escapehtml = function (str){
     
  // str = encodeURIComponent(str);
  //   return str;

  // }


	$scope.escapehtml = function(string) {
	  var entityMap = {
		  '&': '&amp;',
		  '<': '&lt;',
		  '>': '&gt;',
		  '"': '&quot;',
		  "'": '&#39;',
		  '/': '&#x2F;',
		  '`': '&#x60;',
		  '=': '&#x3D;'
		};

	  return String(string).replace(/[&<>"'`=\/]/g, function (s) {
	    return entityMap[s];
	  });
	}
   
// TODO: Write the function to create an XML Export element in Moodle XML.
  $scope.xmldata = function(a){
    //Answers 
   $scope.que = function(nr){
    if ( nr > (a.answers.length -1)){
    return "";
    } 
    else {
    return "<answer fraction=\"" + $scope.fraction(nr) + "\"><text>"
    + $scope.escapehtml(a.answers[nr].answer)+ "</text></answer>"
    }
  }
  // Returns the fraction dependent of how many right answers there are for example 2 right answers: return 50 for each right answer and 0 for false answer.
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
     // TODO: Create handlers for other cases: 2 questions, 4 ...
    if ($scope.right() == 1){
      if (a.answers[nr].correct == false){
      return "0"}
      else return "100";    
    }
    else if ($scope.right() == 2){
      if (a.answers[nr].correct == false){
      return "0"}
      else return "50";
    }
    else if ($scope.right() == 3){
      if (a.answers[nr].correct == false){
      return "0"}
      else return "33.33333";
    }
    //TODO: Pop up that there are false elements.
    else return "too many or few right";
  }
    
    
    $scope.question = "<question type=\"multichoice\"><name><text>" 
    + (a.ID) + "</text></name><questiontext format=\"html\"><text>"  //TODO: If a question name exists add it at the beginning of the line
    //+ "<![CDATA[" + (a.Question)+ "]]>" + "</text></questiontext>" 
    + $scope.escapehtml(a.Question) + "</text></questiontext>" 
    + $scope.que(0) 
    + $scope.que(1)
    + $scope.que(2)
    + "<single>false</single></question>";
    //console.log($scope.question);
    return $scope.question;
  }
  
  // --- [Cancel] clicked
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
  // --- [Export] clicked
  $scope.export = function () {

  };

  $scope.filterHTMLTags = function(html) {    
      var div = document.createElement("div");
      div.innerHTML = html;
      return div.textContent || div.innerText || "";
    };
  
  
  // Function to save data to a file on PC.
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