UPDATE `sqms_syllabus_state_rules` SET `transistionScript`='func_setPredesDeprecatd.inc.php' WHERE `sqms_syllabus_state_rules_id`='17' and`sqms_state_id_FROM`='2' and`sqms_state_id_TO`='3';


UPDATE `sqms_question_type` SET `name`='Calculated', `description`='' WHERE `sqms_question_type_id`='1';
UPDATE `sqms_question_type` SET `name`='Calculated multi-choice', `description`='' WHERE `sqms_question_type_id`='2';
UPDATE `sqms_question_type` SET `name`='Calculated simple' WHERE `sqms_question_type_id`='3';
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('5', 'Essay');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('6', 'Matching');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('7', 'Embedded Answers');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('8', 'Multiple choice');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('9', 'Short Answer');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('10', 'Numerical');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('11', 'Random short-answer matching');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('12', 'True/False');
INSERT INTO `sqms_question_type` (`sqms_question_type_id`, `name`) VALUES ('4', 'Description');

