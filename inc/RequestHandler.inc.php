<?php

/**
 * Created by PhpStorm.
 * User: cwalonka
 * Date: 30.09.15
 * Time: 09:08
 */
class RequestHandler 
{
    private $db;
    private $discount = 0;

    private function getResultArray($result) {
        /* Zweck: Ausgabe einer Debug */
        /*if(true){
            echo "<pre>";
            echo "<hr>Query:<br>";
            var_dump($query);
            echo "</pre>";
        }*/
        $results_array = array();
        while ($row = $result->fetch_assoc()) {
            $results_array[] = $row;
        }
        return $results_array;
    }
    public function __construct() {
		// Get global variables here
		global $DB_host;
		global $DB_user;
		global $DB_pass;
		global $DB_name;
		// TODO: Initialize the connection only ONCE, like in EduMS API -> DB Config
		$db = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
		/* check connection */
		if($db->connect_errno){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		//$config['text']['defaultfooter'] = "powered by mITSM.de";
		$db->query("SET NAMES utf8");
        $this->db = $db;
    }
    public function handle($command, $params) {
		
        switch($command) {
			
			case 'syllabus':
				$arr1 = $this->getSyllabusList(); // All data from syllabus
				return json_encode($arr1);
                break;
				
			case 'getsyllabusdetails':
				$syllabid = $params["ID"];
				$actstate = $this->getSyllabusState($syllabid);
				
				$arr0 = array("parentID" => $syllabid);
				$arr1 = $this->getSyllabusPossibleNextStates($actstate); // Possible next states
				$arr2 = $this->getFormDataByState($actstate); // Form data for actual state
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
				return $this->addQuestion($params);
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
			
			//-------- Reports for Dashboard
			case 'getreports':
				$arr1 = $this->getReport_QuestionsWithoutQuestionmarks();
				$arr2 = $this->getReport_QuestionsTotal();
				$arr3 = $this->getReport_AnswersWhitoutMarks();
				$return = array_merge_recursive($arr1, $arr2, $arr3);
				return json_encode($return);
				break;

			case "test":
				/************************************
				// Transistion test
				$return = $this->setSyllabusState(107, 2);
				if ($return) {
					// execute transition codes
					foreach ($return as $script) {
						$scr = $script["transistionScript"];
						if (!is_null($scr)) {
							$fname = "functions/" . $scr;
							if (file_exists($fname)) {
								echo $fname;
								include_once($fname);
							}
						}
					}
				} else echo "invalid transition";
				return "<br/>time=".time();
				break;
				/*************************************/
				
			//-------- State machine
            default:
                return "goaway";
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
    owner AS 'Owner',
    ". // description AS 'Description',
    "c.name AS 'State'
FROM
    (sqms_syllabus AS a
    LEFT JOIN sqms_topic AS b ON a.sqms_topic_id = b.sqms_topic_id)
        INNER JOIN
    sqms_syllabus_state AS c ON a.sqms_state_id = c.sqms_syllabus_state_id;";
        $return = array();
		$res = $this->db->query($query);
        $return['syllabus'] = $this->getResultArray($res);
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
	private function copySyllabus($oldSyllabus) {
		$this->addSyllabus($oldSyllabus);
	}
	private function getSyllabusPossibleNextStates($actstate) {
		settype($actstate, 'integer');
		
		$query = "SELECT 
    a.sqms_state_id_TO,
    b.name
FROM
    sqms_syllabus_state_rules AS a
    INNER JOIN sqms_syllabus_state AS b ON a.sqms_state_id_TO = b.sqms_syllabus_state_id
WHERE
    sqms_state_id_FROM = $actstate;";
		$return = array();
		$res = $this->db->query($query);
		$return['nextstates'] = $this->getResultArray($res);
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
	private function setSyllabusName($syllabid, $newname) {
		$query = "UPDATE sqms_syllabus SET name = ? WHERE sqms_syllabus_id = ?;";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("si", $newname, $syllabid); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
	}
	private function checkTransition($from, $to) {
		settype($from, 'integer');
		settype($to, 'integer');
		
		$query = "SELECT * FROM sqms_syllabus_state_rules WHERE ".
		"sqms_state_id_FROM = $from AND sqms_state_id_TO = $to;";
		$return = array();
		$res = $this->db->query($query);
		$cnt = $res->num_rows;
        return ($cnt > 0);
	}
	private function getSyllabusState($syllabid) {
		settype($syllabid, 'integer');
		$query = "SELECT sqms_state_id FROM sqms_syllabus WHERE sqms_syllabus_id = $syllabid;";
		$res = $this->db->query($query);
		$return = array();
        $return['test'] = $this->getResultArray($res);
		return $return['test'][0]['sqms_state_id'];
	}
	private function setSyllabusState($syllabid, $stateid) {
		// params
		settype($syllabid, 'integer');
		settype($stateid, 'integer');
		// get actual state from syllabus
		$actstate = $this->getSyllabusState($syllabid);
		// check transition
		$trans = $this->checkTransition($actstate, $stateid);
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
		$return = $this->getResultArray($res);
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
	private function addQuestion($params) {
		/* TODO:
		$query = "INSERT INTO sqms_question (name) VALUES (?);";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("s", $name); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
		*/
		return null;
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
		settype($state, 'integer');
        $query = "SELECT * FROM sqms_syllabus_element"; // TODO: Replace * -> column names
		if($id!=-1){
			$query .= " WHERE sqms_syllabus_id = $id";
        }
		$query .= " ORDER BY element_order;";
        $return = array();
		$res = $this->db->query($query);
        $return['syllabuselements'] = $this->getResultArray($res);
        return $return;
    }
	private function getFormDataByState($state) {
		if (!isset($state)) $state = 1;
		settype($state, 'integer');
		$query = "SELECT * FROM sqms_syllabus_state WHERE sqms_syllabus_state_id = $state;";
		$res = $this->db->query($query);
		$return = array();
        $tmp = $this->getResultArray($res);
		$return['formdata'] = $tmp[0]['form_data'];
		return $return;
	}
	private function getTopicList($id=-1) {
        $query = "SELECT * FROM `sqms_topic`";
        if($id!=-1){
            $query .= " AND sqms_topic_id='$id'";
        }
		$res = $this->db->query($query);
        $return['topiclist'] = $this->getResultArray($res);
        return $return;
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
		$res = $this->db->query($query);
        $return['questionlist'] = $this->getResultArray($res);
        return $return;
    }
	private function getAnswers($questionID) {
		settype($questionID , 'integer');

        $query = "SELECT * FROM `sqms_answer` WHERE sqms_question_id = $questionID;";
		$res = $this->db->query($query);
        $return['answers'] = $this->getResultArray($res);
        return $return;
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