-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bpmspace_sqms_v6
-- ------------------------------------------------------
-- Server version	5.5.54-0+deb8u1

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
-- Table structure for table `sqms_answer`
--

DROP TABLE IF EXISTS `sqms_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_answer` (
  `sqms_answer_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `answer` mediumtext NOT NULL,
  `correct` tinyint(1) NOT NULL,
  `sqms_question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sqms_answer_id`,`sqms_question_id`),
  UNIQUE KEY `id_UNIQUE` (`sqms_answer_id`),
  KEY `fk_sqms_answer_sqms_question1_idx` (`sqms_question_id`),
  CONSTRAINT `fk_sqms_answer_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`sqms_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_answer`
--

LOCK TABLES `sqms_answer` WRITE;
/*!40000 ALTER TABLE `sqms_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_history`
--

DROP TABLE IF EXISTS `sqms_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_history` (
  `sqms_history_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_users_login` varchar(32) NOT NULL,
  `timestamp` datetime NOT NULL,
  `table_name` varchar(45) NOT NULL,
  `column_name` varchar(45) NOT NULL,
  `value_OLD` varchar(1024) DEFAULT NULL,
  `value_NEW` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`sqms_history_id`,`sqms_users_login`),
  KEY `fk_sqms_history_sqms_users1_idx` (`sqms_users_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_history`
--

LOCK TABLES `sqms_history` WRITE;
/*!40000 ALTER TABLE `sqms_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_language`
--

DROP TABLE IF EXISTS `sqms_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_language` (
  `sqms_language_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `language` varchar(45) DEFAULT NULL,
  `language_short` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`sqms_language_id`),
  UNIQUE KEY `idsqms_language_UNIQUE` (`sqms_language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_language`
--

LOCK TABLES `sqms_language` WRITE;
/*!40000 ALTER TABLE `sqms_language` DISABLE KEYS */;
INSERT INTO `sqms_language` VALUES (1,'deutsch','DE'),(2,'english','EN'),(3,'francais','FR'),(4,'espanol','ES');
/*!40000 ALTER TABLE `sqms_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_question`
--

DROP TABLE IF EXISTS `sqms_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question` (
  `sqms_question_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_language_id` bigint(20) NOT NULL,
  `sqms_question_state_id` bigint(20) NOT NULL,
  `question` mediumtext NOT NULL,
  `author` varchar(32) NOT NULL,
  `version` bigint(20) NOT NULL,
  `id_external` varchar(45) DEFAULT NULL,
  `sqms_question_id_predecessor` bigint(20) DEFAULT NULL,
  `sqms_question_id_successor` bigint(20) DEFAULT NULL,
  `sqms_question_type_id` bigint(20) NOT NULL,
  `sqms_topic_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`sqms_question_id`),
  KEY `fk_sqms_question_sqms_question_type1_idx` (`sqms_question_type_id`),
  KEY `fk_sqms_language_id1_idx` (`sqms_language_id`),
  KEY `sqms_topic_id` (`sqms_topic_id`),
  KEY `ugzgjhfdfgjfg_idx` (`sqms_question_state_id`),
  CONSTRAINT `fk_sqms_question_sqms_question_type1` FOREIGN KEY (`sqms_question_type_id`) REFERENCES `sqms_question_type` (`sqms_question_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_2` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`sqms_language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_4` FOREIGN KEY (`sqms_topic_id`) REFERENCES `sqms_topic` (`sqms_topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_test` FOREIGN KEY (`sqms_question_state_id`) REFERENCES `sqms_question_state` (`sqms_question_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question`
--

LOCK TABLES `sqms_question` WRITE;
/*!40000 ALTER TABLE `sqms_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_question` ENABLE KEYS */;
UNLOCK TABLES;

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
  CONSTRAINT `fk_sqms_users_has_sqms_question_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`sqms_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_reviewer`
--

LOCK TABLES `sqms_question_reviewer` WRITE;
/*!40000 ALTER TABLE `sqms_question_reviewer` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_question_reviewer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_question_state`
--

DROP TABLE IF EXISTS `sqms_question_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_state` (
  `sqms_question_state_id` bigint(20) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `form_data` longtext,
  PRIMARY KEY (`sqms_question_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_state`
--

LOCK TABLES `sqms_question_state` WRITE;
/*!40000 ALTER TABLE `sqms_question_state` DISABLE KEYS */;
INSERT INTO `sqms_question_state` VALUES (1,'new',NULL),(2,'ready',NULL),(3,'released',NULL),(4,'deprecated',NULL);
/*!40000 ALTER TABLE `sqms_question_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_question_state_rules`
--

DROP TABLE IF EXISTS `sqms_question_state_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_state_rules` (
  `sqms_question_state_rules_id` bigint(20) NOT NULL,
  `sqms_state_id_FROM` bigint(20) NOT NULL,
  `sqms_state_id_TO` bigint(20) NOT NULL,
  `transistionScript` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sqms_question_state_rules_id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  UNIQUE KEY `id_UNIQUE` (`sqms_question_state_rules_id`),
  KEY `fk_sqms_question_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_question_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_question_state` (`sqms_question_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_question_state` (`sqms_question_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_state_rules`
--

LOCK TABLES `sqms_question_state_rules` WRITE;
/*!40000 ALTER TABLE `sqms_question_state_rules` DISABLE KEYS */;
INSERT INTO `sqms_question_state_rules` VALUES (6,3,4,NULL),(10,2,1,NULL),(11,2,3,NULL),(12,2,4,NULL),(13,1,2,NULL),(14,1,4,NULL);
/*!40000 ALTER TABLE `sqms_question_state_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_question_type`
--

DROP TABLE IF EXISTS `sqms_question_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_question_type` (
  `sqms_question_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sqms_question_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_type`
--

LOCK TABLES `sqms_question_type` WRITE;
/*!40000 ALTER TABLE `sqms_question_type` DISABLE KEYS */;
INSERT INTO `sqms_question_type` VALUES (1,'sample testing','this question can be public!'),(2,'live testing','this question MUST NOT be public!'),(3,'special',' ');
/*!40000 ALTER TABLE `sqms_question_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_role`
--

DROP TABLE IF EXISTS `sqms_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_role` (
  `sqms_role_id` int(11) NOT NULL,
  `role_name` longtext,
  PRIMARY KEY (`sqms_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_role`
--

LOCK TABLES `sqms_role` WRITE;
/*!40000 ALTER TABLE `sqms_role` DISABLE KEYS */;
INSERT INTO `sqms_role` VALUES (1,'ADMIN'),(2,'Topic 1 Author');
/*!40000 ALTER TABLE `sqms_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_role_LIAMUSER`
--

DROP TABLE IF EXISTS `sqms_role_LIAMUSER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_role_LIAMUSER` (
  `sqms_role_LIAMUSER_id` int(11) NOT NULL,
  `sqms_role_id` int(11) DEFAULT NULL,
  `sqms_LIAMUSER_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sqms_role_LIAMUSER_id`),
  KEY `role_id_1_idx` (`sqms_role_id`),
  CONSTRAINT `role_id_1` FOREIGN KEY (`sqms_role_id`) REFERENCES `sqms_role` (`sqms_role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_role_LIAMUSER`
--

LOCK TABLES `sqms_role_LIAMUSER` WRITE;
/*!40000 ALTER TABLE `sqms_role_LIAMUSER` DISABLE KEYS */;
INSERT INTO `sqms_role_LIAMUSER` VALUES (1,1,1),(2,2,1);
/*!40000 ALTER TABLE `sqms_role_LIAMUSER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_syllabus`
--

DROP TABLE IF EXISTS `sqms_syllabus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  PRIMARY KEY (`sqms_syllabus_id`),
  KEY `fk_sqms_syllabus_sqms_language1_idx` (`sqms_language_id`),
  KEY `fk_sqms_syllabus_sqms_state1_idx` (`sqms_state_id`),
  CONSTRAINT `fk_sqms_syllabus_sqms_language1` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`sqms_language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_sqms_state1` FOREIGN KEY (`sqms_state_id`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus`
--

LOCK TABLES `sqms_syllabus` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_syllabus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_syllabus_element`
--

DROP TABLE IF EXISTS `sqms_syllabus_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_element` (
  `sqms_syllabus_element_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element_order` int(11) NOT NULL,
  `severity` decimal(7,2) NOT NULL,
  `sqms_syllabus_id` bigint(20) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` mediumtext,
  `sqms_syllabus_element_id_predecessor` bigint(20) NOT NULL DEFAULT '0',
  `sqms_syllabus_element_id_successor` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sqms_syllabus_element_id`),
  KEY `fk_sqms_syllabus_element_sqms_syllabus_idx` (`sqms_syllabus_id`),
  CONSTRAINT `fk_sqms_syllabus_element_sqms_syllabus` FOREIGN KEY (`sqms_syllabus_id`) REFERENCES `sqms_syllabus` (`sqms_syllabus_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_element`
--

LOCK TABLES `sqms_syllabus_element` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_element` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_syllabus_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_syllabus_element_question`
--

DROP TABLE IF EXISTS `sqms_syllabus_element_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_element_question` (
  `sqms_syllabus_element_question_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sqms_question_id` bigint(20) NOT NULL,
  `sqms_syllabus_element_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sqms_syllabus_element_id`,`sqms_question_id`),
  UNIQUE KEY `id` (`sqms_syllabus_element_question_id`),
  KEY `fk_sqms_syllabus_element_question_sqms_question1_idx` (`sqms_question_id`),
  KEY `fk_sqms_syllabus_element_question_sqms_syllabus_element1_idx` (`sqms_syllabus_element_id`),
  CONSTRAINT `fk_sqms_syllabus_element_question_sqms_question1` FOREIGN KEY (`sqms_question_id`) REFERENCES `sqms_question` (`sqms_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_element_question_sqms_syllabus_element1` FOREIGN KEY (`sqms_syllabus_element_id`) REFERENCES `sqms_syllabus_element` (`sqms_syllabus_element_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=601 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_element_question`
--

LOCK TABLES `sqms_syllabus_element_question` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_element_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_syllabus_element_question` ENABLE KEYS */;
UNLOCK TABLES;

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
  CONSTRAINT `fk_sqms_users_has_sqms_syllabus_sqms_syllabus1` FOREIGN KEY (`sqms_syllabus_id`) REFERENCES `sqms_syllabus` (`sqms_syllabus_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_reviewer`
--

LOCK TABLES `sqms_syllabus_reviewer` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_reviewer` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqms_syllabus_reviewer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_syllabus_state`
--

DROP TABLE IF EXISTS `sqms_syllabus_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_state` (
  `sqms_syllabus_state_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` int(11) NOT NULL,
  `form_data` longtext,
  PRIMARY KEY (`sqms_syllabus_state_id`),
  UNIQUE KEY `id_UNIQUE` (`sqms_syllabus_state_id`),
  UNIQUE KEY `state_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_state`
--

LOCK TABLES `sqms_syllabus_state` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_state` DISABLE KEYS */;
INSERT INTO `sqms_syllabus_state` VALUES (1,'new',10,NULL),(2,'ready',80,NULL),(3,'released',100,NULL),(4,'deprecated',999,NULL);
/*!40000 ALTER TABLE `sqms_syllabus_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_syllabus_state_rules`
--

DROP TABLE IF EXISTS `sqms_syllabus_state_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_syllabus_state_rules` (
  `sqms_syllabus_state_rules_id` bigint(20) NOT NULL,
  `sqms_state_id_FROM` bigint(20) NOT NULL,
  `sqms_state_id_TO` bigint(20) NOT NULL,
  `transistionScript` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sqms_syllabus_state_rules_id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_state_rules`
--

LOCK TABLES `sqms_syllabus_state_rules` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_state_rules` DISABLE KEYS */;
INSERT INTO `sqms_syllabus_state_rules` VALUES (12,3,4,NULL),(16,2,1,NULL),(17,2,3,NULL),(18,2,4,NULL),(19,1,2,NULL),(20,1,4,NULL);
/*!40000 ALTER TABLE `sqms_syllabus_state_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sqms_topic`
--

DROP TABLE IF EXISTS `sqms_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sqms_topic` (
  `sqms_topic_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `sqms_role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`sqms_topic_id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `id_UNIQUE` (`sqms_topic_id`),
  KEY `ole_id_2_idx` (`sqms_role_id`),
  CONSTRAINT `role_id_2` FOREIGN KEY (`sqms_role_id`) REFERENCES `sqms_role` (`sqms_role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_topic`
--

LOCK TABLES `sqms_topic` WRITE;
/*!40000 ALTER TABLE `sqms_topic` DISABLE KEYS */;
INSERT INTO `sqms_topic` VALUES (1,'Topic 1',2);
/*!40000 ALTER TABLE `sqms_topic` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-09 10:07:57
