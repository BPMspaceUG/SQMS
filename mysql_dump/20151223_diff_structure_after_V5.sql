ALTER TABLE `sqms_syllabus_state_rules` 
ADD COLUMN `transistionScript` varchar(255) NULL AFTER `sqms_state_id_TO`;

ALTER TABLE `sqms_question_state_rules` 
ADD COLUMN `transistionScript` varchar(255) NULL AFTER `sqms_state_id_TO`;

ALTER TABLE `sqms_syllabus_state` 
ADD COLUMN `form_data` LONGTEXT NULL AFTER `value`;

ALTER TABLE `sqms_question_state` 
ADD COLUMN `form_data` LONGTEXT NULL AFTER `name`;

CREATE TABLE `sqms_role` (
  `sqms_role_id` INT NOT NULL,
  `role_name` LONGTEXT NULL,
  PRIMARY KEY (`sqms_role_id`));
  
CREATE TABLE `sqms_role_LIAMUSER` (
  `sqms_role_LIAMUSER_id` INT NOT NULL,
  `sqms_role_id` INT NULL,
  `sqms_LIAMUSER_id` INT NULL,
  PRIMARY KEY (`sqms_role_LIAMUSER_id`));

ALTER TABLE `sqms_topic` 
ADD COLUMN `sqms_role_id` INT NULL AFTER `name`;

ALTER TABLE `sqms_role_LIAMUSER` 
ADD INDEX `role_id_1_idx` (`sqms_role_id` ASC);
ALTER TABLE `sqms_role_LIAMUSER` 
ADD CONSTRAINT `role_id_1`
  FOREIGN KEY (`sqms_role_id`)
  REFERENCES `sqms_role` (`sqms_role_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `sqms_topic` 
ADD INDEX `ole_id_2_idx` (`sqms_role_id` ASC);
ALTER TABLE `sqms_topic` 
ADD CONSTRAINT `role_id_2`
  FOREIGN KEY (`sqms_role_id`)
  REFERENCES `sqms_role` (`sqms_role_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
