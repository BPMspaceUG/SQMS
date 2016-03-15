<?php
function getResultArray($result) {
	$results_array = array();
	while ($row = $result->fetch_assoc()) {
		$results_array[] = $row;
	}
	return $results_array;
}

/****************************
  S T A T E     E N G I N E  
****************************/
class StateEngine {
	private $actState = -1;
	private $db;
	// tables
	private $table = 'sqms_syllabus'; // root element
	private $table_states = 'sqms_syllabus_state'; // states
	private $table_rules = 'sqms_syllabus_state_rules'; // rules
	// columns
	private $colname_rootID = 'sqms_syllabus_id';
	private $colname_stateID = 'sqms_state_id';
	private $colname_stateID_at_TblStates = 'sqms_syllabus_state_id';
	private $colname_stateName = 'name'; // const
	private $colname_from = 'sqms_state_id_FROM'; // const
	private $colname_to = 'sqms_state_id_TO'; // const
	
	public function __construct($db, $tbl_root, $tbl_states, $tbl_rules, $col_rootID, $col_stateID, $colname_stateID_at_TblStates) {
		// set initial variables
		$this->db = $db;
		$this->table = $tbl_root;
		$this->table_states = $tbl_states;
		$this->table_rules = $tbl_rules;
		$this->colname_rootID = $col_rootID;
		$this->colname_stateID = $col_stateID;
		$this->colname_stateID_at_TblStates = $colname_stateID_at_TblStates;
	}
	public function getActState($id) {
		settype($id, 'integer');
		$query = "SELECT a.".$this->colname_stateID." AS 'id', b.".
			$this->colname_stateName." AS 'name' FROM ".$this->table." AS a INNER JOIN ".
			$this->table_states." AS b ON a.".$this->colname_stateID."=b.".$this->colname_stateID_at_TblStates.
			" WHERE ".$this->colname_rootID." = $id;";
		//echo $query;
		$res = $this->db->query($query);
		return getResultArray($res);
	}
	public function getNextStates($actstate) {
		settype($actstate, 'integer');
		$query = "SELECT a.".$this->colname_to." AS 'id', b.".
			$this->colname_stateName." AS 'name' FROM ".$this->table_rules." AS a INNER JOIN ".
			$this->table_states." AS b ON a.".$this->colname_to."=b.".$this->colname_stateID_at_TblStates.
			" WHERE ".$this->colname_from." = $actstate;";
		$res = $this->db->query($query);
		return getResultArray($res);
	}
	public function setState($db, $stateID) {
		// TODO: params: tablename
		// return Transition Scripts
		$trans_possible = $this->checkTransition($db, $actState, $stateID);
		if ($trans_possible) {
			// Write to DB
		}
	}
	public function checkTransition($fromID, $toID) {
		settype($fromID, 'integer');
		settype($toID, 'integer');
		$query = "SELECT * FROM ".$table." WHERE ".$this->$colname_from." = $fromID ".
			"AND ".$this->colname_to." = $toID;";
		$return = array();
		$res = $this->db->query($query);
		$cnt = $res->num_rows;
        return ($cnt > 0);
	}
}

class RequestHandler 
{
    private $db;
    private $discount = 0;
	private $SESy, $SEQu;

