<?php
  // Includes
  include_once '../phpSecureLogin/includes/db_connect.inc.php';
  include_once '../phpSecureLogin/includes/functions.inc.php';
  if(!isset($_SESSION)) sec_session_start();
  include_once("StateEngine.inc.php");
  include_once("RoleManager.inc.php");

  function getResultArray($result) {
    $results_array = array();
    while ($row = $result->fetch_assoc()) {
      $results_array[] = $row;
    }
    return $results_array;
  }

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
  public function handle($command, $params) {
    
    // Read out actual roles
    $this->roleIDs = $this->RM->getRoleIDsByLIAMid($_SESSION['user_id']);
    
    switch($command) {
     
      case 'syllabus':
        $arr1 = $this->getSyllabusList(); // All data from syllabus
        return json_encode($arr1);
        break;

      case 'getsyllabusdetails':
        $syllabid = $params["ID"];
        $actstate = $this->SESy->getActState($syllabid);
        @$actStateID = $actstate[0]['id'];
        $arr0 = array("parentID" => $syllabid);
        $arr1 = array("nextstates" => $this->SESy->getNextStates($actStateID)); // Possible next states
        $arr2 = $this->getFormDataByState($actStateID); // Form data for actual state
        $arr3 = $this->getSyllabusElementsList($syllabid);
        // Merge data
        $return = array_merge_recursive($arr0, $arr1, $arr2, $arr3);
        return json_encode($return);
        break;
        
      case 'syllabuselements':
        $return = $this->getSyllabusElementsList();
        return json_encode($return);
        break;
        
      case 'create_syllabus':
        return $this->addSyllabus(
          $params["Name"],
          $params["Owner"],
          $params["TopicID"],
          $params["description"],
          $params["From"],
          $params["To"],
          $params["LangID"]
          );
        break;
        
      case "update_syllabus":
        $res = 0;
        $res += $this->setSyllabusName($params["ID"], $params["Name"]); 
        $res += $this->setSyllabusTopic($params["ID"], $params["TopicID"]);
        $res += $this->setSyllabusDescr($params["ID"], $params["description"]);
        $res += $this->setSyllabusOwner($params["ID"], $params["Owner"]);
        $res += $this->setSyllabusFrom($params["ID"], $params["From"]);
        $res += $this->setSyllabusTo($params["ID"], $params["To"]);
        $res += $this->setSyllabusLang($params["ID"], $params["LangID"]);
        if ($res != 7) return ''; else return $res;
        break;
        
      case "update_syllabus_name":
        $res = $this->setSyllabusName($params["ID"], $params["name"]);
        if ($res != 1) return ''; else return $res;
        break;
        
      case "update_syllabus_topic":
        $res = $this->setSyllabusTopic($params["ID"], $params["TopicID"]);
        if ($res != 1) return ''; else return $res;
        break;
        
      case "update_question_topic":
        $res = $this->setQuestionTopic($params["ID"], $params["TopicID"]);
        if ($res != 1) return ''; else return $res;
        break;
        
      case 'create_syllabuselement':
        return $this->addSyllabusElement(
          $params["element_order"],
          $params["severity"],
          $params["parentID"],
          $params["name"],
          $params["description"]
        );
        break;
      
      case "create_successor":
        // check if current element has no predecessor
        return $this->createSuccessor($params);
        break;
        
      case 'update_syllabuselement':
        $res = $this->updateSyllabusElement(
          $params["ID"],
          $params["name"],
          $params["description"],
          $params["element_order"],
          $params["severity"],
          (int)$params["parentID"]
        );
        if ($res != 1) return ''; else return $res;
        break;
        
      //----------------------- Questions & Answers
      
      case 'questions':
        $return = $this->getQuestionList();
        return json_encode($return);
        break;
        
      case 'create_question':
        var_dump($params);
        return $this->addQuestion(
          $params['Question'],
          $params['Owner'],
          $params['ngTopic']['id']
        );
        break;
      
      case "update_question":
        $res = $this->updateQuestion($params["ID"], $params["Question"]);
        $res += $this->setQuestionTopic($params["ID"], $params['ngTopic']['id']);
        if ($res != 2) return ''; else return $res;
        break;
        
      case 'create_answer':
        return $this->addAnswer(
          $params["parentID"],
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

      // TODO: Remove...
      case 'delete_answer':
        //$res = $this->delAnswer($params["ID"]);
        $res = $this->delAnswer(1);
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
        
      // TODO: Evtl. zusammenfassen zu einem Command
        
      case "update_syllabus_state":
        return $this->setSyllabusState($params["syllabusid"], $params["stateid"]);
        break;

      case "update_question_state":
        return $this->setQuestionState($params["questionid"], $params["stateid"]);
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
    return getResultArray($res);
  }
  
  /********************************************** Syllabus */
  
  // TODO: Class
  /*
  class Syllabus {
    private $conn;
    private $table_name = "sqms_syllabus";
    // Object Properties
    public $id;
    public $name;
    public $version;
    public $state_id;
    public $topic_id;
    public $owner_id;
    public $lang_id;
    public $successor;
    public $predecessor;
    public $validfrom;
    public $validto;
    public $description;
    
    public function __construct($db) {
      $this->conn = $db;
    }
    public function create() {
      $query = "INSERT INTO ".$this->table_name." (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
        "validity_period_from, validity_period_to, description) VALUES (?,?,?,?,?,?,?,?,?);";
      $stmt = $this->conn->prepare($query);

      $this->name = htmlspecialchars(strip_tags($this->name));
      //$this->price = htmlspecialchars(strip_tags($this->price));
      $this->description = htmlspecialchars(strip_tags($this->description));
      //$this->category_id = htmlspecialchars(strip_tags($this->category_id));
      //$this->timestamp = htmlspecialchars(strip_tags($this->timestamp));
      
      $stmt->bindParam(1, $this->name);
      //$stmt->bindParam(2, $this->price);
      $stmt->bindParam(3, $this->description);
      //$stmt->bindParam(4, $this->category_id);
      //$stmt->bindParam(5, $this->timestamp);

      if($stmt->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
  */
  
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
      a.sqms_syllabus_id_predecessor AS 'PredID'
  FROM
      sqms_syllabus AS a LEFT JOIN sqms_topic AS b
  ON a.sqms_topic_id = b.sqms_topic_id
  LEFT JOIN sqms_language AS c
  ON c.sqms_language_id = a.sqms_language_id".$suffix.";";


    $return = array();
    $res = $this->db->query($query);
    $res = getResultArray($res);    
    $r = null;
    
    foreach ($res as $el) {
      $acts = $this->SESy->getActState($el["ID"])[0];
      $state = array("state" => $acts);
      $x = array_merge_recursive($el, $state);
      $r[] = $x;
    }
    $return['syllabus'] = $r;
    return $return;
  }
  private function addSyllabus($name, $owner, $topic, $description, $from, $to, $langID, $version = 1, $successor = null) {
    if ($successor != null)
      $query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
        "validity_period_from, validity_period_to, description, sqms_syllabus_id_successor) ".
        "VALUES (?,?,?,?,?,?,?,?,?,?);";
    else
      $query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
        "validity_period_from, validity_period_to, description) VALUES (?,?,?,?,?,?,?,?,?);";
    $stmt = $this->db->prepare($query);
    $one = 1;
    if ($successor != null)
      $stmt->bind_param("siiisisssi", $name, $one, $version, $topic, $owner, $langID, $from, $to, $description, $successor);
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
  private function updateSyllabusPredecessor($SyllabusID, $PredecessorID) {
    $query = "UPDATE sqms_syllabus SET sqms_syllabus_id_predecessor = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("ii", $PredecessorID, $SyllabusID);
    $result = $stmt->execute();
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
      $OldSyllabus["ID"] // Successor
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
    // update Old Syllabus (set Predecessor ID)
    $this->updateSyllabusPredecessor($OldSyllabus["ID"], $newID);
    // TODO: set state of old Syllabus to deprecated
    return $newID;
  }
  private function setSyllabusName($syllabid, $newname) {
    $query = "UPDATE sqms_syllabus SET name = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $newname, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setSyllabusTopic($syllabid, $topicID) {
    $query = "UPDATE sqms_syllabus SET sqms_topic_id = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("ii", $topicID, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setSyllabusDescr($syllabid, $descr) {
    $query = "UPDATE sqms_syllabus SET description = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $descr, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  // TODO: Set by Owner ID
  private function setSyllabusOwner($syllabid, $owner) {
    $query = "UPDATE sqms_syllabus SET owner = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $owner, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setSyllabusFrom($syllabid, $from) {
    $query = "UPDATE sqms_syllabus SET validity_period_from = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $from, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setSyllabusTo($syllabid, $to) {
    $query = "UPDATE sqms_syllabus SET validity_period_to = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $to, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setSyllabusLang($syllabid, $langID) {
    $query = "UPDATE sqms_syllabus SET sqms_language_id = ? WHERE sqms_syllabus_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $langID, $syllabid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }  
  /********************************************** SyllabusElement */
  
  private function getSyllabusElementsList($id=-1) {
    settype($id, 'integer');
    $query = "SELECT ".
    "sqms_syllabus_element_id, element_order, severity, sqms_syllabus_id,".
    "name, description FROM sqms_syllabus_element";
    if ($id > 0) {
      $query .= " WHERE sqms_syllabus_id = $id";
    }
    $query .= " ORDER BY element_order;";
    $return = array();
    $res = $this->db->query($query);
    $return['syllabuselements'] = getResultArray($res);
    return $return;
  }  
  private function addSyllabusElement($element_order, $severity, $parentID, $name, $description) {
    // TODO: Prepare statement
    $query = "INSERT INTO sqms_syllabus_element ".
      "(element_order, severity, sqms_syllabus_id, name, description) VALUES (".
      $element_order.",".
      $severity.",".
      $parentID.",".
      "'".$name."',".
      "'".$description."');";
        $result = $this->db->query($query);
    if (!$result) $this->db->error;
    return $result;
  }
  private function updateSyllabusElement($id, $name, $description, $elementorder, $severity, $parentID) {
    $query = "UPDATE sqms_syllabus_element SET name=?, description=?, element_order=?, severity=?, sqms_syllabus_id=? ".
      "WHERE sqms_syllabus_element_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("ssiiii", $name, $description, $elementorder, $severity, $parentID, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  
  /********************************************** Question */
  
  private function getQuestionList() {
    $query = "SELECT 
    a.sqms_question_id AS 'ID',
    b.name AS 'Topic',
    b.sqms_topic_id AS 'TopicID',
    a.question AS 'Question',
    a.author AS 'Owner',
    d.language AS 'Language',
    a.version AS 'Version',
    a.id_external AS 'ExtID',
    c.name AS 'Type'
FROM
    `sqms_question` AS a LEFT JOIN
    sqms_topic AS b ON a.sqms_topic_id = b.sqms_topic_id
LEFT JOIN sqms_question_type AS c
ON a.sqms_question_type_id = c.sqms_question_type_id
LEFT JOIN sqms_language AS d
ON d.sqms_language_id = a.sqms_language_id;";
    $rows = $this->db->query($query);
    $res = getResultArray($rows);
    $r = null;
    foreach ($res as $el) {
      $acts = $this->SEQu->getActState($el["ID"])[0];
      $state = array("state" => $acts);
      $x = array_merge_recursive($el, $state);
      $r[] = $x;
    }
    $return['questionlist'] = $r;
    return $return;
  }
  private function addQuestion($question, $author, $topicID) {
    $query = "INSERT INTO `sqms_question` (
  `sqms_language_id`,`sqms_question_state_id`,`question`,`author`,`version`,`id_external`,
  `sqms_question_id_predecessor`,`sqms_question_id_successor`,`sqms_question_type_id`,`sqms_topic_id`)
  VALUES (1,1,?,?,1,'',0,0,1,?);";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("ssi", $question, $author, $topicID); // bind params
    $result = $stmt->execute(); // execute statement
    return $result;
  }
  private function updateQuestion($id, $question) {
    $query = "UPDATE sqms_question SET question = ? WHERE sqms_question_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("si", $question, $id); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function setQuestionTopic($questionid, $topicID) {
    $query = "UPDATE sqms_question SET sqms_topic_id = ? WHERE sqms_question_id = ?;";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("ii", $topicID, $questionid); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  
  /********************************************** Answer */
  
  /*
  class Answer {
    private $conn;
    private $table_name = "sqms_answer";
    // Object Properties
    public $id;
    public $name;
  }
  */
  
  private function getAnswers($questionID) {
    settype($questionID , 'integer');
    $query = "SELECT sqms_answer_id AS 'ID', answer, correct FROM `sqms_answer` WHERE sqms_question_id = $questionID;";
    $res = $this->db->query($query);
    $return['answers'] = getResultArray($res);
    return $return;
  }
  private function addAnswer($questionID, $correct, $answer){
    $query = "INSERT INTO sqms_answer (sqms_question_id, answer, correct) VALUES (?,?,?);";
    $stmt = $this->db->prepare($query); // prepare statement
    $stmt->bind_param("isi", $questionID, $answer, $correct); // bind params
    $result = $stmt->execute(); // execute statement
    return (!is_null($result) ? 1 : null);
  }
  private function delAnswer($answerID){
    settype($answerID , 'integer');
    // Only delete elements where state = 1 (new)
    $query = "DELETE a FROM sqms_answer AS a
INNER JOIN sqms_question AS b ON a.sqms_question_id = b.sqms_question_id 
WHERE a.sqms_answer_id = $answerID AND b.sqms_question_state_id = 1;";
    var_dump($query);
    $result = $this->db->query($query);
    var_dump($result);
    $success = $this->db->affected_rows;
    var_dump($this->db);
    return ($success > 0 ? 1 : null);
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
    $return['topiclist'] = getResultArray($res);
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
  

  
  
  // TODO: implement into class StateEngine
  private function setSyllabusState($syllabid, $stateid) {
    // params
    settype($syllabid, 'integer');
    settype($stateid, 'integer');
    // get actual state from syllabus
    $actstateObj = $this->SESy->getActState($syllabid);
    if (count($actstateObj) == 0) return false;
    $actstateID = $actstateObj[0]["id"];
    // check transition
    $trans = $this->SESy->checkTransition($actstateID, $stateid);
    
    // check if transition is possible
    if ($trans) {
      $newstateObj = $this->SEQu->getStateAsObject($stateid);
      $scripts = $this->SESy->getTransitionScripts($actstateID, $stateid);
      // Execute all scripts from database at transistion
      foreach ($scripts as $script) {
        // Set path to scripts
        $scriptpath = "functions/".$script["transistionScript"];
        
        // Standard Result
        $script_result = array("result" => true, "message" => "");
        
        // If script exists then load it
        if (trim($scriptpath) != "functions/" && file_exists($scriptpath))
          include_once($scriptpath);
        // update state in DB, when plugin says yes
        if ($script_result["result"] == true) {
          $query = "UPDATE sqms_syllabus SET sqms_state_id = $stateid WHERE sqms_syllabus_id = $syllabid;";
          $res = $this->db->query($query);
        }
        // Return
        return json_encode($script_result);
      }
    }
    return false; // false zurückgeben
  }
  private function setQuestionState($questionid, $stateid) {
    // params
    settype($questionid, 'integer');
    settype($stateid, 'integer');
    // get actual state from question
    $actstateObj = $this->SEQu->getActState($questionid);
    if (count($actstateObj) == 0) return false;    
    $actstateID = $actstateObj[0]["id"];
    // check transition
    $trans = $this->SEQu->checkTransition($actstateID, $stateid);
    // check if transition is possible
    if ($trans) {
      // update state in DB
      $query = "UPDATE sqms_question SET sqms_question_state_id = $stateid WHERE sqms_question_id = $questionid;";
      $res = $this->db->query($query);
      $scripts = $this->SEQu->getTransitionScripts($actstateID, $stateid);
      
      /**** Execute all scripts from database at transistion ****/
      foreach ($scripts as $script) {
        // Set path to scripts
        $scriptpath = "functions/".$script["transistionScript"];
        // If script is not emptystring and exists
        if (trim($script["transistionScript"]) != "" && file_exists($scriptpath))
          include_once($scriptpath);
      }
      return true; //$scripts;
      
    } else
      return false;
  }
  

  
  
  // TODO: Obsolete!!!
  private function getFormDataByState($state) {
    if (!isset($state)) $state = 1;
    settype($state, 'integer');
    $query = "SELECT * FROM sqms_syllabus_state WHERE sqms_syllabus_state_id = $state;";
    $res = $this->db->query($query);
    $return = array();
    $tmp = getResultArray($res);
    $return['formdata'] = $tmp[0]['form_data'];
    return $return;
  }
  
  
  
  // ----------------------------------- Reports
  private function getReport_QuestionsWithoutQuestionmarks(){
    $query = "SELECT 'Questions without Questionmarks' as attr, COUNT(*) AS value, 'fa-question' AS icon FROM sqms_question WHERE question NOT LIKE '%?%';";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = getResultArray($res);
    return $return;
  }
  private function getReport_QuestionsTotal(){
    $query = "SELECT 'Questions total' as attr, COUNT(*) AS value, 'fa-question' AS icon FROM sqms_question;";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = getResultArray($res);
    return $return;
  }
  private function getReport_AnswersWhitoutMarks(){
    $query = "SELECT 'Answers without Marks' as attr, ".
      "COUNT(*) AS value, 'fa-exclamation' AS icon FROM sqms_answer ".
      "WHERE answer NOT LIKE '%.%' ".
      "OR answer NOT LIKE '%?%'";
    $return = array();
    $res = $this->db->query($query);
    $return['reports'] = getResultArray($res);
    return $return;
  }
}