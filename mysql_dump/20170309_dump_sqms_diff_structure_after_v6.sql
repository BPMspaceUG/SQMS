SET GLOBAL log_bin_trust_function_creators=1;
DROP FUNCTION IF EXISTS fnStripTags;
DELIMITER |
CREATE FUNCTION fnStripTags( Dirty varchar(4000) )
RETURNS varchar(4000)
DETERMINISTIC 
BEGIN
  DECLARE iStart, iEnd, iLength int;
    WHILE Locate( '<', Dirty ) > 0 And Locate( '>', Dirty, Locate( '<', Dirty )) > 0 DO
      BEGIN
        SET iStart = Locate( '<', Dirty ), iEnd = Locate( '>', Dirty, Locate('<', Dirty ));
        SET iLength = ( iEnd - iStart) + 1;
        IF iLength > 0 THEN
          BEGIN
            SET Dirty = Insert( Dirty, iStart, iLength, '');
          END;
        END IF;
      END;
    END WHILE;
    RETURN Dirty;
END;
|
DELIMITER ;

DROP VIEW IF EXISTS v_sqms_question_combinator; 
create view v_sqms_question_combinator as
  select 
  
    concat("[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]",sqms_topic.name,"-",sqms_language.language,"-",substring(sqms_question.question, 1, 20))  as name_text_preview,
  
  sqms_question.sqms_question_id as sqms_question_id,
  concat("<p class=\"sqms_question_text\">",fnStripTags(sqms_question.question),"</p>","<p class=\"sqms_question_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]</p>") as question,
    
    (sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct) as maxpoint,
    
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_1.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_1, 
  sqms_answer_1.correct as correct_id_1,
    TRUNCATE((sqms_answer_1.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct))*100, 5) as fraction_id_1, 
    
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_2.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_2, 
  sqms_answer_2.correct as correct_id_2,
    TRUNCATE((sqms_answer_2.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct))*100, 5) as fraction_id_2,
  
    concat("<p class=\"sqms_answer_text\">",fnStripTags(sqms_answer_3.answer),"</p><p class=\"sqms_answer_id\">[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]</p>") as sqms_answer_3, 
  sqms_answer_3.correct as correct_id_3,
    TRUNCATE((sqms_answer_3.correct/(sqms_answer_1.correct + sqms_answer_2.correct + sqms_answer_3.correct))*100, 5) as fraction_id_3,
    
    sqms_topic.sqms_topic_id,
    sqms_language.language,

    
    concat("[",LPAD(sqms_answer_1.sqms_question_id,4,"0"),"-",LPAD(sqms_answer_1.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_2.sqms_answer_id,4,"0"),"-",LPAD(sqms_answer_3.sqms_answer_id,4,"0"),"]") as unique_id
  
  from              sqms_answer sqms_answer_1
         inner join sqms_answer sqms_answer_2
         inner join sqms_answer sqms_answer_3
         inner join sqms_question
         inner join sqms_language
         inner join sqms_topic

         
         on         sqms_answer_1.sqms_question_id = sqms_answer_2.sqms_question_id
                and sqms_answer_2.sqms_question_id = sqms_answer_3.sqms_question_id
                and NOT sqms_answer_2.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and NOT sqms_answer_3.sqms_answer_id = sqms_answer_1.sqms_answer_id
                and sqms_answer_3.sqms_answer_id > sqms_answer_2.sqms_answer_id
                and sqms_answer_2.sqms_answer_id > sqms_answer_1.sqms_answer_id
        and sqms_question.sqms_question_id = sqms_answer_1.sqms_question_id
                and sqms_question.sqms_language_id = sqms_language.sqms_language_id
                and sqms_topic.sqms_topic_id = sqms_question.sqms_topic_id

                
  where  sqms_answer_1.correct=1;

  
DROP VIEW IF EXISTS v_sqms_syllabus_syllabuselement_question; 
create view v_sqms_syllabus_syllabuselement_question as

  SELECT 
  SYLLA.sqms_syllabus_id,
    SYLLA.name AS S_name,
    SYLLA.description AS S_description,
    SYLEL.sqms_syllabus_element_id ,
    SYLEL.element_order ,
  SYLEL.name AS SE_name,
  SYLEL.description AS SE_description,
  SYLEL.severity,
    (round((SYLEL.severity*30/100)*2)/2) as question_count,
  (round((SYLEL.severity*30/100*0.8)*2)/2) as question_count_min,
    (round((SYLEL.severity*30/100*1.2)*2)/2) as question_count_max,

    QUEST.sqms_question_id,
    QUEST.question
    
  FROM 
  sqms_syllabus_element as SYLEL
   
  LEFT JOIN sqms_syllabus as SYLLA ON SYLLA.sqms_syllabus_id = SYLEL.sqms_syllabus_id 
  LEFT JOIN sqms_syllabus_element_question as SYLEL_Q ON SYLEL.sqms_syllabus_element_id = SYLEL_Q.sqms_syllabus_element_id
  LEFT JOIN sqms_question as QUEST ON SYLEL_Q.sqms_question_id = QUEST.sqms_question_id
  
  ORDER BY SYLLA.sqms_syllabus_id, SYLEL.element_order, QUEST.sqms_question_id  ASC;

  DROP TABLE IF EXISTS `sqms_history`;

