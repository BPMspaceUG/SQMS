<?php
  /****************************
    R O L E    M A N A G E R  
  ****************************/
  class RoleManager
  {
    private $db;

    public function __construct() {
      // Get global variables here
      global $DB_host;
      global $DB_user;
      global $DB_pass;
      global $DB_name;
      // Connect to database
      // TODO: give DB object in the constructor
      $db = new mysqli($DB_host, $DB_user, $DB_pass, $DB_name);
      if($db->connect_errno){
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
      }
      $db->query("SET NAMES utf8");
      $this->db = $db;
    }
    public function getRolesByLIAMid($liamID) {
      settype($liamID, 'integer');
      $query = "SELECT a.sqms_role_id AS 'ID',
      b.role_name AS 'Rolename' FROM sqms_role_LIAMUSER AS a
      LEFT JOIN sqms_role AS b
      ON a.sqms_role_id = b.sqms_role_id
      WHERE a.sqms_LIAMUSER_id = $liamID;";
      $res = $this->db->query($query);
      return getResultArray($res);
    }
    public function getRolenameByRoleID($roleID) {
      settype($liamID, 'integer');
      $query = "SELECT role_name FROM sqms_role WHERE sqms_role_id = $roleID;";
      $res = $this->db->query($query);
      return getResultArray($res);
    }
    public function isActUserAllowed($area) {
      $result = false;
      // get roles from act user
      $roles = $this->getRolesByLIAMid($_SESSION['user_id']); 
      // has roles
      if (count($roles) > 0) {
        for ($i=0;$i<count($roles);$i++) {
          if ($this->isRoleAllowed($roles[$i]["ID"], $area))
            return true;
        }
      }
      return $result;
    }
    // Here are the rights for each role --> TODO: Maybe add this in the database
    public function isRoleAllowed($roleID, $area) {
      $result = false;
      switch ($area) {
        // Dashboard
        case "menu_dashboard":
          $result = true;
          break;
        
        // Syllabus + Question
        case "menu_syllabus":
        case "menu_question":
          if ($roleID >= 2) $result = true;
          break;
          
        // Topic + Language
        case "menu_topic":
        case "menu_language":
          if ($roleID == 1) $result = true;
          break; // only admin
          
        default:
          $result = false;
          break;
      }
      return $result;
    }
  }
?>