    public function __construct() {
		// Get global variables here
		global $DB_host;
		global $DB_user;
		global $DB_pass;
		global $DB_name;
		$db = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
		/* check connection */
		if($db->connect_errno){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$db->query("SET NAMES utf8");
        $this->db = $db;
		
		// Create state engines
		// Params: $db, $tbl_root, $tbl_states, $tbl_rules, $col_rootID, $col_stateID, $colname_stateID_at_TblStates
		$this->SESy = new StateEngine($this->db, 'sqms_syllabus', 'sqms_syllabus_state', 'sqms_syllabus_state_rules',
			'sqms_syllabus_id', 'sqms_state_id', 'sqms_syllabus_state_id');
			
		$this->SEQu = new StateEngine($this->db, 'sqms_question', 'sqms_question_state', 'sqms_question_state_rules',
			'sqms_question_id', 'sqms_question_state_id', 'sqms_question_state_id');
    }
    public function handle($command, $params) {
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
				$arr1 = $this->getSyllabusPossibleNextStates($actStateID); // Possible next states
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
				return $this->addSyllabus($params);
				break;
				
			case "update_syllabus":
				return $this->updateSyllabus($params);
				break;
				
			case "copy_syllabus":
				$this->copySyllabus($params);
				return "1"; // TODO
				break;
				
			/***********************
			case "swag":
				$actState = $this->SEQu->getActState(104);
				$nextStates = $this->SEQu->getNextStates($actState[0]["id"]);
				$res = array_merge_recursive($actState, $nextStates);
				echo '<pre>';
				var_dump($res);
				echo '</pre>';
				break;
			*************************/

			//-------- Topics
			
			case 'topics':
				$return = $this->getTopicList();
				return json_encode($return);
				break;
				
			case 'create_topic':
				return $this->addTopic($params["name"]);
				break;
				
			case 'delete_topic': // doesnt work because of settings in database
				return $this->delTopic($params["sqms_topic_id"]);
				break;
				
			case 'update_topic':
				return $this->updateTopic($params);
				break;
			
			//-------- Questions
			
			case 'questions':
				$return = $this->getQuestionList();
				return json_encode($return);
				break;
				
			case 'create_question':
				$question = $params['Question'];
				$author = $params['Author'];
				$topicID = 11; // $params['topic_id'];
				$res = $this->addQuestion($question, $author, $topicID);
				if ($res) echo 1; // return something, so its not bad request
				break;
				
			case 'update_question':
				return $this->updateQuestion($params);
				break;
				
			case 'getanswers':
				$questionid = $params['ID'];
				$arr0 = array("parentID" => $questionid);
				$arr1 = $this->getAnswers($questionid);
				// Merge data
				$return = array_merge_recursive($arr0, $arr1);
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
	
	// TODO: Make class for state machine
	
    private function getSyllabusList() {
		$query = "SELECT 
    sqms_syllabus_id AS 'ID',
    a.name AS 'Name',
    version AS 'Version',
    b.name AS 'Topic',
    owner AS 'Owner'
FROM
    sqms_syllabus AS a
    LEFT JOIN sqms_topic AS b ON a.sqms_topic_id = b.sqms_topic_id;";
        $return = array();
		$res = $this->db->query($query);
		$res = getResultArray($res);
		$r = null;
		foreach ($res as $el) {
			//$state = array("state" => $this->SESy->getActState($el["ID"])[0]);
			$acts = $this->SESy->getActState($el["ID"])[0];
			$state = array("state" => $acts);
			$x = array_merge_recursive($el, $state);
			$r[] = $x;
		}
		$return['syllabus'] = $r;
		return $return;
    }
	private function addSyllabus($params) {
		
		// TODO: Prepare statement
		$query = "INSERT INTO sqms_syllabus ".
			"(name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, ".
			"validity_period_from, validity_period_to, description) VALUES (".
			"'".$params["name"]."',".
			"1,". // StateID (alwas 1 at creating)
			"1,". // Version
			"1,". // Topic
			"'".$params["owner"]."',".
			"1,". // LangID
			"CURDATE(),".
			"DATE_ADD(CURDATE(), INTERVAL 1 YEAR),".
			"'".$params["description"]."');";
        $result = $this->db->query($query);
		if (!$result) $this->db->error;
		return $result;
	}
	// ------------------------------------- Questions
	private function getQuestionList() {
        $query = "SELECT a.sqms_question_id AS 'ID',
b.name AS 'Topic',
a.question AS 'Question',
a.author AS 'Author',
a.version AS 'Vers',
a.id_external AS 'ExtID',
a.sqms_question_type_id AS 'Type'
 FROM `sqms_question` AS a LEFT JOIN sqms_topic AS b ON a.sqms_topic_id = b.sqms_topic_id";
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
	private function getAnswers($questionID) {
		settype($questionID , 'integer');
        $query = "SELECT sqms_answer_id AS 'ID', answer, correct FROM `sqms_answer` WHERE sqms_question_id = $questionID;";
		$res = $this->db->query($query);
        $return['answers'] = getResultArray($res);
        return $return;
	}
	

	private function copySyllabus($oldSyllabus) {
		$this->addSyllabus($oldSyllabus);
	}
	private function getSyllabusPossibleNextStates($actstate) {
		settype($actstate, 'integer');
		$query = "SELECT a.sqms_state_id_TO, b.name 
FROM sqms_syllabus_state_rules AS a
INNER JOIN sqms_syllabus_state AS b
ON a.sqms_state_id_TO = b.sqms_syllabus_state_id
WHERE sqms_state_id_FROM = $actstate;";
		$return = array();
		$res = $this->db->query($query);
		$return['nextstates'] = getResultArray($res);
        return $return;
	}
	private function updateSyllabus($params) {
		// check state first, then decide which rights are possible on server side
		$actstate = $params["sqms_state_id"];
		$newstate = $params["selectedOption"]["sqms_state_id_TO"];
		$id = $params["ID"];
		// update
		$this->setSyllabusState($id, $newstate);
		$this->setSyllabusName($id, $params["name"]);
		return "Updated! ($newstate) ".time();
	}
	private function updateAnswer($id, $answer, $correct) {
		$query = "UPDATE sqms_answer SET answer = ?, correct = ? WHERE sqms_answer_id = ?;";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("sii", $answer, $correct, $id); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
	}
	private function setSyllabusName($syllabid, $newname) {
		$query = "UPDATE sqms_syllabus SET name = ? WHERE sqms_syllabus_id = ?;";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("si", $newname, $syllabid); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
	}
	private function setSyllabusState($syllabid, $stateid) {
		// params
		settype($syllabid, 'integer');
		settype($stateid, 'integer');
		// get actual state from syllabus
		$actstate = $this->SESy->getActState($syllabid)[0]["id"];
		// check transition
		$trans = $this->SESy->checkTransition($this->db, $actstate, $stateid);
		// check if transition is possible
		if ($trans) {
			// update state in DB
			$query = "UPDATE sqms_syllabus SET sqms_state_id = $stateid WHERE sqms_syllabus_id = $syllabid;";
			$res = $this->db->query($query);
			$scripts = $this->getTransitionScripts($actstate, $stateid);
			return $scripts;
		} else
			return false;
	}
	private function getTransitionScripts($from, $to) {
		settype($from, 'integer');
		settype($to, 'integer');
		$query = "SELECT transistionScript FROM sqms_syllabus_state_rules WHERE ".
		"sqms_state_id_FROM = $from AND sqms_state_id_TO = $to;";
		$return = array();
		$res = $this->db->query($query);
		$return = getResultArray($res);
        return $return;
	}
	private function addTopic($name) {
		$query = "INSERT INTO sqms_topic (name) VALUES (?);";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("s", $name); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
	}
	private function delTopic($id) {
		// Deleten darf der user dann sowieso nicht
		// TODO: Prepare statement
		$query = "UPDATE sqms_topic SET name = 'XXXXXXX' WHERE sqms_topic_id = ".$id.";";
        $result = $this->db->query($query);
		//if (!$result) $this->db->error;
		return (!is_null($result) ? 1 : null);
	}
	private function updateTopic($params) {
		$query = "UPDATE sqms_topic SET name = ? WHERE sqms_topic_id = ?;";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("si", $name, $id); // bind params
		$name = $params["name"];
		$id = $params["sqms_topic_id"];
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
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
	private function updateQuestion($params) {
		/* TODO:
		$query = "UPDATE sqms_question SET name = ? WHERE sqms_topic_id = ?;";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("si", $name, $id); // bind params
		
		$name = $params["name"];
		$id = $params["sqms_topic_id"];
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
		*/
		return null;
	}
    private function getSyllabusElementsList($id=-1) {
		settype($id, 'integer');
        $query = "SELECT * FROM sqms_syllabus_element"; // TODO: Replace * -> column names
		if ($id > 0)
			$query .= " WHERE sqms_syllabus_id = $id";
		$query .= " ORDER BY element_order;";
        $return = array();
		$res = $this->db->query($query);
        $return['syllabuselements'] = getResultArray($res);
        return $return;
    }
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
	private function getTopicList() {
        $query = "SELECT * FROM `sqms_topic`";
		$res = $this->db->query($query);
        $return['topiclist'] = getResultArray($res);
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