CREATE TABLE `sqms_history` (
  `sqms_history_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_users_login` varchar(32) NOT NULL,
  `timestamp` datetime NOT NULL,
  `table_name` varchar(45) NOT NULL,
  `main_id` bigint(20) DEFAULT NULL,
  `column_name` varchar(45) NOT NULL,
  `value_OLD` varchar(1024) DEFAULT NULL,
  `value_NEW` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`sqms_history_id`,`sqms_users_login`),
  KEY `fk_sqms_history_sqms_users1_idx` (`sqms_users_login`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


# Create table for different question sets.

CREATE TABLE `sqms_exam_version` (
  `sqms_exam_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `sqms_exam_version_name` longtext,
  PRIMARY KEY (`sqms_exam_version_id`)
) ENGINE=InnoDB AUTO_INCREMENT=678 DEFAULT CHARSET=utf8;

# Create table with questions and answers and their coresponding set in sqms_exam_version


CREATE TABLE `sqms_question_answer_exam_version` (
  `sqms_question_answer_exam_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `sqms_exam_version_id` int(11) NOT NULL,
  `sqms_question_id` bigint(20) NOT NULL,
  `sqms_answer_id_1` bigint(20) DEFAULT NULL,
  `sqms_answer_id_2` bigint(20) DEFAULT NULL,
  `sqms_answer_id_3` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`sqms_question_answer_exam_version_id`),
  KEY `sqms_exam_version_id_fk_1_idx` (`sqms_exam_version_id`),
  KEY `test_idx` (`sqms_question_id`),
  KEY `sqms_answer_id_1_fk_3_idx` (`sqms_answer_id_1`),
  KEY `sqms_answer_id_2_fk_4_idx` (`sqms_answer_id_2`),
  KEY `sqms_answer_id_3_fk_5_idx` (`sqms_answer_id_3`),
  CONSTRAINT `sqms_exam_version_id_fk_01` FOREIGN KEY (`sqms_exam_version_id`) REFERENCES `sqms_exam_version` (`sqms_exam_version_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_answer_id_1_fk_3` FOREIGN KEY (`sqms_answer_id_1`) REFERENCES `sqms_answer` (`sqms_answer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_answer_id_2_fk_4` FOREIGN KEY (`sqms_answer_id_2`) REFERENCES `sqms_answer` (`sqms_answer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_answer_id_3_fk_5` FOREIGN KEY (`sqms_answer_id_3`) REFERENCES `sqms_answer` (`sqms_answer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_id_fk_02` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`sqms_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


# Add triggers
DROP TRIGGER IF EXISTS `question_after_update`;
DELIMITER //

CREATE
  TRIGGER `question_after_update` AFTER UPDATE 
  ON `sqms_question`
  FOR EACH ROW BEGIN
  
  INSERT INTO sqms_history (sqms_users_login, timestamp, table_name, main_id, column_name, value_OLD, value_NEW) 
    VALUES (CURRENT_USER(), NOW(), "sqms_question", OLD.sqms_question_id, "sqms_question_state_id", OLD.sqms_question_state_id, NEW.sqms_question_state_id);
  
    END
    //
    DELIMITER ;
  
DROP TRIGGER IF EXISTS `answer_after_update`;
DELIMITER //

CREATE
  TRIGGER `answer_after_update` AFTER UPDATE 
  ON `sqms_answer`
  FOR EACH ROW BEGIN
  
  INSERT INTO sqms_history (sqms_users_login, timestamp, table_name, main_id, column_name, value_OLD, value_NEW) 
    VALUES (CURRENT_USER(), NOW(), "sqms_answer", OLD.sqms_answer_id, "sqms_question_id", OLD.sqms_question_id, NEW.sqms_question_id);
  
    END
    //
    DELIMITER ;

Create or replace view v_sqms_short_xml_export_moodle as
Select concat('[',lpad(d.sqms_question_id,4,'0'),'-',
lpad(d.sqms_answer_id_1,4,'0'),'-',
lpad(d.sqms_answer_id_2,4,'0'),'-',
lpad(d.sqms_answer_id_3,4,'0'),']',
f.name,'-',g.language,'-',substr(e.question ,1,20)) AS `name_text_preview`,
e.question, 
a.answer as answer_1, CASE 
            WHEN a.correct 
               THEN 100.00000
               ELSE 0 
       END as fraction_id_1, 
b.answer as answer_2, CASE 
            WHEN b.correct 
               THEN 100.00000
               ELSE 0 
       END as fraction_id_2, 
c.answer as answer_3, CASE 
            WHEN c.correct 
               THEN 100.00000
               ELSE 0 
       END as fraction_id_3
FROM sqms_question_answer_exam_version d natural join sqms_topic f natural join sqms_language g natural join sqms_question e join sqms_answer a on a.sqms_answer_id = d.sqms_answer_id_1 join sqms_answer b on b.sqms_answer_id = sqms_answer_id_2 join sqms_answer c on c.sqms_answer_id = sqms_answer_id_3;

#Workaround for Dropping table with constraints. Leave out in v7 minimum structure

SET FOREIGN_KEY_CHECKS=0; 
DROP TABLE IF EXISTS `sqms_syllabus`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `sqms_syllabus` (
  `sqms_syllabus_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `sqms_state_id` bigint(20) NOT NULL,
  `version` bigint(20) NOT NULL,
  `sqms_topic_id` bigint(20) NOT NULL,
  `owner` varchar(32) NOT NULL,
  `sqms_language_id` bigint(20) NOT NULL,
  `sqms_syllabus_id_predecessor` bigint(20) DEFAULT NULL,
  `sqms_syllabus_id_successor` bigint(20) DEFAULT NULL,
  `validity_period_from` date DEFAULT NULL,
  `validity_period_to` date DEFAULT NULL,
  `description` mediumtext,
  `sqms_syllabus_time` int(11) DEFAULT NULL,
  `sqms_syllabus_question_nr` int(11) DEFAULT NULL,
  PRIMARY KEY (`sqms_syllabus_id`),
  KEY `fk_sqms_syllabus_sqms_language1_idx` (`sqms_language_id`),
  KEY `fk_sqms_syllabus_sqms_state1_idx` (`sqms_state_id`),
  CONSTRAINT `fk_sqms_syllabus_sqms_language1` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`sqms_language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_sqms_state1` FOREIGN KEY (`sqms_state_id`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

CREATE OR REPLACE

VIEW `v_sqms_syllabus_syllabuselement` AS

    SELECT

        `SYLLA`.`sqms_syllabus_id` AS `sqms_syllabus_id`,

        `STATE`.`name` AS `State`,

        `SYLLA`.`name` AS `S_name`,

        `TOPIC`.`name` AS `Topic`,

        `SYLLA`.`description` AS `S_description`,

        `SYLLA`.`owner` AS `S_owner`,

        `SYLLA`.`validity_period_from` AS `validity_period_from`,

        `SYLLA`.`validity_period_to` AS `validity_period_to`,

        `SYLLA`.`version` AS `S_version`,

        `LANG`.`language` AS `language`,
		
		CONCAT('State: ',

                `STATE`.`name`,

                ' - ID: ',

                `SYLLA`.`sqms_syllabus_id`,

                ' - Topic: ',

                `TOPIC`.`name`,

                ' - Version: ',

                `SYLLA`.`version`,

                ' - language: ',

                `LANG`.`language`,

                ' - valid from ',

                `SYLLA`.`validity_period_from`,

                ' valid until ',

                `SYLLA`.`validity_period_to`) AS `Syllabus_Info`,

        `SYLLA`.`sqms_syllabus_question_nr` AS `sqms_syllabus_question_nr`,

        `SYLLA`.`sqms_syllabus_time` AS `sqms_syllabus_time`,
		
		`SYLEL`.`sqms_syllabus_element_id` AS `syllabus_element_id`,

        `SYLEL`.`element_order` AS `element_order`,

        `SYLEL`.`name` AS `SE_name`,

        `SYLEL`.`description` AS `SE_description`,

        `SYLEL`.`severity` AS `severity`,
        
        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_time`) / 100) * 2), 0) / 2) AS `time`,
        
        (ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 0.8) * 2), 0) / 2) AS `question_count_min`,
        
        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 2), 0) / 2) AS `question_count`,
	
		(ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 1.2) * 2), 0) / 2) AS `question_count_max`,
        
       	CONCAT('Minutes: ',	TRUNCATE((ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_time`) / 100) * 2), 0) / 2),0) ,

                ' - min/avg/max Question Number: ',
                
                TRUNCATE(ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 0.8) * 2), 0) / 2,1),

				'/',
                
                TRUNCATE((ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 2), 0) / 2),1),

				'/',
                
                TRUNCATE((ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 1.2) * 2), 0) / 2),1)
                
                ) 

				AS `SyllabusElement_Info`

    FROM

        ((((`sqms_syllabus_element` `SYLEL`

        JOIN `sqms_syllabus` `SYLLA`)

        JOIN `sqms_language` `LANG`)

        JOIN `sqms_syllabus_state` `STATE`)

        JOIN `sqms_topic` `TOPIC`)

    WHERE

        ((`SYLLA`.`sqms_syllabus_id` = `SYLEL`.`sqms_syllabus_id`)

            AND (`LANG`.`sqms_language_id` = `SYLLA`.`sqms_language_id`)

            AND (`STATE`.`sqms_syllabus_state_id` = `SYLLA`.`sqms_state_id`)

            AND (`TOPIC`.`sqms_topic_id` = `SYLLA`.`sqms_topic_id`));


			
