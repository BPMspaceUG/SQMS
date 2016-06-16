<?php
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
      $trans_possible = $this->checkTransition($actState, $stateID);
      if ($trans_possible) {
        // Write to DB
      }
    }
    public function checkTransition($fromID, $toID) {
      settype($fromID, 'integer');
      settype($toID, 'integer');
      $query = "SELECT * FROM ".$this->table_rules." WHERE ".$this->colname_from." = $fromID ".
        "AND ".$this->colname_to." = $toID;";
      //echo '<pre>'.$query.'</pre>';
      $res = $this->db->query($query);
      $cnt = $res->num_rows;
      return ($cnt > 0);
    }
    public function getTransitionScripts($fromID, $toID) {
      settype($fromID, 'integer');
      settype($toID, 'integer');
      $query = "SELECT transistionScript FROM ".$this->table_rules." WHERE ".
      "sqms_state_id_FROM = $fromID AND sqms_state_id_TO = $toID;";
      $return = array();
      $res = $this->db->query($query);
      $return = getResultArray($res);
      return $return;
    }
  }
?>