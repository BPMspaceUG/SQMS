SET GLOBAL log_bin_trust_function_creators = 1;

DROP function if exists generate_fname;
DELIMITER $$
CREATE FUNCTION generate_fname () RETURNS varchar(255)
BEGIN
	RETURN ELT(FLOOR(1 + (RAND() * (5-1))), "James","Mary","John","Patricia");
END$$

DELIMITER ;

select generate_fname() as FirstName, generate_fname() as LastName;

SET GLOBAL log_bin_trust_function_creators = 0;

SET SQL_SAFE_UPDATES = 0;
UPDATE `bpmspace_sqms_v6_A`.`sqms_question` SET `question` = concat("Is this a question with the ID [",`sqms_question_id`, "]?");
UPDATE `bpmspace_sqms_v6_A`.`sqms_question` SET `author`= generate_fname();
UPDATE `bpmspace_sqms_v6_A`.`sqms_answer` SET `answer` = concat("This answer with id [" ,`sqms_answer_id` , "] is correct. It is associated to question with ID [",sqms_question_id,"]") WHERE `correct` = 1;
UPDATE `bpmspace_sqms_v6_A`.`sqms_answer` SET `answer` = concat("This answer with id [" ,`sqms_answer_id` , "] is wrong. It is associated to question with ID [",sqms_question_id,"]") WHERE `correct` = 0;
UPDATE `bpmspace_sqms_v6_A`.`sqms_topic`SET `name` = concat("Topic with ID [",`sqms_topic_id`,"]");

UPDATE `bpmspace_sqms_v6_A`.`sqms_role` sr,
(   SELECT `name` , `sqms_role_id`
    FROM `bpmspace_sqms_v6_A`.`sqms_topic` 
) st
SET `sr`.`role_name` = concat("team of authors for ",`st`.`name`)
WHERE `st`.`sqms_role_id` = `sr`.`sqms_role_id`;

SET SQL_SAFE_UPDATES = 1;

