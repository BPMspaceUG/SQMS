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
DROP TABLE IF EXISTS `sqms_exam_version`;

CREATE TABLE `sqms_exam_version` (
  `sqms_exam_version_id` int(11) NOT NULL AUTO_INCREMENT,
  `sqms_exam_version_name` longtext,
  PRIMARY KEY (`sqms_exam_version_id`)
) ENGINE=InnoDB AUTO_INCREMENT=678 DEFAULT CHARSET=utf8;

# Create table with questions and answers and their coresponding set in sqms_exam_version

DROP TABLE IF EXISTS `sqms_question_answer_exam_version`;

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
FROM bpmspace_sqms_v6.sqms_question_answer_exam_version d natural join sqms_topic f natural join sqms_language g natural join sqms_question e join sqms_answer a on a.sqms_answer_id = d.sqms_answer_id_1 join sqms_answer b on b.sqms_answer_id = sqms_answer_id_2 join sqms_answer c on c.sqms_answer_id = sqms_answer_id_3;

DROP TABLE IF EXISTS `bpmspace_sqms_v6.sqms_syllabus`;

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

    VIEW `bpmspace_sqms_v6`.`v_sqms_syllabus_syllabuselement_question` AS

    SELECT

        `SYLLA`.`sqms_syllabus_id` AS `sqms_syllabus_id`,

        `SYLLA`.`name` AS `S_name`,

        `SYLLA`.`description` AS `S_description`,

        `SYLEL`.`sqms_syllabus_element_id` AS `sqms_syllabus_element_id`,

        `SYLEL`.`element_order` AS `element_order`,

        `SYLEL`.`name` AS `SE_name`,

        `SYLEL`.`description` AS `SE_description`,

        `SYLEL`.`severity` AS `severity`,

        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_time`) / 100) * 2),

                0) / 2) AS `time`,

        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 2),

                0) / 2) AS `question_count`,

        (ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 1.2) * 2),

                0) / 2) AS `question_count_max`,

        (ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 0.8) * 2),

                0) / 2) AS `question_count_min`,

        `QUEST`.`sqms_question_id` AS `sqms_question_id`,

        `QUEST`.`question` AS `question`

    FROM

        (((`bpmspace_sqms_v6`.`sqms_syllabus_element` `SYLEL`

        LEFT JOIN `bpmspace_sqms_v6`.`sqms_syllabus` `SYLLA` ON ((`SYLLA`.`sqms_syllabus_id` = `SYLEL`.`sqms_syllabus_id`)))

        LEFT JOIN `bpmspace_sqms_v6`.`sqms_syllabus_element_question` `SYLEL_Q` ON ((`SYLEL`.`sqms_syllabus_element_id` = `SYLEL_Q`.`sqms_syllabus_element_id`)))

        LEFT JOIN `bpmspace_sqms_v6`.`sqms_question` `QUEST` ON ((`SYLEL_Q`.`sqms_question_id` = `QUEST`.`sqms_question_id`)))

    ORDER BY `SYLLA`.`sqms_syllabus_id` , `SYLEL`.`element_order`;

CREATE OR REPLACE

VIEW `bpmspace_sqms_v6`.`v_sqms_syllabus_syllabuselement` AS

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

        `SYLLA`.`sqms_syllabus_question_nr` AS `sqms_syllabus_question_nr`,

        `SYLLA`.`sqms_syllabus_time` AS `sqms_syllabus_time`,

        `SYLEL`.`element_order` AS `element_order`,

        `SYLEL`.`name` AS `SE_name`,

        `SYLEL`.`description` AS `SE_description`,

        `SYLEL`.`severity` AS `severity`,

        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_time`) / 100) * 2),

                0) / 2) AS `time`,

        (ROUND((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 2),

                0) / 2) AS `question_count`,

        (ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 1.2) * 2),

                0) / 2) AS `question_count_max`,

        (ROUND(((((`SYLEL`.`severity` * `SYLLA`.`sqms_syllabus_question_nr`) / 100) * 0.8) * 2),

                0) / 2) AS `question_count_min`,

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

                `SYLLA`.`validity_period_to`) AS `Syllabus_Info`

    FROM

        ((((`bpmspace_sqms_v6`.`sqms_syllabus_element` `SYLEL`

        JOIN `bpmspace_sqms_v6`.`sqms_syllabus` `SYLLA`)

        JOIN `bpmspace_sqms_v6`.`sqms_language` `LANG`)

        JOIN `bpmspace_sqms_v6`.`sqms_syllabus_state` `STATE`)

        JOIN `bpmspace_sqms_v6`.`sqms_topic` `TOPIC`)

    WHERE

        ((`SYLLA`.`sqms_syllabus_id` = `SYLEL`.`sqms_syllabus_id`)

            AND (`LANG`.`sqms_language_id` = `SYLLA`.`sqms_language_id`)

            AND (`STATE`.`sqms_syllabus_state_id` = `SYLLA`.`sqms_state_id`)

            AND (`TOPIC`.`sqms_topic_id` = `SYLLA`.`sqms_topic_id`));


# Export in one Cell.

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
        JOIN `sqms_answer` `c` ON ((`c`.`sqms_answer_id` = `d`.`sqms_answer_id_3`)))