<?php
  include_once '../phpSecureLogin/includes/db_connect.inc.php';
  include_once '../phpSecureLogin/includes/functions.inc.php';
  if(!isset($_SESSION)) sec_session_start();
  
  include_once("StateEngine.inc.php");
  include_once("RoleManager.inc.php");
  
/***********************************************
  RequestHandler
************************************************
  Handles all incoming requests from the
  Client and does all the handling between
  Database and Server.
***********************************************/
class RequestHandler
{
  private $db;
  private $SESy, $SEQu;
  private $RM;
  private $roleIDs;

  public function __construct() {
    // Get global variables here
    global $DB_host;
    global $DB_user;
    global $DB_pass;
    global $DB_name;
    
    // Connect to database
    $db = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
    if($db->connect_errno){
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
    
    // Set charset
    $db->query("SET NAMES utf8");
    
    // Save DB Connection
    $this->db = $db;
    
    //--- Create RoleManager
    $this->RM = new RoleManager();
    
    //--- Create state engine objects for Syllabus and Question
    // Params: [$db, $tbl_root, $tbl_states, $tbl_rules, $col_rootID, $col_stateID, $colname_stateID_at_TblStates]
    // StateEngine Syllabus
    $this->SESy = new StateEngine(
      $this->db,
      'sqms_syllabus',
      'sqms_syllabus_state',
      'sqms_syllabus_state_rules',
      'sqms_syllabus_id',
      'sqms_state_id',
      'sqms_syllabus_state_id'
    );
    // StateEngine Question
    $this->SEQu = new StateEngine(
      $this->db,
      'sqms_question',
      'sqms_question_state',
      'sqms_question_state_rules',
      'sqms_question_id',
      'sqms_question_state_id',
      'sqms_question_state_id'
    );
  }
  private function getResultArray($result) {
    $results_array = array();
    while ($row = $result->fetch_assoc()) {
      $results_array[] = $row;
    }
    return $results_array;
  }
  public function handle($command, $params) {
    
    // Read out actual roles
    $this->roleIDs = $this->RM->getRoleIDsByLIAMid($_SESSION['user_id']);
    
    switch($command) {
     
      case 'syllabus':
        $arr1 = $this->getSyllabusList(); // All data from syllabus
        return json_encode($arr1);
        break;

      case 'syllabuselements':
        $return = $this->getSyllabusElementsList();
        return json_encode($return);
        break;
        
      case 'create_syllabus':
        return $this->addSyllabus(
          $params["Name"],
          $params["ngOwner"]["lastname"],
          $params["TopicID"],
          $params["description"],
          $params["From"],
          $params["To"],
          $params["LangID"]
          );
        break;
        
      case "update_syllabus":
        $res = 0;
        $res += $this->updateSyllabusCol($params["ID"], "name", "s", $params["Name"]);
        $res += $this->updateSyllabusCol($params["ID"], "sqms_topic_id", "i", $params["TopicID"]);
        $res += $this->updateSyllabusCol($params["ID"], "description", "s", $params["description"]);
        $res += $this->updateSyllabusCol($params["ID"], "owner", "s", $params["ngOwner"]["lastname"]); // TODO: Change to ID in DB
        $res += $this->updateSyllabusCol($params["ID"], "validity_period_from", "s", $params["From"]);
        $res += $this->updateSyllabusCol($params["ID"], "validity_period_to", "s", $params["To"]);
        $res += $this->updateSyllabusCol($params["ID"], "sqms_language_id", "i", $params["LangID"]);
        if ($res != 7) return ''; else return $res;
        break;
        
      case "update_syllabus_name":
        $res = $this->updateSyllabusCol($params["ID"], "name", "s", $params["name"]);
        if ($res != 1) return ''; else return $res;
        break;
      
      case "update_syllabus_topic":
        $res = $this->updateSyllabusCol($params["ID"], "sqms_topic_id", "i", $params["TopicID"]);
        if ($res != 1) return ''; else return $res;
        break;
      
      case 'create_syllabuselement':
        return $this->addSyllabusElement(
          (int)$params["element_order"],
          (int)$params["severity"],
          (int)$params["parentID"], // PARENT => use ID of selection at creation
          $params["name"],
          $params["description"]
        );
        break;
      
      case "create_successor_s":
        return $this->createSuccessor($params);
        break;
        
      case "create_successor_q":
        return $this->createSuccessorQ($params);
        break;
        
      case 'update_syllabuselement':
        $res = $this->updateSyllabusElement(
          $params["ID"],
          $params["name"],
          $params["description"],
          $params["element_order"],
          $params["severity"],
          $params["parentID"], // not important... only if moved to another Syallabus
          @$params["QuestionIDs"]
        );
        if ($res != 1) return ''; else return $res;
        break;
        
      case 'syllabuselementsquestions':
        $res = $this->getSyllabusElementsQuestions();
        return json_encode($res);
        break;
        
	  case 'authortotopiclist':
		$return = $this->getAuthorToTopic();
		return json_encode($return);
		break;
      //----------------------- Questions & Answers
      
      case 'questions':
        $return = $this->getQuestionList();
        return json_encode($return);
        break;
        
      case 'questiontypes':
        $return = $this->getQuestionTypes();
        return json_encode($return);
        break;
        
      case 'create_question':
        if (!isset($params['ExtID'])) $params['ExtID'] = 0;
        return $this->addQuestion(
          $params['Question'],
          $params["ngOwner"]["lastname"],
          $params['ngTopic']['id'],
          $params['ExtID'],
          $params['ngLang']['sqms_language_id'],
          $params['ngQuesType']['ID'],
          1 // Version
        );
        break;
      
      case "update_question":
        $res = $this->updateQuestionCol($params["ID"], "question", "s", $params["Question"]);
        $res += $this->updateQuestionCol($params["ID"], "author", "s", $params["ngOwner"]["lastname"]);
        $res += $this->updateQuestionCol($params["ID"], "id_external", "i", $params["ExtID"]);
        $res += $this->updateQuestionCol($params["ID"], "sqms_topic_id", "i", $params['ngTopic']['id']);
        $res += $this->updateQuestionCol($params["ID"], "sqms_language_id", "i", $params['ngLang']['sqms_language_id']);
        $res += $this->updateQuestionCol($params["ID"], "sqms_question_type_id", "i", $params['ngQuesType']['ID']);
        $res += $this->updateQuestionSEIDs($params["ID"], $params['SyllabusElementIDs']);
        if ($res != 7) return ''; else return $res;
        break;
      
      case "update_question_topic":
        $res = $this->updateQuestionCol($params["ID"], "sqms_topic_id", "i", $params["TopicID"]);
        if ($res != 1) return ''; else return $res;
        break;
        
      case 'answers':
        $return = $this->getAnswers();
        return json_encode($return);
        break;
        
      case 'create_answer':
        return $this->addAnswer(
          $params["parentID"], // PARENT => use ID of selection at creation
          $params["correct"],
          $params["answer"]
        );
        break;
        
      case 'getanswers':
        $questionid = $params['ID'];
        $arr0 = array("parentID" => $questionid);
        $arr1 = $this->getAnswers($questionid);
        $actstate = $this->SEQu->getActState($questionid);
        @$actStateID = $actstate[0]['id'];
        $arr2 = array("nextstates" => $this->SEQu->getNextStates($actStateID)); // Possible next states
        // Merge data
        $return = array_merge_recursive($arr0, $arr1, $arr2);
        return json_encode($return);
        break;
        
      case 'update_answer':
        $res = $this->updateAnswer(
          $params["ID"],
          $params["answer"],
          $params["correct"]
        );
        if ($res != 1) return ''; else return $res;
        break;
      
      case 'users':
        $return = $this->RM->getUsers();
        return json_encode(array("userlist" => $return));
        break;
        
      case 'languages':
        $return = $this->getLanguages();
        return json_encode(array("langlist" => $return));
        break;
        
      //----------------------- Topics
      
      case 'topics': // Get relevant topics connected to role
        $return = $this->getTopicList();
        return json_encode($return);
        break;
        
      case 'create_topic': // Create a new topic
        return $this->addTopic($params["name"]);
        break;
            
      case 'update_topic': // Update a topic
        $res = $this->updateTopic($params["ID"], $params["name"]);
        if ($res != 1) return ''; else return $res;
        break;
      
      case "update_syllabus_state":
        return $this->SESy->setState($params["elementid"], $params["stateid"]);
        break;

      case "update_question_state":
        return $this->SEQu->setState($params["elementid"], $params["stateid"]);
        break;

        //-------- Reports for Dashboard
      case 'getreports':
        $arr1 = $this->getReport_QuestionsWithoutQuestionmarks();
        $arr2 = $this->getReport_QuestionsTotal();
        $arr3 = $this->getReport_AnswersWhitoutMarks();
        $return = array_merge_recursive($arr1, $arr2, $arr3);
        return json_encode($return);
        break;

      default:
        return ""; // empty string
        exit;
        break;
    }
  }
  
  ###################################################################################################################
  ####################### Definition der Handles
  ###################################################################################################################
  
  private function getLanguages() {
    $query = "SELECT sqms_language_id, language FROM sqms_language;";
    $res = $this->db->query($query);
    return $this->getResultArray($res);
  }
  
  /********************************************** Syllabus */
  
  private function getSyllabusList($shortlist = false) {    
    // Only return topics which are allowed for the actual role
    if ($this->roleIDs) {
      $suffix = " WHERE ";
      foreach ($this->roleIDs as $id) {
        $suffix .= "b.sqms_role_id = ".$id." OR ";
      }
      $suffix = substr($suffix, 0, -4); // remove last " OR "
    }

    $query = "SELECT 
      sqms_syllabus_id AS 'ID',
      a.name AS 'Name',
      version AS 'Version',
      b.name AS 'Topic',
      b.sqms_topic_id AS 'TopicID',
      a.description AS 'description',
      owner AS 'Owner',
      validity_period_from AS 'From',
      validity_period_to AS 'To',
      c.language AS 'Language',
      c.sqms_language_id AS 'LangID',
      a.sqms_syllabus_id_successor AS 'SuccID',
      a.sqms_syllabus_id_predecessor AS 'PredID',
      CONCAT('Language: ', c.language, ' - Version: ', version) AS displTxt,
      'S' AS 'ElementType'
  FROM
      sqms_syllabus AS a LEFT JOIN sqms_topic AS b
  ON a.sqms_topic_id = b.sqms_topic_id
  LEFT JOIN sqms_language AS c
  ON c.sqms_language_id = a.sqms_language_id".$suffix.";";

    $return = array();
    $res = $this->db->query($query);
    $res = $this->getResultArray($res);    
    $r = null;
    
    foreach ($res as $el) {
      $acts = $this->SESy->getActState($el["ID"])[0];
      $nexts = $this->SESy->getNextStates($acts["id"]);
      $state = array("state" => $acts, "nextstates" => $nexts);
      $x = array_merge_recursive($el, $state);
      $r[] = $x;
    }
    $return['syllabus'] = $r;
    return $return;
  }
  private function addSyllabus($name, $owner, $topic, $description, $from, $to, $langID, $version = 1, $predecessor = null) {
    if ($predecessor != null)
      $query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
        "validity_period_from, validity_period_to, description, sqms_syllabus_id_predecessor) ".
        "VALUES (?,?,?,?,?,?,?,?,?,?);";
    else
      $query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
        "validity_period_from, validity_period_to, description) VALUES (?,?,?,?,?,?,?,?,?);";
    $stmt = $this->db->prepare($query);
    $one = 1;
    if ($predecessor != null)
      $stmt->bind_param("siiisisssi", $name, $one, $version, $topic, $owner, $langID, $from, $to, $description, $predecessor);
    else
      $stmt->bind_param("siiisisss", $name, $one, $version, $topic, $owner, $langID, $from, $to, $description);
    $result = $stmt->execute();
    if (!$result) $this->db->error;
    // Return last inserted ID
    $res = null;
    if ($result)
      $res = $this->db->insert_id;
    return $res;
  }
  private function updateSyllabusCol($id, $column, $type, $content) {
    $query = "UPDATE sqms_syllabus SET $column = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param($type."i", $content, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function createSuccessor($OldSyllabus) {
    // Copy Syllabus with Successor and new Version
    $newID = $this->addSyllabus(
      $OldSyllabus["Name"],
      $OldSyllabus["Owner"],
      $OldSyllabus["TopicID"],
      $OldSyllabus["description"],
      $OldSyllabus["From"],
      $OldSyllabus["To"],
      $OldSyllabus["LangID"],
      (int)$OldSyllabus["Version"]+1, // increase version
      $OldSyllabus["ID"] // Predecessor
    );
    // Copy all Syllabus Elements
    $SyElements = $this->getSyllabusElementsList($OldSyllabus["ID"])["syllabuselements"];
    foreach ($SyElements as $SE) {
      // Add SyllabusElement
      $this->addSyllabusElement(
        $SE["element_order"],
        $SE["severity"],
        $newID,
        $SE["name"],
        $SE["description"]
      );
    }
    // Set Successor of old Syllabus
    $this->updateSyllabusCol($OldSyllabus["ID"], "sqms_syllabus_id_successor", "i", $newID);
    // Set state of old Syllabus to deprecated
    $this->updateSyllabusCol($OldSyllabus["ID"], "sqms_state_id", "i", 4);    
    return $newID;
  }
  
  /********************************************** SyllabusElement */
 
  
  
  private function getSyllabusElementsList($id=-1) {
    settype($id, 'integer');
    $query = "SELECT 
    sqms_syllabus_element_id AS 'ID',
    element_order,
    ROUND(severity) AS 'severity',
    a.sqms_syllabus_id AS 'parentID',
    a.name,
    a.description,
    'SE' AS 'ElementType',
    b.name AS 'parentName',
    b.sqms_language_id AS 'LangID',
    b.sqms_topic_id AS 'TopicID'
FROM
    sqms_syllabus_element AS a
        INNER JOIN
    sqms_syllabus AS b ON a.sqms_syllabus_id = b.sqms_syllabus_id";
    if ($id > 0) {
      $query .= " WHERE a.sqms_syllabus_id = $id";
    }
    $query .= " ORDER BY element_order;";
    
    $return = array();
    $res = $this->db->query($query);
    $return['syllabuselements'] = $this->getResultArray($res);
    return $return;
  }  
  private function addSyllabusElement($element_order, $severity, $parentID, $name, $description) {
    $query = "INSERT INTO sqms_syllabus_element (element_order, severity, sqms_syllabus_id, name, description) ".
             "VALUES (?,?,?,?,?);";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("iiiss", $element_order, $severity, $parentID, $name, $description);
    $result = $stmt->execute();
    if (!$result) $this->db->error;
    // Return last inserted ID
    $res = null;
    if ($result)
      $res = $this->db->insert_id;
    return $res;
  }
  private function updateQuestionSEIDs($QuestionID, $SyllabusElementIDs) {
    // Rewire n:m Connections to SyllabusElements
    if (!is_null($SyllabusElementIDs)) {
      $query = "INSERT INTO sqms_syllabus_element_question(sqms_question_id, sqms_syllabus_element_id) VALUES(?, ?) ON DUPLICATE KEY UPDATE sqms_question_id = sqms_question_id;";
      foreach ($SyllabusElementIDs as $SEID) {
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $QuestionID, $SEID);
        $result = $stmt->execute();      
      }
    }
    return (!is_null($result) ? 1 : null);
  }
  private function updateSyllabusElement($id, $name, $description, $elementorder, $severity, $parentID, $QuestionIDs) {
    // Rewire n:m Connections to Questions
    if (!is_null($QuestionIDs)) {
      $query = "INSERT INTO sqms_syllabus_element_question(sqms_question_id, sqms_syllabus_element_id) VALUES(?, ?) ON DUPLICATE KEY UPDATE sqms_question_id = sqms_question_id;";
      foreach ($QuestionIDs as $QID) {
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $QID, $id);
        $result = $stmt->execute();      
      }
    }
    // Update SyllabusElement Entry
    $query = "UPDATE sqms_syllabus_element SET name=?, description=?, element_order=?, severity=?, sqms_syllabus_id=? ".
      "WHERE sqms_syllabus_element_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("ssiiii", $name, $description, $elementorder, $severity, $parentID, $id); // bind params
    $result = $stmt->execute(); // execute statement    
    return (!is_null($result) ? 1 : null);
  }
  private function getSyllabusElementsQuestions() {
    $query = "SELECT
    sqms_syllabus_element_id AS 'SEID',
    sqms_question_id AS 'QID'
    FROM sqms_syllabus_element_question;";
    $rows = $this->db->query($query);
    $return['se_q_list'] = $this->getResultArray($rows);
    return $return;
  }
  
  private function getAuthorToTopic () {
	  $query = "SELECT DISTINCT 
	  sqms_role.role_name, sqms_topic.name, sqms_syllabus.sqms_topic_id
FROM sqms_syllabus
INNER JOIN sqms_topic, sqms_role
WHERE sqms_syllabus.sqms_topic_id = sqms_topic.sqms_topic_id
AND sqms_topic.sqms_role_id = sqms_role.sqms_role_id";
	$rows = $this->db->query($query);
    $return['authortotopic'] = $this->getResultArray($rows);
    return $return;  
  }
  /********************************************** Question */
  
  private function getQuestionTypes() {
    $query = "SELECT
    sqms_question_type_id AS 'ID',
    name,
    description FROM sqms_question_type;";
    $rows = $this->db->query($query);
    $return['qtypelist'] = $this->getResultArray($rows);
    return $return;
  }  
  private function getQuestionList() {
    $query = "SELECT 
    a.sqms_question_id AS 'ID',
    b.name AS 'Topic',
    b.sqms_topic_id AS 'TopicID',
    a.question AS 'Question',
    a.author AS 'Owner',
    d.language AS 'Language',
    a.sqms_language_id AS 'LangID',
    a.version AS 'Version',
    a.id_external AS 'ExtID',
    c.name AS 'Type',
    c.sqms_question_type_id AS 'TypeID',
    a.sqms_question_id_successor AS 'SuccID',
    'Q' AS 'ElementType'
FROM
    `sqms_question` AS a LEFT JOIN
    sqms_topic AS b ON a.sqms_topic_id = b.sqms_topic_id
LEFT JOIN sqms_question_type AS c
ON a.sqms_question_type_id = c.sqms_question_type_id
LEFT JOIN sqms_language AS d
ON d.sqms_language_id = a.sqms_language_id;";
    $rows = $this->db->query($query);
    $res = $this->getResultArray($rows);
    $r = null;
    foreach ($res as $el) {
      $acts = $this->SEQu->getActState($el["ID"])[0];
      $nexts = $this->SEQu->getNextStates($acts["id"]);
      $state = array("state" => $acts, "nextstates" => $nexts);
      $x = array_merge_recursive($el, $state);
      $r[] = $x;
    }
    $return['questionlist'] = $r;
    return $return;
  }
  private function addQuestion($question, $author, $topicID, $extID, $langID, $quesType, $version, $predecessorID = 0, $successorID = 0) {
    $query = "INSERT INTO sqms_question (
  sqms_question_state_id,
  sqms_language_id,
  question,
  author,
  version,
  id_external,
  sqms_question_id_predecessor,
  sqms_question_id_successor,
  sqms_question_type_id,
  sqms_topic_id)
  VALUES (1,?,?,?,?,?,?,?,?,?);";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("issiiiiii", $langID, $question, $author, $version, $extID, $predecessorID, $successorID, $quesType, $topicID); // bind params
    $result = $stmt->execute();
    if (!$result) $this->db->error;
    // Return last inserted ID
    $res = null;
    if ($result)
      $res = $this->db->insert_id;
    return $res;
  }
  private function updateQuestionCol($id, $column, $type, $content) {
    $query = "UPDATE sqms_question SET $column = ? WHERE sqms_question_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param($type."i", $content, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function createSuccessorQ($OldQuestion) {
    // Copy Question with Successor and new Version
    $newID = $this->addQuestion(
      $OldQuestion["Question"],
      $OldQuestion["owner"],
      $OldQuestion["TopicID"],
      $OldQuestion["ExtID"],
      $OldQuestion["LangID"],
      $OldQuestion["TypeID"],
      (int)$OldQuestion["Version"]+1, // increase version
      $OldQuestion["ID"] // Predecessor
    );
    // TODO: Copy all answers ...
    
    // Set Successor of old Question
    $this->updateQuestionCol($OldQuestion["ID"], "sqms_question_id_successor", "i", $newID);
    // Set state of old Question to deprecated
    $this->updateQuestionCol($OldQuestion["ID"], "sqms_question_state_id", "i", 4);
    return $newID;
  }
  
  /********************************************** Answer */

  private function getAnswers($questionID = -1) {
    settype($questionID , 'integer');
    $query = "SELECT ".
      "sqms_answer_id AS 'ID', answer, correct, 'A' AS 'ElementType',".
      "sqms_question_id AS 'parentID' FROM `sqms_answer`";
    if ($questionID > 0)
      $query .= " WHERE sqms_question_id = $questionID;";
    $res = $this->db->query($query);
    $return['answers'] = $this->getResultArray($res);
    return $return;
  }
  private function addAnswer($questionID, $correct, $answer){
    $query = "INSERT INTO sqms_answer (sqms_question_id, answer, correct) VALUES (?,?,?);";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("isi", $questionID, $answer, $correct); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function updateAnswer($id, $answer, $correct) {
    $query = "UPDATE sqms_answer SET answer = ?, correct = ? WHERE sqms_answer_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("sii", $answer, $correct, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  
  /********************************************** Topic */
  
  private function getTopicList() {
    // Only return topics which are allowed for the actual role
    if ($this->roleIDs) {
      $suffix = " WHERE ";
      foreach ($this->roleIDs as $id) {
        $suffix .= "sqms_role_id = ".$id." OR ";
      }
      $suffix = substr($suffix, 0, -4); // remove last " OR "
    }
    $query = "SELECT sqms_topic_id AS id, name FROM `sqms_topic`".$suffix;
    $res = $this->db->query($query);
    $return['topiclist'] = $this->getResultArray($res);
    return $return;
  }  
  private function addTopic($name) {
    $query = "INSERT INTO sqms_topic (name) VALUES (?);";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("s", $name); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function updateTopic($id, $name) {
    $query = "UPDATE sqms_topic SET name = ? WHERE sqms_topic_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $name, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  
  // ----------------------------------- Reports
  private function getReport_QuestionsWithoutQuestionmarks(){
    $query = "SELECT 'Questions without Questionmarks' as attr, COUNT(*) AS value, 'fa-question' AS icon FROM sqms_question WHERE question NOT LIKE '%?%';";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = $this->getResultArray($res);
    return $return;
  }
  private function getReport_QuestionsTotal(){
    $query = "SELECT 'Questions total' as attr, COUNT(*) AS value, 'fa-question' AS icon FROM sqms_question;";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = $this->getResultArray($res);
    return $return;
  }
  private function getReport_AnswersWhitoutMarks(){
    $query = "SELECT 'Answers without Marks' as attr, ".
      "COUNT(*) AS value, 'fa-exclamation' AS icon FROM sqms_answer ".
      "WHERE answer NOT LIKE '%.%' ".
      "OR answer NOT LIKE '%?%'";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = $this->getResultArray($res);
    return $return;
  }
}