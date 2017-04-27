UPDATE `bpmspace_sqms_v6`.`sqms_syllabus_state_rules` SET `transistionScript`='func_setPredesDeprecatd.inc.php' WHERE `sqms_syllabus_state_rules_id`='17' and`sqms_state_id_FROM`='2' and`sqms_state_id_TO`='3';


UPDATE `bpmspace_sqms_v6`.`sqms_question_type` SET `name`='Calculated', `description`='' WHERE `sqms_question_type_id`='1';
UPDATE `bpmspace_sqms_v6`.`sqms_question_type` SET `name`='Calculated multi-choice', `description`='' WHERE `sqms_question_type_id`='2';
UPDATE `bpmspace_sqms_v6`.`sqms_question_type` SET `name`='Calculated simple' WHERE `sqms_question_type_id`='3';
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('5', 'Essay');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('6', 'Matching');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('7', 'Embedded Answers');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('8', 'Multiple choice');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('9', 'Short Answer');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('10', 'Numerical');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('11', 'Random short-answer matching');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('12', 'True/False');
INSERT INTO `bpmspace_sqms_v6`.`sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('4', 'Description');


CREATE TABLE `sqms_exam_version` (
  `sqms_exam_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `sqms_exam_version_name` longtext,
  `sqms_question_unique_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sqms_exam_version_id`) 
) ENGINE=InnoDB AUTO_INCREMENT=678 DEFAULT CHARSET=utf8;

CREATE TABLE `sqms_question_exam_version` (
  `sqms_question_exam_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `sqms_exam_version_id` int(11),
  `sqms_question_id` int(11),
  PRIMARY KEY (`sqms_question_exam_version_id`) 
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8;

ALTER TABLE `bpmspace_sqms_v6`.`sqms_question_exam_version` 
ADD INDEX `sqms_exam_version_id_fk_1_idx` (`sqms_exam_version_id` ASC);
ALTER TABLE `bpmspace_sqms_v6`.`sqms_question_exam_version` 
ADD CONSTRAINT `sqms_exam_version_id_fk_1`
  FOREIGN KEY (`sqms_exam_version_id`)
  REFERENCES `bpmspace_sqms_v6`.`sqms_exam_version` (`sqms_exam_version_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,

ADD CONSTRAINT `sqms_question_id_fk_2` 
  FOREIGN KEY (`sqms_question_id`) 
  REFERENCES `sqms_question` (`sqms_question_id`) 
  ON DELETE NO ACTION 
  ON UPDATE NO ACTION;

# Add triggers
DROP TRIGGER IF EXISTS `bpmspace_sqms_v6.question_after_update`;
DELIMITER //

CREATE
	TRIGGER `bpmspace_sqms_v6.question_after_update` AFTER UPDATE 
	ON `sqms_question`
	FOR EACH ROW BEGIN
	
	INSERT INTO sqms_history (sqms_users_login, timestamp, table_name, main_id, column_name, value_OLD, value_NEW) 
    VALUES (CURRENT_USER(), NOW(), "sqms_question", OLD.sqms_question_id, "sqms_question_state_id", OLD.sqms_question_state_id, NEW.sqms_question_state_id);
	
    END
    //
    DELIMITER ;
	
DROP TRIGGER IF EXISTS `bpmspace_sqms_v6.answer_after_update`;
DELIMITER //

CREATE
	TRIGGER `bpmspace_sqms_v6.answer_after_update` AFTER UPDATE 
	ON `sqms_answer`
	FOR EACH ROW BEGIN
	
	INSERT INTO sqms_history (sqms_users_login, timestamp, table_name, main_id, column_name, value_OLD, value_NEW) 
    VALUES (CURRENT_USER(), NOW(), "sqms_answer", OLD.sqms_answer_id, "sqms_question_id", OLD.sqms_question_id, NEW.sqms_question_id);
	
    END
    //
    DELIMITER ;

# Create a new table as copy of coms_participant.

DROP TABLE IF EXISTS bpmspace_coms_v1.coms_participant_history;

CREATE TABLE `coms_participant_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `coms_participant_id` int(11) NOT NULL,
  `coms_participant_lastname` varchar(60) DEFAULT NULL,
  `coms_participant_firstname` varchar(60) DEFAULT NULL,
  `coms_participant_public` tinyint(4) DEFAULT '0',
  `coms_participant_placeofbirth` varchar(60) DEFAULT NULL,
  `coms_participant_birthcountry` varchar(60) DEFAULT NULL,
  `coms_participant_dateofbirth` date DEFAULT NULL,
  `coms_participant_LIAM_id` int(11) NOT NULL,
  `coms_participant_gender` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


# Create trigger for coms participants.

DROP TRIGGER IF EXISTS `bpmspace_coms_v1.coms_participant_update`;
DELIMITER //

CREATE
	TRIGGER `bpmspace_coms_v1.coms_participant_update` AFTER UPDATE 
	ON `coms_participant`
	FOR EACH ROW BEGIN
	
	INSERT INTO coms_participant_history (coms_participant_id, coms_participant_lastname, coms_participant_firstname, coms_participant_public, coms_participant_placeofbirth, coms_participant_birthcountry, coms_participant_dateofbirth, coms_participant_LIAM_id, coms_participant_gender) 
	VALUES (OLD.coms_participant_id, OLD.coms_participant_lastname, OLD.coms_participant_firstname, OLD.coms_participant_public, OLD.coms_participant_placeofbirth, OLD.coms_participant_birthcountry, OLD.coms_participant_dateofbirth, OLD.coms_participant_LIAM_id, OLD.coms_participant_gender);

    END
    //
    DELIMITER ;