CREATE OR REPLACE

    VIEW `v_sqms_syllabus_syllabuselement_question` AS

    SELECT

        `V_SYLLA`.*,

        `QUEST`.`sqms_question_id` AS `sqms_question_id`,

        `QUEST`.`question` AS `question`

    FROM

        (`v_sqms_syllabus_syllabuselement` `V_SYLLA` JOIN `sqms_syllabus_element_question` `SE_QUEST`) JOIN  `sqms_question` `QUEST`
	
	WHERE 
	
		(`V_SYLLA`.`syllabus_element_id` = `SE_QUEST`.sqms_syllabus_element_id) AND
		(`SE_QUEST`.`sqms_question_id` = `QUEST`.`sqms_question_id`);

		
-- Set up global group concat length for moodle export

SET GLOBAL group_concat_max_len=100000;

-- Export every question in one Cell.

CREATE OR REPLACE
VIEW `v_sqms_short_xml_export_moodle2` AS
    SELECT 
    
    CONCAT('<question type="multichoice">
     <name>
       <text>', CONCAT('[',
                LPAD(`d`.`sqms_question_id`, 4, '0'),
                '-',
                LPAD(`d`.`sqms_answer_id_1`, 4, '0'),
                '-',
                LPAD(`d`.`sqms_answer_id_2`, 4, '0'),
                '-',
                LPAD(`d`.`sqms_answer_id_3`, 4, '0'),
                ']',
                `f`.`name`,
                '-',
                `g`.`language`,
                '-',
                SUBSTR(`e`.`question`, 1, 20)), '</text>
     </name>
     <questiontext format="html">
       <text>', `e`.`question` , '</text>
     </questiontext>
      <answer fraction="', (CASE
            WHEN `a`.`correct` THEN 100.00000
            ELSE 0
        END), '">
        <text>', `a`.`answer`, '</text>
      </answer>
      <answer fraction="', (CASE
            WHEN `b`.`correct` THEN 100.00000
            ELSE 0
        END), '">
        <text>', `b`.`answer`, '</text>
      </answer>
      <answer fraction="', (CASE
            WHEN `c`.`correct` THEN 100.00000
            ELSE 0
        END), '">
        <text>', `c`.`answer`, '</text>
      </answer>
      <shuffleanswers>1</shuffleanswers>
      <single>false</single>
      <answernumbering>123</answernumbering>
  </question>') AS `moodle`
    
    FROM
        ((((((`sqms_question_answer_exam_version` `d`
        JOIN `sqms_topic` `f`)
        JOIN `sqms_language` `g`)
        JOIN `sqms_question` `e` ON (((`d`.`sqms_question_id` = `e`.`sqms_question_id`)
            AND (`f`.`sqms_topic_id` = `e`.`sqms_topic_id`)
            AND (`g`.`sqms_language_id` = `e`.`sqms_language_id`))))
        JOIN `sqms_answer` `a` ON ((`a`.`sqms_answer_id` = `d`.`sqms_answer_id_1`)))
        JOIN `sqms_answer` `b` ON ((`b`.`sqms_answer_id` = `d`.`sqms_answer_id_2`)))
        JOIN `sqms_answer` `c` ON ((`c`.`sqms_answer_id` = `d`.`sqms_answer_id_3`)));

