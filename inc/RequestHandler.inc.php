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

    private function getResultArray($result){
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

    public function __construct()
    {
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

    public function handle($command, $params){

        switch($command){

			case 'syllabus':
				$return = $this->getSyllabusList();
				return json_encode($return);
                break;
				
			case 'syllabuselements':
                $return = $this->getSyllabusElementsList();
				return json_encode($return);
                break;
				
			case 'create_syllabus':
				return $this->addSyllabus($params);
				break;
				
			case "getnextstates":
				return $this->getSyllabusPossibleNextStates(1);
				break;
				
			case "update_syllabus":
				return $this->updateSyllabus($params);
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
							
			//-------- Reports for Dashboard
				
			case 'report_questionswithoutquestionmarks':
				$return = $this->getReport_QuestionsWithoutQuestionmarks();
				return json_encode($return);
				break;

			case "test":
				// Transistion test
				$return = $this->setSyllabusState(107, 2);
				if ($return) {
					// execute transition codes
					foreach ($return as $script) {
						$scr = $script["transistionScript"];
						if (!is_null($scr)) {
							$fname = "functions/" . $scr;
							if (file_exists($fname)) {
								echo "$fname";
								include_once($fname);
							}
						}
					}
				} else echo "invalid transition";
				return "<br/>time=".time();
				break;
				
				
				
			//-------- State machine
			
			// At State
			case "getformdata":
				return $this->getFormDataByState(1);
				break;
			
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
	
    private function getSyllabusList(){
		$query = "SELECT 
    sqms_syllabus_id AS ID,
    a.name AS name,
    sqms_state_id,
    version,
    b.name AS topic,
    owner,
    validity_period_from,
    validity_period_to,
    description,
    a.sqms_topic_id,
    c.name AS state,
	sqms_syllabus_id_predecessor,
	sqms_syllabus_id_successor
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
	private function addSyllabus($route){
		// TODO: Prepare statement
		$query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, validity_period_from, validity_period_to, description) VALUES (".
			"'Testtext',".
			"1,". // StateID
			"1,". // Version
			"1,". // Topic
			"'Herr man',".
			"1,". // LangID
			"'2015-06-01',".
			"'2016-12-12',".
			"'<p>This is a test, HTML should be possible!</p>');";
        $result = $this->db->query($query);
		if (!$result) $this->db->error;
		return $result;
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
		$id = $params ["ID"];
		// update
		$this->setSyllabusState($id, $actstate);
		$this->setSyllabusName($id, $params["name"]);
		
		return time();
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
		echo "ID: ".$syllabid."<br/>";
		echo "Trans: " . $actstate." -> " . $stateid . "<br/>";
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
	
	
	
	private function addTopic($name){
		$query = "INSERT INTO sqms_topic (name) VALUES (?);";
		$stmt = $this->db->prepare($query); // prepare statement
		$stmt->bind_param("s", $name); // bind params
        $result = $stmt->execute(); // execute statement
		return (!is_null($result) ? 1 : null);
	}
	private function delTopic($id){
		// Deleten darf der user dann sowieso nicht
		// TODO: Prepare statement
		$query = "UPDATE sqms_topic SET name = 'XXXXXXX' WHERE sqms_topic_id = ".$id.";";
        $result = $this->db->query($query);
		//if (!$result) $this->db->error;
		return (!is_null($result) ? 1 : null);
	}
	private function updateTopic($params){
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
    private function getSyllabusElementsList(){
        $query = "SELECT * FROM sqms_syllabus_element;"; // TODO: Replace * -> column names
        $return = array();
		$res = $this->db->query($query);
        $return['syllabuselements'] = $this->getResultArray($res);
        return $return;
    }
	private function getFormDataByState($state) {
		settype($state, 'integer');
		$query = "SELECT form_data FROM sqms_syllabus_state WHERE sqms_syllabus_state_id = $state;";
		$res = $this->db->query($query);
		$return = array();
        $return['test'] = $this->getResultArray($res);
		//var_dump($return);
		return $return['test'][0]['form_data'];
	}
	private function getTopicList($id=-1){
        $query = "SELECT * FROM `sqms_topic`";
        if($id!=-1){
            $query .= " AND sqms_topic_id='$id'";
        }
		$res = $this->db->query($query);
        $return['topiclist'] = $this->getResultArray($res);
        return $return;
    }
	private function getQuestionList($id=-1){
        $query = "SELECT * FROM `sqms_question`";
        if($id!=-1){
            $query .= " AND sqms_question_id='$id'";
        }
		$res = $this->db->query($query);
        $return['questionlist'] = $this->getResultArray($res);
        return $return;
    }
	// ----------------------------------- Reports
    private function getReport_QuestionsWithoutQuestionmarks(){
        $query = "SELECT COUNT(*) AS NrOfQuestionsWOQmarks FROM sqms_question WHERE question NOT LIKE '%?%';";
        $return = array();
		$res = $this->db->query($query);
        $return['reports'] = $this->getResultArray($res);
        return $return;
    }
	/*
    public function showStartPage(){
        $return['sidebar'] = array(array("text"=>"requ-handler>showStartpage()>Startpage>sidebar."));
        $return['content'] = array(array("text"=>"showStartpage()>Startpage>Content"));
        return $return;
    }

    private function handleBoot(){

        //echo $return['topics'];
        return ;//$return;
    }

    private function handleTopic($handle){

        $parameters = sizeof($handle); //wie viele Parameter wurden übergeben? sizeof=count
        if($parameters==0){ //api/usr/token/topics/
            return $this->getTopicList(); //done
        }
        elseif($parameters == 1){ //api/usr/token/topics/12345
            $param = intval($handle[0]);//Zwang zu Integer
            return $this->getTopicList($param);
        }

        //@TODO
    }

    private function handleMonitor($handle){

        $parameters = sizeof($handle); //wie viele Parameter wurden übergeben? sizeof=count
        if($parameters==0){ //api/usr/token/monitor
            $out = array();
            $out['topics'] = $this->getTopicList(); //done
            return $out;
        }
        elseif($parameters == 1){ //api/usr/token/monitor/var+?
            //$param = intval($handle[0]);//Zwang zu Integer
            //return $this->getTopicList($param);
        }

        //@TODO
    }
	*/
	
    /*Ein Package ist eine frei kofigurierbare Sammlung von von Schulungen und Kursen*/
    private function handlePackage($handle){

        $parameters = sizeof($handle); //wie viele Parameter wurden übergeben?
        if($parameters==0){ //api/usr/token/package/
            $return = $this->getPackageList();
            $return['nextEvents'] = $this->getEvents();
            $return['sidebar'] = array(array("text"=>"Der Standard ISO 27000 beschäftigt sich mit der Einführung von den Mindestanforderungen an und dem Risikomanagement bei einem Informationssicherheitsmanagementsystem (ISMS) einer IT Organisation."));
            $return['footer'] = array(array("text"=>"Alle Preise verstehen sich zzgl. 19% MwSt. Auf Schulungen in englischer Sprache erheben wir eine einmaligen Aufschlag von 150€."));
            return $return;
        }
        elseif($parameters == 1){ //api/usr/token/topics/12345
            $param = intval($handle[0]);
            $return = $this->getPackageList($param);
            $return['nextEvents'] = $this->getEvents();
            $return['topnav'] = array(array("text"=>"Anmeldung","path"=>"signup/"),array("text"=>"Standorte","path"=>"locations/"),array("text"=>"Themen","path"=>"topics/"));
            $return['sidebar'] = array(array("text"=>"Der Standard ISO 27000 beschäftigt sich mit der Einführung von den Mindestanforderungen an und dem Risikomanagement bei einem Informationssicherheitsmanagementsystem (ISMS) einer IT Organisation."));
            return $return;
        }

        //@TODO
    }



    ###################################################################################################################
    ####################### Definition der Helper-Funktionen
    ############################################## Topic
    ###################################################################################################################
    /**
     * Gibt eine Liste aller Pakete aus, ist ein Thema festgelegt, so werden nur die passenden Pakete angezeigt
     * @param int $id ID des Topics das angezeigt werden soll
     * @return mixed
     */
	 /*
    private function getPackageList($id=-1){
        $query = "SELECT * FROM `package` WHERE TRUE";
        if($id!=-1){
            $query .= " AND topic_id='$id'";
        }
        $return['packagelist'] = $this->getResultArray($query);
        return $return;
    }*/

    /*Zweck: Rückgabe eines oder aller Topics aus der Datenbank*/

    public function getNextEvents(){
        return $this->getEvents();
    }

    /*Ein Event ist eine Schulung zu einen bestimmten Zeitpunkt und an einem bestimmten Ort*/
    private function getEvents($id=-1,$test=0){
        $sql = "
                SELECT * FROM `apieventdata`
                WHERE test = '$test' ";
        if($id!=-1){
            $sql .=  "AND course_id = $id";
        }
        $sql .= "ORDER BY start_date
                Limit 0,5";

        return $this->getResultArray($sql);
    }


    /*Eine CourseList ist die Liste aller möglichen Teilbereiche von Schulungen*/
    private function getCourseList(){
        $return = array();
        $sql = "SELECT * FROM `course` WHERE deprecated = 0";
        $return['topiclist'] = $this->getResultArray($query);
        $return['nextEvents'] = $this->getAllEvents();
        return $return;
    }

    /*Jeder Kurs ist einem Topic (einer Schulung) zugeordnet. Jeder Kurs hat seine eigene ID*/
    private function getCoursecById($id){
        $return = array();
        $query = "SELECT * FROM `course`
                WHERE
                    deprecated = 0 AND
                    course_id = $id
                    ";
        $return['topic'] = $this->getResultArray($query);
        $return['nextEvents'] = $this->getEventsByTopic($id);
        return $return;
    }

    /**
     * Gibt alle Zukünftigen Veranstaltungen eines Themas, welche nicht inhouse sind, aus.
     * @param $id Thema
     * @return mixed
     */
    private function getEventsByTopic($id){
        $query = "
                SELECT * FROM `apieventdata`
                WHERE test = 0 AND
                  course_id = $id
                ORDER BY start_date
                Limit 0,5";
        return $this->getResultArray($query);
    }

    /**
     * Gibt alle Zukünftigen Veranstaltungen eines Themas, welche nicht inhouse sind, aus.
     * @param $id Thema
     * @return mixed
     */
    private function getAllEvents(){
        global $db;
        $result = $db->query("SELECT * FROM `brand_location` WHERE brand_id = ".$this->userid);
        $query = "SELECT * FROM `apieventdata`";
        if($result->num_rows>0){
            $query .= " WHERE location_id IN (SELECT location_id FROM `brand_location` WHERE brand_id = ".$this->userid.")";
        }
        $query .= "
                    ORDER BY start_date
                    LIMIT 0,5";
        return $this->getResultArray($query);
    }



    ###################################################################################################################
    ####################### Definition der Helper-Funktionen
    ############################################## Location
    ###################################################################################################################



    /**
     * Verarbeitet den Location-Handle
     * Wenn keine Location angegeben wird wird die Liste aller Locations angegeben
     * @param $handle
     * @return array
     */
    private function handleLocations($handle){
        global $db;
        if(sizeof($handle)==0){
            return $this->getLocationList();
        }
        else{
            return $this->getLocationInformation($handle);
        }
    }

    private function getLocationList(){
        global $db;
        $result = $db->query("SELECT * FROM `brand_location` WHERE brand_id = ".$this->userid);
        $query = "SELECT distinct location_id, location_name, location_description FROM `apieventdata` WHERE location_description<>'' ";
        if($result->num_rows>0){
            $query .= " AND location_id IN (SELECT location_id FROM `brand_location` WHERE brand_id = ".$this->userid.")";
        }
        /* //!ÄNDERN! weil location_id nicht mehr in brand_topic vorhanden 
        $result = $db->query("SELECT * FROM `brand_topic_limit` WHERE brand_id = ".$this->userid);
        if($result->num_rows>0){
            $query .= " AND topic_id_id IN (SELECT location_id FROM `brand_topic` WHERE brand_id = ".$this->userid.")";
        }*/

        $return = array();
        $return['locations'] = $this->getResultArray($query);
        $return['nextEvents'] = $this->getAllEvents();
        return $return;
    }

    private function getLocationInformation($handle){
        //anhand des handles Location suchen
        if(sizeof($handle==1)){
            $return = array();
            $query ="SELECT distinct location_id, location_name, location_description FROM `apieventdata` WHERE location_id = '".intval($handle[0])."'";
            $return['locations'] = $this->getResultArray($query);
            $return['nextEvents'] = $this->getEventsByLocationId(intval($handle[0]));
            return $return;
        }
        return array("location"=>"Munich","description"=>"München ist die schönste Stadt der Welt");
    }

    private function getEventsByLocationId($id){
        $query = "SELECT * FROM `apieventdata` WHERE location_id = '$id' LIMIT 0,5";
        return $this->getResultArray($query);
    }

    private function handleEvents($handle){
        if(sizeof($handle)==0){
            return $this->getEventList();
        }
        else{
            if($handle[0]=='location'){
                return $this->getEventList($handle[1]);

            }
            elseif($handle[0]=='limit'){

            }
            return $this->getEventList();
        }
    }

    private function getEventList(){
        $query = "SELECT * FROM `event` WHERE start_date > NOW() AND inhouse = 0";
        return $this->getResultArray($query);;
    }

}