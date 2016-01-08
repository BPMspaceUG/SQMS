-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bpmspace_sqms
-- ------------------------------------------------------
-- Server version	5.5.46-0+deb7u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mylog`
--

DROP TABLE IF EXISTS `mylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mylog` (
  `t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `val` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_answer`
--

DROP TABLE IF EXISTS `sqms_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_answer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `answer` mediumtext NOT NULL,
  `correct` tinyint(1) NOT NULL,
  `sqms_question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`sqms_question_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_sqms_answer_sqms_question1_idx` (`sqms_question_id`),
  CONSTRAINT `fk_sqms_answer_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_history`
--

DROP TABLE IF EXISTS `sqms_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_users_login` varchar(32) NOT NULL,
  `timestamp` datetime NOT NULL,
  `table_name` varchar(45) NOT NULL,
  `column_name` varchar(45) NOT NULL,
  `value_OLD` varchar(1024) DEFAULT NULL,
  `value_NEW` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`,`sqms_users_login`),
  KEY `fk_sqms_history_sqms_users1_idx` (`sqms_users_login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_language`
--

DROP TABLE IF EXISTS `sqms_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_language` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `language` varchar(45) DEFAULT NULL,
  `language_short` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idsqms_language_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_log`
--

DROP TABLE IF EXISTS `sqms_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_log` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `inserted_date` datetime DEFAULT NULL,
  `username` varchar(90) NOT NULL,
  `application` varchar(200) NOT NULL,
  `creator` varchar(30) NOT NULL,
  `ip_user` varchar(32) NOT NULL,
  `action` varchar(30) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2198 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_logged`
--

DROP TABLE IF EXISTS `sqms_logged`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_logged` (
  `login` varchar(32) NOT NULL,
  `date_login` varchar(128) DEFAULT NULL,
  `sc_session` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`login`),
  CONSTRAINT `sqms_logged_ibfk_1` FOREIGN KEY (`login`) REFERENCES `sqms_users` (`login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_question`
--

DROP TABLE IF EXISTS `sqms_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question` (
  `sqms_language_id` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_state_id` bigint(20) NOT NULL,
  `question` mediumtext NOT NULL,
  `author` varchar(32) NOT NULL,
  `version` bigint(20) NOT NULL,
  `id_external` varchar(45) DEFAULT NULL,
  `sqms_question_id_predecessor` bigint(20) DEFAULT NULL,
  `sqms_question_id_successor` bigint(20) DEFAULT NULL,
  `sqms_question_type_id` bigint(20) NOT NULL,
  `sqms_topic_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sqms_question_sqms_state1_idx` (`sqms_state_id`),
  KEY `fk_sqms_question_sqms_question_type1_idx` (`sqms_question_type_id`),
  KEY `fk_sqms_language_id1_idx` (`sqms_language_id`),
  KEY `sqms_topic_id` (`sqms_topic_id`),
  CONSTRAINT `fk_sqms_question_sqms_question_type1` FOREIGN KEY (`sqms_question_type_id`) REFERENCES `sqms_question_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_question_sqms_state1` FOREIGN KEY (`sqms_state_id`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_2` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_4` FOREIGN KEY (`sqms_topic_id`) REFERENCES `sqms_topic` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_question_reviewer`
--

DROP TABLE IF EXISTS `sqms_question_reviewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_reviewer` (
  `sqms_users_login` varchar(32) NOT NULL,
  `sqms_question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sqms_users_login`,`sqms_question_id`),
  KEY `fk_sqms_users_has_sqms_question_sqms_question1_idx` (`sqms_question_id`),
  KEY `fk_sqms_users_has_sqms_question_sqms_users1_idx` (`sqms_users_login`),
  CONSTRAINT `fk_sqms_users_has_sqms_question_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_question_state`
--

DROP TABLE IF EXISTS `sqms_question_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_state` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_state_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`sqms_state_id`),
  KEY `fk_sqms_question_state_sqms_state1_idx` (`sqms_state_id`),
  CONSTRAINT `fk_sqms_question_state_sqms_state1` FOREIGN KEY (`sqms_state_id`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_question_state_rules`
--

DROP TABLE IF EXISTS `sqms_question_state_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_state_rules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_state_id_FROM` bigint(20) NOT NULL,
  `sqms_state_id_TO` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_sqms_question_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_question_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_question_type`
--

DROP TABLE IF EXISTS `sqms_question_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_state`
--

DROP TABLE IF EXISTS `sqms_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_state` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `state_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_syllabus`
--

DROP TABLE IF EXISTS `sqms_syllabus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  KEY `fk_sqms_syllabus_sqms_topic1_idx` (`sqms_topic_id`),
  KEY `fk_sqms_syllabus_sqms_language1_idx` (`sqms_language_id`),
  KEY `fk_sqms_syllabus_sqms_state1_idx` (`sqms_state_id`),
  CONSTRAINT `fk_sqms_syllabus_sqms_language1` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_sqms_state1` FOREIGN KEY (`sqms_state_id`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_sqms_topic1` FOREIGN KEY (`sqms_topic_id`) REFERENCES `sqms_topic` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_syllabus_element`
--

DROP TABLE IF EXISTS `sqms_syllabus_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_element` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element_order` int(11) NOT NULL,
  `severity` decimal(7,2) NOT NULL,
  `sqms_syllabus_id` bigint(20) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` mediumtext,
  `sqms_syllabus_element_id_predecessor` bigint(20) NOT NULL DEFAULT '0',
  `sqms_syllabus_element_id_successor` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_sqms_syllabus_element_sqms_syllabus_idx` (`sqms_syllabus_id`),
  CONSTRAINT `fk_sqms_syllabus_element_sqms_syllabus` FOREIGN KEY (`sqms_syllabus_id`) REFERENCES `sqms_syllabus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_syllabus_element_question`
--

DROP TABLE IF EXISTS `sqms_syllabus_element_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_element_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_question_id` bigint(20) NOT NULL,
  `sqms_syllabus_element_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sqms_syllabus_element_id`,`sqms_question_id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_sqms_syllabus_element_question_sqms_question1_idx` (`sqms_question_id`),
  KEY `fk_sqms_syllabus_element_question_sqms_syllabus_element1_idx` (`sqms_syllabus_element_id`),
  CONSTRAINT `fk_sqms_syllabus_element_question_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_element_question_sqms_syllabus_element1` FOREIGN KEY (`sqms_syllabus_element_id`) REFERENCES `sqms_syllabus_element` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=601 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_syllabus_reviewer`
--

DROP TABLE IF EXISTS `sqms_syllabus_reviewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_reviewer` (
  `sqms_users_login` varchar(32) NOT NULL,
  `sqms_syllabus_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sqms_users_login`,`sqms_syllabus_id`),
  KEY `fk_sqms_users_has_sqms_syllabus_sqms_syllabus1_idx` (`sqms_syllabus_id`),
  KEY `fk_sqms_users_has_sqms_syllabus_sqms_users1_idx` (`sqms_users_login`),
  CONSTRAINT `fk_sqms_users_has_sqms_syllabus_sqms_syllabus1` FOREIGN KEY (`sqms_syllabus_id`) REFERENCES `sqms_syllabus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_syllabus_state_rules`
--

DROP TABLE IF EXISTS `sqms_syllabus_state_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_state_rules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_state_id_FROM` bigint(20) NOT NULL,
  `sqms_state_id_TO` bigint(20) NOT NULL,
  PRIMARY KEY (`id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_topic`
--

DROP TABLE IF EXISTS `sqms_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_topic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sqms_users`
--

DROP TABLE IF EXISTS `sqms_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_users` (
  `login` varchar(32) NOT NULL,
  `pswd` varchar(32) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL,
  `activation_code` varchar(32) DEFAULT NULL,
  `priv_admin` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-25 10:55:05
