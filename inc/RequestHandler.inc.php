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

    private function getResultArray($query){
        /*Zweck: Ausgabe einer Debugvariante der Seite*/
        /*if(true){
            echo "<pre>";
            echo "<hr>Query:<br>";
            var_dump($query);
            echo "</pre>";
        }*/
        $results_array = array();
        $result = $this->db->query($query);
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
				$return = array_merge(
					$this->getSyllabusList(),
					$this->getTopicList()
				);
				return $return;
                break;
				
			case 'syllabuselements':
                $return = $this->getSyllabusElementsList();
				return $return;
                break;
				
			case 'create_syllabus':
				return $this->addSyllabus($route);
				break;

			case 'create_topic':
				return $this->addTopic($params["Name"]);
				break;				
				
			case 'report_questionswithoutquestionmarks':
				$return = $this->getReport_QuestionsWithoutQuestionmarks();
				return $return;
				break;
				
			case 'topics':
				return $this->getTopicList();
				break;
			
			case 'questions':
				return $this->getQuestionList();
				break;

			// ====================================================
/*				
            case 'boot':
                $return['sidebar'] = array(array("text"=>"requ-handler>handleBoot()>sidebar."));
                $return['content'] = file_get_contents('../../EduMS-client/boot.html');
                $return['topics'] = $this->handleTopic(3);
                return $return;
                break;

            case 'getTopics':                
                return $this->getTopicList();
                break;

            case 'monitor':
                return $this->handleMonitor($handle);
                break;

            case 'events':
                
                /events = Liste aller Events die freigegben sind
                /events/id = Daten des Events
                
                return $this->handleEvents($handle);
                break;

            case 'package':
                return $this->handlePackage($handle);
                break;              
*/
            default:
                return "goaway";
                exit;
                break;

        }
    }

    ###################################################################################################################
    ####################### Definition der Handles
    ###################################################################################################################

    private function getSyllabusList(){
        $query = "SELECT * FROM sqms_syllabus;"; // TODO: Replace * -> column names
        $return = array();
        $return['syllabus'] = $this->getResultArray($query);
        return $return;
    }
	private function addSyllabus($route){
		// TODO: Prepare statement
		$query = "INSERT INTO sqms_syllabus (name, sqms_state_id, version, sqms_topic_id, owner, sqms_language_id, validity_period_from, validity_period_to, description) VALUES (".
			"'Swagetti Yolonese',".
			"1,". // StateID
			"1,". // Version
			"1,". // Topic
			"'B. A. Troll',".
			"1,". // LangID
			"'2015-06-01',".
			"'2016-12-12',".
			"'<p>This is a test, HTML should be possible!</p>');";
        $result = $this->db->query($query);
		if (!$result) $this->db->error;
		return $result;
	}
	private function addTopic($name){
		// TODO: Prepare statement
		$query = "INSERT INTO sqms_topic (name) VALUES (".
			"'".$name."');";
        $result = $this->db->query($query);
		//if (!$result) $this->db->error;
		return (!is_null($result) ? 1 : null);
	}
	
    private function getSyllabusElementsList(){
        $query = "SELECT * FROM sqms_syllabus_element;"; // TODO: Replace * -> column names
        $return = array();
        $return['syllabuselements'] = $this->getResultArray($query);
        return $return;
    }
	private function getTopicList($id=-1){
        $query = "SELECT * FROM `sqms_topic`";
        if($id!=-1){
            $query .= " AND sqms_topic_id='$id'";
        }
        $return['topiclist'] = $this->getResultArray($query);
        return $return;
    }
	private function getQuestionList($id=-1){
        $query = "SELECT * FROM `sqms_question`";
        if($id!=-1){
            $query .= " AND sqms_question_id='$id'";
        }
        $return['questionlist'] = $this->getResultArray($query);
        return $return;
    }
		
	// ----------------------------------- Reports
    private function getReport_QuestionsWithoutQuestionmarks(){
        $query = "SELECT COUNT(*) AS NrOfQuestionsWOQmarks FROM sqms_question WHERE question NOT LIKE '%?%';";
        $return = array();
        $return['reports'] = $this->getResultArray($query);
        return $return;
    }

	
    public function showStartPage(){
        $return['sidebar'] = array(array("text"=>"requ-handler>showStartpage()>Startpage>sidebar."));
        $return['content'] = array(array("text"=>"showStartpage()>Startpage>Content"));
        return $return;
    }

    private function handleBoot(){

        //echo $return['topics'];
        return ;//$return;
    }

    /*Ein Topic ist eine Schulungsart. Entweder wurden keine Parameter übergeben dann soll die ganze verfügbare Liste ausgegeben werden
    oder es wurde ein Parameter angegeben dann nur dieses Topic ausgeben.*/
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