-- Alter the where statement where the number represents the sqms_exam_version_id of the set that should be exported. if you add this line you have to erase the semicolon the line above.
--    WHERE
--        (`d`.`sqms_exam_version_id` = 2);

-- Join all questions in one cell for convenient export to moodle.

CREATE OR REPLACE 
VIEW `v_sqms_moodle_export_full` AS
    SELECT 
        GROUP_CONCAT(DISTINCT `v_sqms_short_xml_export_moodle2`.`moodle`
            ORDER BY `v_sqms_short_xml_export_moodle2`.`moodle` ASC
            SEPARATOR ' ') AS `quiz`
    FROM
        `v_sqms_short_xml_export_moodle2`;
		
---------------------------

ALTER TABLE `bpmspace_sqms_v6`.`sqms_question_answer_exam_version` 
ADD COLUMN `sqms_answer_id_4` BIGINT(20) NULL DEFAULT NULL AFTER `sqms_answer_id_3`,
ADD COLUMN `sqms_answer_id_5` BIGINT(20) NULL DEFAULT NULL AFTER `sqms_answer_id_4`,
ADD COLUMN `sqms_answer_id_6` BIGINT(20) NULL DEFAULT NULL AFTER `sqms_answer_id_5`;


CREATE VIEW `v_sqms_short_xml_export_moodle2_4` AS
    SELECT 
        CONCAT('<question type="multichoiceset">
                                				<name>
                                				<text>',
                CONCAT('[',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_question_id`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_1`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_2`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_3`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_4`,
                                4,
                                '0'),
                        ']',
                        `sqms_topic`.`name`,
                        '-',
                        `sqms_language`.`language`,
                        '-',
                        SUBSTR(`sqms_question`.`question`,
                            1,
                            20)),
                '</text>
				</name>
				<questiontext format="html">
				<text>',
                `sqms_question`.`question`,
                '</text>
				</questiontext>
				<answer fraction="',
                (CASE
                    WHEN `answer_a`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_a`.`answer`,
                '</text>
				</answer>
				<answer fraction="',
                (CASE
                    WHEN `answer_b`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_b`.`answer`,
                '</text>
				</answer>
				<answer fraction="',
                (CASE
                    WHEN `answer_c`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_c`.`answer`,
                '</text>
				</answer>
				<answer fraction="',
                (CASE
                    WHEN `answer_d`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_d`.`answer`,
                '</text>
				</answer>
				<shuffleanswers>1</shuffleanswers>
				<single>false</single>
				<answernumbering>123</answernumbering>
				</question>') AS `moodle`
    FROM
        (((((((`sqms_question_answer_exam_version`
        JOIN `sqms_topic`)
        JOIN `sqms_language`)
        JOIN `sqms_question` ON (((`sqms_question_answer_exam_version`.`sqms_question_id` = `sqms_question`.`sqms_question_id`)
            AND (`sqms_topic`.`sqms_topic_id` = `sqms_question`.`sqms_topic_id`)
            AND (`sqms_language`.`sqms_language_id` = `sqms_question`.`sqms_language_id`))))
        JOIN `sqms_answer` `answer_a` ON ((`answer_a`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_1`)))
        JOIN `sqms_answer` `answer_b` ON ((`answer_b`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_2`)))
        JOIN `sqms_answer` `answer_c` ON ((`answer_c`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_3`)))
        JOIN `sqms_answer` `answer_d` ON ((`answer_d`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_4`)))
    WHERE
        (`sqms_question_answer_exam_version`.`sqms_exam_version_id` = 54689);

		---------------------

CREATE VIEW `v_sqms_short_xml_export_moodle2_3` AS
    SELECT 
        CONCAT('<question type="multichoiceset">
			<name>
			<text>',
                CONCAT('[',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_question_id`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_1`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_2`,
                                4,
                                '0'),
                        '-',
                        LPAD(`sqms_question_answer_exam_version`.`sqms_answer_id_3`,
                                4,
                                '0'),
                        ']',
                        `sqms_topic`.`name`,
                        '-',
                        `sqms_language`.`language`,
                        '-',
                        SUBSTR(`sqms_question`.`question`,
                            1,
                            20)),
                '</text>
				</name>
				<questiontext format="html">
				<text>',
                `sqms_question`.`question`,
                '</text>
				</questiontext>
				<answer fraction="',
                (CASE
                    WHEN `answer_a`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_a`.`answer`,
                '</text>
				</answer>
				<answer fraction="',
                (CASE
                    WHEN `answer_b`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_b`.`answer`,
                '</text>
				</answer>
				<answer fraction="',
                (CASE
                    WHEN `answer_c`.`correct` THEN 100.00000
                    ELSE 0
                END),
                '">
				<text>',
                `answer_c`.`answer`,
                '</text>
				</answer>
				<shuffleanswers>1</shuffleanswers>
				<single>false</single>
				<answernumbering>123</answernumbering>
				</question>
				') AS `moodle`
    FROM
        ((((((`sqms_question_answer_exam_version`
        JOIN `sqms_topic`)
        JOIN `sqms_language`)
        JOIN `sqms_question` ON (((`sqms_question_answer_exam_version`.`sqms_question_id` = `sqms_question`.`sqms_question_id`)
            AND (`sqms_topic`.`sqms_topic_id` = `sqms_question`.`sqms_topic_id`)
            AND (`sqms_language`.`sqms_language_id` = `sqms_question`.`sqms_language_id`))))
        JOIN `sqms_answer` `answer_a` ON ((`answer_a`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_1`)))
        JOIN `sqms_answer` `answer_b` ON ((`answer_b`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_2`)))
        JOIN `sqms_answer` `answer_c` ON ((`answer_c`.`sqms_answer_id` = `sqms_question_answer_exam_version`.`sqms_answer_id_3`)))
    WHERE
        (`sqms_question_answer_exam_version`.`sqms_exam_version_id` = 54683);
		

		---------------------

CREATE VIEW `v_sqms_question_combinator_4` AS
    SELECT 
        CONCAT('[',
                LPAD(`sqms_answer_1`.`sqms_question_id`,
                        4,
                        '0'),
                '-',
                LPAD(`sqms_answer_1`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_2`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_3`.`sqms_answer_id`, 4, '0'),
                '-',
				LPAD(`sqms_answer_4`.`sqms_answer_id`, 4, '0'),
                ']',
                `sqms_topic`.`name`,
                '-',
                `sqms_language`.`language`,
                '-',
                SUBSTR(`sqms_question`.`question`,
                    1,
                    20)) AS `name_text_preview`,
        `sqms_question`.`sqms_question_id` AS `sqms_question_id`,
        CONCAT('<p class="sqms_question_text">',
                FNSTRIPTAGS(`sqms_question`.`question`),
                '</p>',
                '<p class="sqms_question_id">[',
                LPAD(`sqms_answer_1`.`sqms_question_id`,
                        4,
                        '0'),
                '-',
                LPAD(`sqms_answer_1`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_2`.`sqms_answer_id`, 4, '0'),
                '-',
				LPAD(`sqms_answer_3`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_4`.`sqms_answer_id`, 4, '0'),
                ']</p>') AS `question`,
        (((`sqms_answer_1`.`correct` + `sqms_answer_2`.`correct`) + `sqms_answer_3`.`correct`)+ `sqms_answer_4`.`correct`) AS `maxpoint`,
        CONCAT('<p class="sqms_answer_text">',
                FNSTRIPTAGS(`sqms_answer_1`.`answer`),
                '</p><p class="sqms_answer_id"></p>') AS `sqms_answer_1`,
        `sqms_answer_1`.`correct` AS `correct_id_1`,
		
        TRUNCATE((`sqms_answer_1`.`correct` / (((`sqms_answer_1`.`correct` + `sqms_answer_2`.`correct`) + `sqms_answer_3`.`correct`)+ `sqms_answer_4`.`correct`) * 100),
            5) AS `fraction_id_1`,
        CONCAT('<p class="sqms_answer_text">',
                FNSTRIPTAGS(`sqms_answer_2`.`answer`),
                '</p><p class="sqms_answer_id"></p>') AS `sqms_answer_2`,
        `sqms_answer_2`.`correct` AS `correct_id_2`,
        TRUNCATE((`sqms_answer_2`.`correct` / (((`sqms_answer_1`.`correct` + `sqms_answer_2`.`correct`) + `sqms_answer_3`.`correct`)+ `sqms_answer_4`.`correct`) * 100),
            5) AS `fraction_id_2`,
        CONCAT('<p class="sqms_answer_text">',
                FNSTRIPTAGS(`sqms_answer_3`.`answer`),
                '</p><p class="sqms_answer_id"></p>') AS `sqms_answer_3`,
        `sqms_answer_3`.`correct` AS `correct_id_3`,
        TRUNCATE((`sqms_answer_3`.`correct` / (((`sqms_answer_1`.`correct` + `sqms_answer_2`.`correct`) + `sqms_answer_3`.`correct`)+ `sqms_answer_4`.`correct`) * 100),
            5) AS `fraction_id_3`,
			
		CONCAT('<p class="sqms_answer_text">',
                FNSTRIPTAGS(`sqms_answer_4`.`answer`),
                '</p><p class="sqms_answer_id"></p>') AS `sqms_answer_4`,
        `sqms_answer_4`.`correct` AS `correct_id_4`,
        TRUNCATE((`sqms_answer_4`.`correct` / (((`sqms_answer_1`.`correct` + `sqms_answer_2`.`correct`) + `sqms_answer_3`.`correct`)+ `sqms_answer_4`.`correct`) * 100),
            5) AS `fraction_id_4`,	
			
        `sqms_topic`.`sqms_topic_id` AS `sqms_topic_id`,
        `sqms_language`.`language` AS `language`,
        CONCAT('[',
                LPAD(`sqms_answer_1`.`sqms_question_id`,
                        4,
                        '0'),
                '-',
                LPAD(`sqms_answer_1`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_2`.`sqms_answer_id`, 4, '0'),
                '-',
				LPAD(`sqms_answer_3`.`sqms_answer_id`, 4, '0'),
                '-',
                LPAD(`sqms_answer_4`.`sqms_answer_id`, 4, '0'),
                ']') AS `unique_id`
    FROM
        ((((((`sqms_answer` `sqms_answer_1`
        JOIN `sqms_answer` `sqms_answer_2`)
        JOIN `sqms_answer` `sqms_answer_3`)
		JOIN `sqms_answer` `sqms_answer_4`)
        JOIN `sqms_question`)
        JOIN `sqms_language`)
        JOIN `sqms_topic` ON (((`sqms_answer_1`.`sqms_question_id` = `sqms_answer_2`.`sqms_question_id`)
            AND (`sqms_answer_2`.`sqms_question_id` = `sqms_answer_3`.`sqms_question_id`)
            AND (`sqms_answer_3`.`sqms_question_id` = `sqms_answer_4`.`sqms_question_id`)
            AND (`sqms_answer_2`.`sqms_answer_id` <> `sqms_answer_1`.`sqms_answer_id`)
            AND (`sqms_answer_3`.`sqms_answer_id` <> `sqms_answer_1`.`sqms_answer_id`)
            AND (`sqms_answer_4`.`sqms_answer_id` <> `sqms_answer_1`.`sqms_answer_id`)
            AND (`sqms_answer_4`.`sqms_answer_id` > `sqms_answer_3`.`sqms_answer_id`)
            AND (`sqms_answer_3`.`sqms_answer_id` > `sqms_answer_2`.`sqms_answer_id`)
            AND (`sqms_answer_2`.`sqms_answer_id` > `sqms_answer_1`.`sqms_answer_id`)
            AND (`sqms_question`.`sqms_question_id` = `sqms_answer_1`.`sqms_question_id`)
            AND (`sqms_question`.`sqms_language_id` = `sqms_language`.`sqms_language_id`)
            AND (`sqms_topic`.`sqms_topic_id` = `sqms_question`.`sqms_topic_id`))));

-----------------

CREATE VIEW `v_sqms_moodle_export_full2_4` AS
    SELECT 
        GROUP_CONCAT(DISTINCT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`v_sqms_short_xml_export_moodle2_4`.`moodle`,
                                                                                                                                                '<p><span>',
                                                                                                                                                'lt;p&gt;&lt;span'),
                                                                                                                                            '</p></span>',
                                                                                                                                            '&lt;/span&gt;&lt;/p&gt;'),
                                                                                                                                        '</p>',
                                                                                                                                        '&lt;/p&gt;'),
                                                                                                                                    '<p>',
                                                                                                                                    '&lt;p&gt;'),
                                                                                                                                '<span>',
                                                                                                                                '&lt;span&gt;'),
                                                                                                                            '</span>',
                                                                                                                            '&lt;/span&gt;'),
                                                                                                                        '<span class="st"',
                                                                                                                        '&lt;span; classe="st"'),
                                                                                                                    '&uuml;',
                                                                                                                    '&amp;uuml;'),
                                                                                                                '&auml;',
                                                                                                                '&amp;auml;'),
                                                                                                            '&ouml;',
                                                                                                            '&amp;ouml;'),
                                                                                                        '&szlig;',
                                                                                                        '&amp;szlig;'),
                                                                                                    '&nbsp;',
                                                                                                    '&amp;nbsp;'),
                                                                                                '<ROW>',
                                                                                                ''),
                                                                                            '</ROW>',
                                                                                            ''),
                                                                                        '<DATA>',
                                                                                        ''),
                                                                                    '</DATA>',
                                                                                    ''),
                                                                                '<moodle>',
                                                                                ''),
                                                                            '</moodle>',
                                                                            ''),
                                                                        '<span',
                                                                        '&lt;span;'),
                                                                    '&Uuml;',
                                                                    '&amp;Uuml;'),
                                                                '&Auml;',
                                                                '&amp;Auml;'),
                                                            '&Ouml;',
                                                            '&amp;Ouml;'),
                                                        'Aktivit&au',
                                                        'Aktivit&amp;auml'),
                                                    'F&uuml',
                                                    'F&amp;uuml'),
                                                'grunds&aum',
                                                'grunds&amp;auml;'),
                                            'vollst&a</text>',
                                            'vollst&amp;auml;</text>'),
                                        '&bdquo;',
                                        '&amp;bdquo;'),
                                    '&ldquo;',
                                    '&amp;ldquo;'),
                                'Welche Angriffsm&</text>',
                                'Welche Angriffsm&amp;ouml;</text>'),
                            'Welche Gegenma&sz</text>',
                            'Welche Gegenma&amp;szlig;</text>'),
                        'Welche Antwortm&o</text>',
                        'Welche Antwortm&amp;ouml;</text>'),
                    '<br />',
                    '&lt;br/&gt;'),
                '&ndash;',
                '&amp;ndash;')
            ORDER BY `v_sqms_short_xml_export_moodle2_4`.`moodle` ASC
            SEPARATOR ' ') AS `quiz`
    FROM
        `v_sqms_short_xml_export_moodle2_4`;
		
-------------------------------------------------------------

CREATE VIEW `v_sqms_moodle_export_full2_3` AS
    SELECT 
        GROUP_CONCAT(DISTINCT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`v_sqms_short_xml_export_moodle2_3`.`moodle`,
                                                                                                                                                '<p><span>',
                                                                                                                                                'lt;p&gt;&lt;span'),
                                                                                                                                            '</p></span>',
                                                                                                                                            '&lt;/span&gt;&lt;/p&gt;'),
                                                                                                                                        '</p>',
                                                                                                                                        '&lt;/p&gt;'),
                                                                                                                                    '<p>',
                                                                                                                                    '&lt;p&gt;'),
                                                                                                                                '<span>',
                                                                                                                                '&lt;span&gt;'),
                                                                                                                            '</span>',
                                                                                                                            '&lt;/span&gt;'),
                                                                                                                        '<span class="st"',
                                                                                                                        '&lt;span; classe="st"'),
                                                                                                                    '&uuml;',
                                                                                                                    '&amp;uuml;'),
                                                                                                                '&auml;',
                                                                                                                '&amp;auml;'),
                                                                                                            '&ouml;',
                                                                                                            '&amp;ouml;'),
                                                                                                        '&szlig;',
                                                                                                        '&amp;szlig;'),
                                                                                                    '&nbsp;',
                                                                                                    '&amp;nbsp;'),
                                                                                                '<ROW>',
                                                                                                ''),
                                                                                            '</ROW>',
                                                                                            ''),
                                                                                        '<DATA>',
                                                                                        ''),
                                                                                    '</DATA>',
                                                                                    ''),
                                                                                '<moodle>',
                                                                                ''),
                                                                            '</moodle>',
                                                                            ''),
                                                                        '<span',
                                                                        '&lt;span;'),
                                                                    '&Uuml;',
                                                                    '&amp;Uuml;'),
                                                                '&Auml;',
                                                                '&amp;Auml;'),
                                                            '&Ouml;',
                                                            '&amp;Ouml;'),
                                                        'Aktivit&au',
                                                        'Aktivit&amp;auml'),
                                                    'F&uuml',
                                                    'F&amp;uuml'),
                                                'grunds&aum',
                                                'grunds&amp;auml;'),
                                            'vollst&a</text>',
                                            'vollst&amp;auml;</text>'),
                                        '&bdquo;',
                                        '&amp;bdquo;'),
                                    '&ldquo;',
                                    '&amp;ldquo;'),
                                'Welche Angriffsm&</text>',
                                'Welche Angriffsm&amp;ouml;</text>'),
                            'Welche Gegenma&sz</text>',
                            'Welche Gegenma&amp;szlig;</text>'),
                        'Welche Antwortm&o</text>',
                        'Welche Antwortm&amp;ouml;</text>'),
                    '<br />',
                    '&lt;br/&gt;'),
                '&ndash;',
                '&amp;ndash;')
            ORDER BY `v_sqms_short_xml_export_moodle2_3`.`moodle` ASC
            SEPARATOR ' ') AS `quiz`
    FROM
        `v_sqms_short_xml_export_moodle2_3`;


CREATE DEFINER=`root`@`localhost` PROCEDURE `csvexport`(IN table_s CHAR(255), IN table_n CHAR(255), IN outfile_path CHAR(255))
BEGIN
SET @table_schema = table_s;
SET @table_n = table_n;
SET @outfile_path = outfile_path;

SET @col_names = (
SELECT GROUP_CONCAT(QUOTE(column_name)) AS columns
FROM information_schema.columns
WHERE table_schema = @table_schema
AND table_name = @table_n);

SET @cols = CONCAT('(SELECT ', @col_names, ')');

SET @query = CONCAT('(SELECT * FROM ', @table_schema, '.', @table_n,
' INTO OUTFILE \'',@outfile_path,'/',@table_n,'.csv\'
FIELDS ENCLOSED BY \'\\\'\' TERMINATED BY \'\t\' ESCAPED BY \'\'
LINES TERMINATED BY \'\n\')');

/* Concatenates column names to query */
SET @sql = CONCAT(@cols, ' UNION ALL ', @query);


PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END
