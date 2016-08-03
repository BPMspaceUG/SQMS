INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('1', 'ADMIN');
INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('2', 'Toipc 9 Authoren');
INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('3', 'Toipc 11 Authoren');
INSERT INTO `sqms_role` (`sqms_role_id`, `role_name`) VALUES ('4', 'Topic 10 Authoren');

INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('1', '2', '1');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('2', '3', '1');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('3', '1', '2');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('4', '4', '4');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('5', '2', '3');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('6', '4', '3');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('7', '2', '5');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('8', '3', '5');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('9', '4', '5');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('10', '2', '6');
INSERT INTO `sqms_role_LIAMUSER` (`sqms_role_LIAMUSER_id`, `sqms_role_id`, `sqms_LIAMUSER_id`) VALUES ('11', '4', '6');

UPDATE `sqms_topic` SET `sqms_role_id`='2' WHERE `sqms_topic_id`='9';
UPDATE `sqms_topic` SET `sqms_role_id`='3' WHERE `sqms_topic_id`='11';
UPDATE `sqms_topic` SET `sqms_role_id`='4' WHERE `sqms_topic_id`='10';

UPDATE `bpmspace_sqms_v5`.`sqms_question_type` SET `name`='sample testing', `description`='this question can be public!' WHERE `sqms_question_type_id`='1';
UPDATE `bpmspace_sqms_v5`.`sqms_question_type` SET `name`='live testing', `description`='this question MUST NOT be public!' WHERE `sqms_question_type_id`='2';
UPDATE `bpmspace_sqms_v5`.`sqms_question_type` SET `name`='special', `description`=' ' WHERE `sqms_question_type_id`='3';;
