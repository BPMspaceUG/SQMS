-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: bpmspace_sqms_v1
-- ------------------------------------------------------
-- Server version	5.7.9

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


DROP DATABASE IF EXISTS`bpmspace_sqms_v1` ;
CREATE DATABASE `bpmspace_sqms_v1` ;
GRANT SELECT, INSERT, UPDATE ON `bpmspace_sqms_v1`.* TO 'bpmspace_sqms'@'localhost';
USE bpmspace_sqms_v1;
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
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_answer`
--

LOCK TABLES `sqms_answer` WRITE;
/*!40000 ALTER TABLE `sqms_answer` DISABLE KEYS */;
INSERT INTO `sqms_answer` VALUES (1,'true answer: 533990c8b3322aad25c075a0a6ab966a',1,34),(2,'false answer: 0f43604265e35bccb4ec7f0a5ed46ea7',0,34),(3,'true answer: 017755fdf72e794dd2b072d73e17f9df',1,34),(5,'true answer: feb11887cccb48659319be05a422d681',1,35),(6,'true answer: d89fa1f9cd274882d8a6f1d576d07c1e',1,35),(7,'false answer: ca4ccbf0aa326d48071b8168ec056c67',0,35),(9,'true answer: 832f9ea89cf441c1ef17d9e9c138b618',1,36),(11,'false answer: 53123f06d9c32598124868c1e4346047',0,36),(12,'false answer: eec19340e813119768dce64300be830e',0,36),(13,'false answer: 849967ec264700677060c990f51e8016',0,37),(14,'true answer: 761f1adc2386a8a2f1962ea838588b07',1,37),(15,'true answer: 3c41095165dad30fdc6567a3ad1af24b',1,37),(17,'true answer: 6c7c6f7c18e6a79003a6198cb345037f',1,38),(18,'false answer: 022e918ce01bfbeeb8c30ab59ff745b6',0,38),(19,'true answer: a995ad43b7c5bcb0211255e3b2c25137',1,38),(20,'false answer: a21af2b794eabcb2ffb46beb4dd02ff4',0,38),(21,'true answer: a970c6f8dc3e6b154084a103d80fc1d5',1,39),(22,'true answer: 2858af0c721440e028db0a48229af5a2',1,39),(23,'false answer: c25f997f32f1d194dcc4587a896342c9',0,39),(24,'false answer: 5d75109576e96b94cebb8f09c52969c8',0,39),(25,'true answer: 5957d3bf90e9c3cf11e9939ab747bacd',1,40),(27,'true answer: 5414bbaa5337ca7c87a6376a26f43587',1,40),(28,'false answer: 7dbd1e48f5cbbeeb165ebe3199c9e16a',0,40),(30,'false answer: bd6428fad950dd0f237d9c5522a199bc',0,41),(31,'true answer: 6a301c7c735907fb79348a3b39932be1',1,41),(32,'false answer: 00a623a5b8b8af7430c7fd65d01d597d',0,41),(33,'true answer: 0590ea730f79b246444ba2ae5bcd92b9',1,42),(34,'false answer: cb7daca7d6611294a67f1dbd82fb12e4',0,42),(35,'false answer: 2e833af0c505e3e8b48fc809641d5e57',0,42),(37,'true answer: 773147ec4a7f9b9680147b02f37af2e7',1,43),(38,'false answer: 76778734963e14df65ab5a3113e18ca1',0,43),(40,'true answer: 921c1eb9a5b2753efc316707ca435774',1,43),(41,'false answer: 13df6cd8de1bded8068de7a859d7f5ef',0,44),(42,'false answer: 2b7caffed43b8d556a816ff01f622c4e',0,44),(43,'true answer: e8716577a708b610ce689da60df07c82',1,44),(45,'false answer: d4865ba60a632131334f88067fbcb0ca',0,45),(47,'true answer: 88286c6fa741d3d98ddec37044e5ff87',1,45),(48,'true answer: b767bc2d3a0c77cf4050bd0b20695985',1,45),(50,'true answer: 85f8413dc20a498f4ec31db45a62c2a5',1,46),(51,'true answer: 9feb9c02cd6774630677fac000ca2f73',1,46),(52,'true answer: 37f2202d4ee77294e5951d002d49cba8',1,46),(54,'true answer: 8e0fc04353fec67b0dc4b5dc322dfe15',1,47),(55,'false answer: f41df281063f7c05e2da522b1aa18c65',0,47),(57,'false answer: 6cf19726e1c30bc8637b12eeee2a7d5b',0,47),(58,'false answer: 3dc0e497c6f2c8290734624882ca228e',0,48),(59,'true answer: f36bdf3e026fa2f696b5d197bd950cf2',1,48),(61,'false answer: f99ccff36222997f6bbdaad5ad7c4d95',0,48),(63,'false answer: f36bdf3e026fa2f696b5d197bd950cf2',0,49),(64,'false answer: df6e96787b6d406adf5c9a5aa8747fcc',0,49),(65,'false answer: 9d4f2d653bdc9cc55767f56d08ed58ec',0,49),(66,'false answer: 565be4debb31cd4daf1d8d5af4ac6b7c',0,50),(67,'false answer: f36bdf3e026fa2f696b5d197bd950cf2',0,50),(69,'true answer: 496b4b8e764e8a3e24f0114859337945',1,50),(70,'false answer: 565be4debb31cd4daf1d8d5af4ac6b7c',0,51),(71,'false answer: f36bdf3e026fa2f696b5d197bd950cf2',0,51),(72,'true answer: 846dace23016806043f7a150a7b760f5',1,51),(73,'false answer: 496b4b8e764e8a3e24f0114859337945',0,51),(75,'true answer: 4c88aaebcc9357e5b155da2782d07ff8',1,52),(76,'true answer: 4047d1830db841902219e0f512b506fa',1,52),(77,'false answer: 58d7bd9e683eb6f2916b411fe94a041d',0,52),(78,'true answer: b649737ad2e6c4edd6dbb8c19a8f141e',1,53),(79,'false answer: de26ad31048363e63fa517f8dea694b5',0,53),(80,'true answer: 2ca070018ee6f55a1c1d0077dba90416',1,53),(81,'true answer: 7864f0cf1c658f8884184fe3741209a5',1,53),(82,'false answer: ad5ade47a32e5ebdd82b3a5b21324928',0,54),(83,'true answer: 7e5f933ea53666879744daa510bec231',1,54),(84,'true answer: b436e1fdb581edb004e55e350c26ae3f',1,54),(86,'false answer: de6d29ebb7faba4a48fbe5104448ef5e',0,55),(87,'true answer: e283d10ae3a6811cef1b44017deb02a3',1,55),(88,'true answer: eb07407c9cc71902286c9d2853f17cad',1,55),(89,'false answer: f53b7c72024396e751f039d9ee2932f6',0,55),(90,'true answer: a4f81d2f89c9a6a7c228994be2f41f20',1,56),(91,'true answer: 85a16e99942b846ba1277cee83155d5c',1,56),(92,'false answer: d6b0b9efc0fea7f5081404b694818420',0,56),(93,'true answer: cfd87837377d419273ced9671d229fa0',1,56),(94,'true answer: 5dacced7332d7c7847dbe17d77f8deb7',1,57),(95,'true answer: b44cd66eec175f49e61e0c44a330471a',1,57),(97,'true answer: c498dfc5dde77398cee7bd2cfa0fc9cf',1,57),(98,'false answer: 7eb8b062dc47d0ecade2438b789ef486',0,58),(100,'true answer: 70d9c978c02f4ca72b3b2c8009e8003d',1,58),(101,'false answer: 5a3f71cc4827d0f971565f31491351f3',0,58),(102,'true answer: f4c62d781abcfdf358df727ee6e780a7',1,59),(103,'true answer: 139b272b502031cc60a0bb779fd8ef9c',1,59),(104,'true answer: dfaeafb5bfa3b7661d89bfbdcd9acddc',1,59),(105,'true answer: cbfa59e4592ef128507b43ff2a552bf7',1,59),(106,'true answer: 83511fb981eb3768dcc13f63328232d7',1,60),(107,'true answer: eac82777c2c5859ba34c3592adcfefd0',1,60),(108,'true answer: a672cf290668a3b565384f0293f7be33',1,60),(110,'false answer: cb0aed1675b6f6d9a4f43bc2285ac8a2',0,61),(112,'true answer: d34057d959190de75be481b74d6f275c',1,61),(113,'false answer: 17bcb427d4a04b82aebc9177b9c7dfa7',0,61),(114,'true answer: 07627510c2a00240d5894746dfe5ac7e',1,62),(115,'false answer: b0b89e67b92306e74b7858d5bd76fb8e',0,62),(116,'false answer: 82dc7cd14a15b73bf8ed6a6c551be144',0,62),(118,'true answer: 2cffc28fc9ab8fe6fae91041ed59246d',1,63),(119,'true answer: 19abe8647b0b4243e37230cacca29255',1,63),(121,'false answer: 1895a9dde92460629390eef617503dab',0,63),(123,'true answer: ff3c3e1598921c5736f1111e0359c58c',1,64),(124,'false answer: afab95c12b3294ddf552cd82121b00d3',0,64),(125,'true answer: 0e7d9a529113e24d8b6df34d33a869f5',1,64),(126,'true answer: a175f798098ad9f10f1010eaf8d36cd4',1,65),(127,'false answer: 547853b03ac8420c7c4b17a44973e7c7',0,65),(128,'true answer: 033a6550d9d93ccba044a8a2ad73dab3',1,65),(129,'false answer: c356b4968b71fa010340eba51a4fa055',0,65),(130,'false answer: 6e6535288ef5a7d49a6cd3402f66d1eb',0,66),(131,'true answer: 6a5c34c05bda9245c96935feb7e247ff',1,66),(132,'false answer: c51c5c8b2d41b9a44fc6d5635f187f6f',0,66),(133,'false answer: 4d7a572609071ced2ff1c892f50d0e90',0,66),(134,'false answer: 4f9901a472ac312ecc2e7d9e7bf081b1',0,67),(135,'true answer: e2fea8ca48780ce8a65ab6211a4e2e96',1,67),(137,'false answer: 93ee909acc90acbc21d61349161a7df3',0,67),(138,'false answer: 86a2d4ef0ee8c67b9b67321b1cd9fd07',0,68),(139,'false answer: 2c5063217a955c92d085034ca2811484',0,68),(140,'true answer: 5a103f906672e82eed5b75caaa55a591',1,68),(141,'false answer: 1348676f337ed9cc4e1ad8321bbb8edb',0,69),(142,'false answer: d399765fbaad6e11ff3b7c29acde6c3c',0,69),(143,'true answer: d7621d2ea4568a24fbe4774defd92d3c',1,69),(144,'true answer: da2338c4e290f7642a297b3ca9c1a52a',1,70),(145,'true answer: a874c3d2c5ccc5bbb069e282d0120741',1,70),(146,'true answer: 8290db5b96a0c168f574b990793199c7',1,70),(147,'true answer: cea43421d1ff29d93baf69d497bc69fc',1,71),(148,'true answer: 94f01c4d7b69a24867128dc1f43fbad7',1,71),(149,'false answer: a35cd03b48b85f2c5aa4e9ca2a6777eb',0,71),(150,'true answer: 94b7271bad79442cd5fe30a69e9d51b4',1,72),(151,'true answer: 26c5b496b2336be5f724092bb21197d2',1,72),(152,'false answer: 4f25d26b9424270f1814b8558d389e90',0,72),(153,'true answer: ec866d6289224ddbe1ddd1bf8f595dec',1,73),(154,'true answer: 36b0570cc242e0daf5cc88d7207e6291',1,73),(155,'false answer: fb375496c3bd15527e089983da1ef5de',0,73),(156,'true answer: d2923d83abc04086349a4161e21fc1a0',1,74),(157,'true answer: 5651e0e7966fd6fd144312aa21041578',1,74),(158,'true answer: fc726cfb70480b771cd7ea9457dbcdbb',1,74),(159,'true answer: a7f83d91516d5de71a5f489407cfd95d',1,75),(160,'false answer: 978e97be927c36158bea4adf1dbeb374',0,75),(161,'false answer: f79d8d6bf63c812550c3a7e110a07e96',0,75),(162,'false answer: c46f4f4abc8757c7469df73b0e6b5d53',0,76),(163,'false answer: b73afa8d212e3d48b19abb988c6c2d94',0,76),(164,'true answer: f6454bb8ea49c476588e365818efd232',1,76),(165,'false answer: 4888b3ac0a95d5cbe67481a5eeb93118',0,77),(166,'true answer: a5e98f8bb038d70c3854191afe55b298',1,77),(167,'true answer: 47a306929bc9aae261dcd1ba4073630d',1,77),(168,'false answer: 3191b72075e5e69db724c93d8a93fba3',0,78),(169,'true answer: 6d0bd6436f938ab235825d5f33f0f10a',1,78),(170,'true answer: 9f7aa6881f53da3214ddde5e0ba50a86',1,78),(171,'true answer: 261127197632e2d16b5fc5796e18f510',1,79),(172,'false answer: 00526212dcab5472e486fc4a4760becf',0,79),(173,'true answer: 5796bce2536f18554a4f49c2895e7ee7',1,79),(174,'false answer: 2a957325738cb4943268538a4d2a15de',0,80),(175,'false answer: dfe24b20cb24459c8adde0ac0bd9df7a',0,80),(176,'true answer: ce5edff721d9a8354629162566a37d3e',1,80),(177,'false answer: 4bf3d0e40efdaee474a62aa4139745d9',0,80),(178,'false answer: defb8bad0cb5e66146751dd2944d52e5',0,81),(179,'false answer: 1961ff2f29b5c01199c3eb4a7ce05921',0,82),(180,'true answer: 357dd86977ff0afa158c821465966627',1,82),(181,'true answer: 6987442f38d0ca39c5209ee4d843dd85',1,82),(182,'false answer: f8f6ce6113806386467358b34ac6cfa3',0,83),(183,'false answer: 2784ebc92e699a657e26153df5d86981',0,83),(184,'false answer: 41b9bdccdb7757fa6b30e5386dba8485',0,83),(185,'true answer: 77ba80c839935f10a2f84d1982340a00',1,84),(186,'false answer: 63657b1aa342c57e658c519fb25bc885',0,84),(187,'false answer: 5db28dc7bbf632e98470d9172ed970d0',0,84),(188,'false answer: 6002e6a9ddb0b671962fa99d91d90936',0,85),(189,'true answer: 3e73d54b7234599786d0c2d0070c9916',1,85),(190,'true answer: 1616da2d17f2c9b5ef7c02de9bc443ff',1,85),(191,'false answer: adc31d18dcf3efb05d2694c0892fa0c3',0,86),(192,'true answer: d6e31be99b8c61ca1fba9908cf620718',1,86),(193,'false answer: cd0f6aeeb319c29f6b32b6ead0d74746',0,86),(194,'true answer: 45c34476268c6c69170b00a8288dccbc',1,87),(195,'false answer: dbc12da400817e92f3bace979c7caabe',0,87),(196,'true answer: 2109cc3a25019c5ce8421e8a25e9f530',1,87),(197,'true answer: 32868fd00f3c65b95d830565e5de156d',1,88),(198,'false answer: 07359147e03dce4c6cd91898ea9cd71d',0,88),(199,'false answer: 9d6c61b01db6106deb7ea279c6255572',0,88),(200,'false answer: 937badbc37beda5504cebb01a831aa9d',0,89),(201,'true answer: 519df8faa97429ed37e2785926ab9ae0',1,89),(202,'true answer: 1f4f3c5620c413a926475060df9a4252',1,89),(203,'false answer: 00120471c53118bbc2dc0cc4c6c3272d',0,90),(204,'true answer: 2b00cacd0ed7c19bff0e35915d8c61c8',1,90),(205,'false answer: 75d8b8833fc8722e7dce970e6ba65c7c',0,90),(206,'true answer: 02594797d4cc16422784c9c630c528e9',1,91),(207,'true answer: 9d35a9a400abb46957ef2ace47dc2c42',1,91),(208,'false answer: d037e97f0fe019c5353f98cb02c26830',0,91),(209,'true answer: 236512b3219024dc43dbef8d7c32ad83',1,92),(210,'true answer: c5dd149db92bb4263d6063b7e150336f',1,92),(211,'false answer: 96d15898f387100df8d0d008f6b0f5b2',0,92),(212,'false answer: bd03e83628fde9444d08b29eb0702a6b',0,93),(213,'true answer: c5dd149db92bb4263d6063b7e150336f',1,93),(214,'false answer: a13f876e4c2b3e9799163b4b443073ae',0,93),(215,'true answer: 78fec8ca5b86763d56ec85b5279678ba',1,94),(216,'true answer: f731cff0e19915dc19d8ea2bf3887fdc',1,94),(217,'true answer: 2ddf26e9418aaac8b09358dd86e5ca87',1,94),(218,'false answer: 766ae412ae45b34f92b835c603b10abc',0,95),(219,'true answer: f731cff0e19915dc19d8ea2bf3887fdc',1,95),(220,'false answer: b317c960147a30a00bf78f49602ac20e',0,95),(221,'true answer: 8786cbcb605a6e1a170f22787cd7878e',1,96),(222,'true answer: 293a0c197680a6535243dff9bdcb8833',1,96),(223,'false answer: 98c4c79555cfdf25fe7c26c84bbd9c4a',0,96),(224,'true answer: 7400ac2b0c36ae2d53d526cc008e287d',1,97),(225,'false answer: bea9198af570f826e9daf23dc1eb0298',0,97),(226,'true answer: 4649a05930b071a7319c179148b423ba',1,97),(227,'true answer: 0b6b078fe1b710ba5625852df0f7cca8',1,98),(228,'false answer: 73c0c92a59472caa98bba30081ac2625',0,98),(229,'false answer: f305d831156ed2e0aefd540b8d1dc0b3',0,98),(230,'true answer: b54bcbbafbd529f46ca3701513f02274',1,99),(231,'false answer: cce35fa43311464defd1ff8e0929683b',0,99),(232,'false answer: 8f8ec4e417a57a1b52f422be796828a2',0,99),(233,'false answer: 509cbeaee671cbdd1317e224fcb0ebac',0,100),(234,'true answer: 6a7e920d0463d858973babb1d6480897',1,100),(235,'false answer: bd06833b1ab922e854d8313aac078e0a',0,100),(236,'false answer: 58947e55653f6693106731f05b8befbd',0,101),(237,'true answer: c803f94a1ac307643be2318ffed20e7d',1,101),(238,'false answer: 520bbeb2bbf56966e0561941f194bd5b',0,101),(239,'true answer: 3862d8430191d4d7944a30caa9f9de93',1,102),(240,'false answer: c734676379a9f9395bb5d8d04b64ee3f',0,102),(241,'false answer: 1544f30c01fdabb65dc1aefe2150dd47',0,102),(242,'true answer: b1a88ed3d72a982319b4e727493592c0',1,103),(243,'true answer: e70ec2ae6a59ed3c6a62c32db67fe632',1,103),(244,'false answer: 3d3476fda75f8c92e801013ee591b94a',0,103),(245,'true answer: dd41044ba46257233cfecbde310a3b8b',1,104),(246,'false answer: 0811fe7b81c50474c041a83ff3552010',0,104),(247,'true answer: 3ba7c8756d05f0b2698226ad53a01dd6',1,104),(248,'false answer: ba8313afd6eaf224b8631aa51061db76',0,105),(249,'true answer: 65c74fb2c87971443a1465a79f5cf842',1,105),(250,'true answer: ee5c71d0b7e1000f8279379cfff596a6',1,105),(251,'true answer: eebc321eb3186290865ac620d040d01b',1,106),(252,'false answer: 8cca87cd87edcb703901875dcd99d0f0',0,106),(253,'true answer: a6ec16a658eb74061c2baf3861d51b51',1,106),(254,'true answer: e5b969a9daadb9f5f0713ff946d8c3cf',1,107),(255,'true answer: 000585498b6426fd8f780b8e4fa67b74',1,107),(256,'false answer: 319aa6739f7ba8200d9b23da1ed16762',0,107),(257,'true answer: a77211ea70cec513cc6a75c44a295df6',1,108),(258,'true answer: 927b76226147975c393243d41c5ded5a',1,108),(259,'false answer: 201eaca2daeb096b892f1e54bcb08ff9',0,108),(260,'true answer: eb5c77914d1cec3feb47cbbf61eee381',1,109),(261,'false answer: a80a00a164c403b3a426e2facd3ea57d',0,109),(262,'false answer: 201eaca2daeb096b892f1e54bcb08ff9',0,109),(263,'true answer: 6a2ca713ea1255a80162781af570afa4',1,110),(264,'true answer: 7912fa5a2efe36f7de44740f8a2c4970',1,110),(265,'false answer: 52744a109ae9a7630e2bd424403030d9',0,110),(266,'false answer: 67fcf5803f72b58d14f3946f672a9949',0,111),(267,'false answer: 0fc2a5c7fe71914d49c6950ab188f484',0,111),(268,'false answer: 3c6506b4908d41af562ac0e610d4c5ca',0,111),(269,'false answer: 90fa5ceb660ebc8078e38ff7b507a082',0,112),(270,'true answer: 39af017bf9e2913a6ec6fe6f3d7d91b7',1,112),(271,'true answer: f0137faf39cddcb93eafea620bda351f',1,112),(272,'true answer: 5e9ccceed535d00135dbe7f7acb22dac',1,113),(273,'true answer: 7e6a4f97c131657b183fb5c15d4a0f38',1,113),(274,'true answer: c4176bdfb7590a1bbbe73a548c0a2d44',1,113),(275,'false answer: 581a08bcee435aca1b0fc26121df5480',0,114),(276,'true answer: c587950215fdab73ac3d618a1a5bc5e9',1,114),(277,'true answer: d5d076e048cbc363fb0c0696316c3a84',1,114),(278,'false answer: e5140d9fb3c061c239f5818ab8c3cd2c',0,115),(279,'true answer: 9991084d75a9ad52245af90c2d4461a2',1,115),(280,'false answer: ae0bc16cfcee8c80b545e028ddc0b700',0,115),(281,'true answer: f8280405fae442390dde73793328139b',1,116),(282,'false answer: b6bb59a313b3b6da640e36b6db43d5d6',0,116),(283,'true answer: c5cabdfcb5346f1e5811c4ec1b5d2b97',1,116),(284,'true answer: ddb1187c9ae100a52e4cd5d5a4f48b21',1,117),(285,'false answer: bc120d7ade38842535f3f5d50c7bb6e6',0,117),(286,'false answer: 21a942b7d23f8fd9cb2468faefd987c7',0,117),(287,'true answer: 54a8b1b0f099c76ed40fcc13d2d31b5c',1,118),(288,'true answer: ae9f8f0c5c92845cd891516c4cea7ccf',1,118),(289,'false answer: 84ec048b62f69ce490b6a996d036ece0',0,118),(290,'false answer: 84ae09c713c43d2c4799af25dc965724',0,119),(291,'false answer: f5c1fc3548126cb6491e363bfad6e72c',0,119),(292,'false answer: 2c1b5ef496806bb08d07c45265923e95',0,119),(293,'false answer: 59819a700706aa88c1e95176058d4411',0,120),(294,'true answer: 9bf8765ff0515fc690c3b02639596deb',1,120),(295,'true answer: 24dcac89deb2f67f05b2b180a67487e1',1,120),(296,'true answer: f1eda915bd9676d591073bc24d027517',1,121),(297,'true answer: b166954e7932fe2a0333d5b2b0c23278',1,121),(298,'true answer: 9bee35262224dbe081063bbde8626493',1,121),(299,'false answer: b9386b7855358a19b1434c7faaaf11ef',0,122),(300,'true answer: 3b2f7c5f6f4c409a8377645b1373f8a6',1,122),(301,'true answer: 83c82632e25e4fb65164090ec592cef9',1,122),(302,'true answer: e4f4ff353740ad04329bbb9aa4d52731',1,123),(303,'true answer: e3fa1a86e3c0e775a5df516e21bf3bc2',1,123),(304,'false answer: a68a79192282309695e8bcf1a6c055de',0,123),(305,'true answer: dd0fe5177ca8d9899082fd494d0403da',1,124),(306,'false answer: 058f4c0c9c7dae687584f7e3f92a0550',0,124),(307,'false answer: a9a81fcdf617140f1c0bcc872370e335',0,124),(308,'true answer: 3245392abafaba6ab4f15649d56ac208',1,125),(309,'false answer: 97395a8b179de21c326729547cfd9bcd',0,125),(310,'false answer: 9f0b99b74ea289b42426afbb27dad804',0,125),(311,'false answer: 9eb318b4a462f54a98c58509437b6676',0,126),(312,'true answer: eac06241b58ea42057d7f8e700997e3c',1,126),(313,'false answer: eee9465a2cce5b99cf4b49827ec366bd',0,126),(314,'true answer: 7a7fef147bda7599187dc322e65804da',1,127),(315,'false answer: 1c6473f2d168e844f78bdc8bfd0dae9e',0,127),(316,'false answer: 44515c32c0186ae75942648c32c11a6b',0,127),(317,'false answer: e557d74ee0a02360a5e293c94c04ee7b',0,128),(318,'false answer: 5c4c9d460bdc084000e8c03ea344af70',0,128),(319,'true answer: 32757ffdba9201290e4838fb0802f2e0',1,128),(320,'false answer: b11b24d8daeb423a1294696361800ca9',0,129),(321,'false answer: fb9a24e172f89418f4ee2fc21b08ba41',0,129),(322,'true answer: fb992903f9121f64f98caafd51b7a490',1,129),(323,'false answer: 6730884771b0c41d3c607af4a8f31917',0,130),(324,'true answer: 635b938aa43be3688f7749f7d4cc0283',1,130),(325,'false answer: 27dd9f7ed7aed84ac2797e310b9014cf',0,130),(326,'true answer: 3c6ef4b79fad60fb40832adbd2550e2b',1,131),(327,'false answer: 75a9be13d2365ce6f38e9c1946597946',0,131),(328,'false answer: 084c030bc8906d233a56bb664cb676aa',0,131),(329,'false answer: b8936e079b53be7d4d3e92d4aaabdf71',0,132),(330,'true answer: c1fd343ca43b927a391b23f6058bec1a',1,132),(331,'false answer: f534f27542ed757cc12b84c921de689f',0,132),(332,'true answer: 711e8a1929fb82bf93068618a7940b84',1,133),(333,'false answer: 6870e70580112db589842fc2b163508a',0,133),(334,'false answer: 55bedea1e61507f12abf39d695679531',0,133),(335,'true answer: 01dfb6f033c0ee228282b1f2cb7034aa',1,134),(336,'false answer: 381ce7ec57887a5fc50d9250210b3875',0,134),(337,'false answer: 4553d6b21bd7426ade704afa97e2e2a6',0,134),(338,'false answer: e4dd8a6c92b88f51f47d3444605bdda5',0,135),(339,'false answer: 5860cc7dc4a37122a7a466c3bba2d3ae',0,135),(340,'true answer: e04cc574ed1694d5b32dc37b271f6e4d',1,135),(341,'false answer: e5fb069da6df0c80ffce79631700afe1',0,136),(342,'false answer: 73fa5fdea29b7f6e1a6da0864d6d012d',0,136),(343,'true answer: 877822b1a32d2cd9926587cd1588ae7f',1,136),(344,'false answer: e04952dbeddf5c0975c33e538d2bf3d5',0,137),(345,'false answer: e811d66f0ba16c5e40e12c4ab5c649bf',0,137),(346,'true answer: 720a9deb9e95e5ec719192facdaaf652',1,137),(347,'false answer: 8545d59b5994156d723b1552b0e666cd',0,138),(348,'false answer: 9f356c7c58779b2798394651535e0369',0,138),(349,'true answer: 3b86d79f46210d7a3685ab424ccdc04a',1,138),(350,'false answer: 1961ff2f29b5c01199c3eb4a7ce05921',0,139),(351,'true answer: 357dd86977ff0afa158c821465966627',1,139),(352,'true answer: 35dafdd17094b9266f02d371feeaea46',1,139),(353,'false answer: 8e0fc04353fec67b0dc4b5dc322dfe15',0,140),(354,'false answer: f41df281063f7c05e2da522b1aa18c65',0,140),(355,'false answer: 6cf19726e1c30bc8637b12eeee2a7d5b',0,140),(356,'false answer: 3dc0e497c6f2c8290734624882ca228e',0,141),(357,'true answer: f36bdf3e026fa2f696b5d197bd950cf2',1,141),(358,'false answer: f99ccff36222997f6bbdaad5ad7c4d95',0,141),(359,'false answer: f36bdf3e026fa2f696b5d197bd950cf2',0,142),(360,'false answer: df6e96787b6d406adf5c9a5aa8747fcc',0,142),(361,'false answer: 9d4f2d653bdc9cc55767f56d08ed58ec',0,142),(362,'false answer: 565be4debb31cd4daf1d8d5af4ac6b7c',0,143),(363,'false answer: f36bdf3e026fa2f696b5d197bd950cf2',0,143),(364,'true answer: 496b4b8e764e8a3e24f0114859337945',1,143),(365,'true answer: ff3c3e1598921c5736f1111e0359c58c',1,144),(366,'false answer: afab95c12b3294ddf552cd82121b00d3',0,144),(367,'true answer: 9486d1790bebdd7eaa87b96dc273f34f',1,144),(368,'true answer: 7400ac2b0c36ae2d53d526cc008e287d',1,145),(369,'false answer: bea9198af570f826e9daf23dc1eb0298',0,145),(370,'true answer: daf46ae8e9496e7d518f627d40f5ef13',1,145),(371,'false answer: 1a4c959f63c8a5dd9a55989c67b3e379',0,146),(372,'true answer: 2aaff95e8402994be2611554b20ec5ff',1,146),(373,'false answer: 2d94474b0ba75377b8563c2941e03b88',0,146),(374,'false answer: 67fcf5803f72b58d14f3946f672a9949',0,147),(375,'false answer: 14a2cb173977a6c4aae8163eefa91fa6',0,147),(376,'false answer: 3c6506b4908d41af562ac0e610d4c5ca',0,147),(377,'false answer: 881c862b0db99de151850eb0f2bfa3cf',0,148),(378,'true answer: 9991084d75a9ad52245af90c2d4461a2',1,148),(379,'false answer: ae0bc16cfcee8c80b545e028ddc0b700',0,148),(380,'true answer: ddb1187c9ae100a52e4cd5d5a4f48b21',1,149),(381,'false answer: bc120d7ade38842535f3f5d50c7bb6e6',0,149),(382,'false answer: 82d471469a5abd0029225ae4de3d4dac',0,149),(383,'false answer: b9386b7855358a19b1434c7faaaf11ef',0,150),(384,'true answer: 3b2f7c5f6f4c409a8377645b1373f8a6',1,150),(385,'true answer: 83c82632e25e4fb65164090ec592cef9',1,150),(386,'false answer: 581a08bcee435aca1b0fc26121df5480',0,151),(387,'true answer: c587950215fdab73ac3d618a1a5bc5e9',1,151),(388,'true answer: a65131329f5df009bbde0b7a86b193e1',1,151),(389,'false answer: 881c862b0db99de151850eb0f2bfa3cf',0,152),(390,'true answer: 9991084d75a9ad52245af90c2d4461a2',1,152),(391,'false answer: ae0bc16cfcee8c80b545e028ddc0b700',0,152);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_language`
--

LOCK TABLES `sqms_language` WRITE;
/*!40000 ALTER TABLE `sqms_language` DISABLE KEYS */;
INSERT INTO `sqms_language` VALUES (1,'deutsch','DE'),(2,'englisch','EN'),(3,'französisch','FR'),(4,'spanisch','ES');
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
  CONSTRAINT `fk_sqms_question_sqms_question_type1` FOREIGN KEY (`sqms_question_type_id`) REFERENCES `sqms_question_type` (`sqms_question_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_2` FOREIGN KEY (`sqms_language_id`) REFERENCES `sqms_language` (`sqms_language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sqms_question_ibfk_4` FOREIGN KEY (`sqms_topic_id`) REFERENCES `sqms_topic` (`sqms_topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question`
--

LOCK TABLES `sqms_question` WRITE;
/*!40000 ALTER TABLE `sqms_question` DISABLE KEYS */;
INSERT INTO `sqms_question` VALUES (34,1,1,'this is a question: eb432fe1c698de40d671cfd664d4a35b?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(35,1,3,'this is a question: 465de7591113e7d31b93cef499d8afa8?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(36,1,3,'this is a question: 445beade0fed82e7cea7bae368b759cb?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(37,1,3,'this is a question: 3fe2fe041317a4d8e864f6fc00b9d8af?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(38,1,1,'this is a question: 4219b785beee9f216cea5eff8a502cf8?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(39,1,1,'this is a question: 8d43b3f3e5551745f51627459c8e52c9?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(40,1,3,'this is a question: 475118b4abe5af7693286dc9e1c339c3?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(41,1,3,'this is a question: bcf0aba07015a9cb808b7533ed234597?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(42,1,3,'this is a question: c8dbf70a7b0d7e1fdc038d516035d0e3?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(43,1,1,'this is a question: 9151b7fe4fa57270c004ce02bd70c7df?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(44,1,1,'this is a question: b8e33f53250e73c0035c15009f007ee9?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(45,1,3,'this is a question: 13b52e5069681fb6049269b692bb9a20?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(46,1,3,'this is a question: 3b24978f733ce02afc45d63a10d695fa?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(47,1,3,'this is a question: cd9f0aec95460e7c62a2ef09a9ca523c?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,140,1,11),(48,1,3,'this is a question: 9cb233749c659c69b32bab914259750c?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,141,1,11),(49,1,3,'this is a question: ab3eef2bfb0bc43e8765545985a041b9?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,142,1,11),(50,1,3,'this is a question: 7d66201ae91ffe2960f3d33661f3c861?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,143,2,11),(51,1,1,'this is a question: 53f9254a41ed45b4ffbeee4e4f94d4b4?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(52,1,3,'this is a question: a15264b84be49ace90df94748bd5aa57?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(53,1,1,'this is a question: a15264b84be49ace90df94748bd5aa57?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(54,1,3,'this is a question: 351d5c7fcccd8d43b50331d6fcd06f11?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(55,1,1,'this is a question: c24f3bdeb7ca0d49e56f6b05a5116d4f?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(56,1,1,'this is a question: c24f3bdeb7ca0d49e56f6b05a5116d4f?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(57,1,3,'this is a question: e386c622751a1ce065e848bfa91e64c7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(58,1,3,'this is a question: 933049c058cfdcfe2b86294b21f3e37d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(59,1,1,'this is a question: c8e67a3eeb00a7798d64d7d265cc700a?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(60,1,3,'this is a question: 7d99f97f173e59057df679bdbc4ab42b?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(61,1,3,'this is a question: 330309df5ccf904578fa9cc3900b9000?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(62,1,3,'this is a question: f619b27e36a1db343ef6db0e955285f7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(63,1,3,'this is a question: e1d0ba768ae1b8f65b653a9aa6487e64?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(64,1,3,'this is a question: 70a9b5e1b6754246a7b0badc50abb299?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,144,1,11),(65,1,1,'this is a question: f4ca516af1d53ed226b00d9898e7c284?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(66,1,1,'this is a question: 511ca4a487b461a142867a674e310ed7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(67,1,1,'this is a question: b51f8b783f185594e7ae634c791e3df3?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(68,1,1,'this is a question: c590e0116f3c4b1868e5635999fe524e?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(69,1,1,'this is a question: 6433a7720128cae8c6437a98d4db1330?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(70,1,1,'this is a question: 521931a99011e070a07ba11dabce1ad7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(71,1,3,'this is a question: 521931a99011e070a07ba11dabce1ad7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(72,1,1,'this is a question: 663a674559a763797747d52cfbe38801?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(73,1,3,'this is a question: 6b2cc810871b758cf60b61287f9efd6d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(74,1,3,'this is a question: 6b2cc810871b758cf60b61287f9efd6d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(75,1,1,'this is a question: 6b2cc810871b758cf60b61287f9efd6d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(76,1,1,'this is a question: f7e6c7dcf0e72683cae1a9ffbfa111e3?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(77,1,1,'this is a question: 8a630ee6e58b2c27201e815ef202a704?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(78,1,1,'this is a question: 15f85af4d71ca70f256998169dd607ae?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(79,1,1,'this is a question: 15f85af4d71ca70f256998169dd607ae?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(80,1,1,'this is a question: da4a81a08ce9e85eb0115f086e135273?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(81,1,1,'this is a question: 07aed17e954ab28462d4c5d7ed1a25f4?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(82,1,3,'this is a question: 141cff9c8a5ede541f9e46504ca1dd4c?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,139,2,11),(83,1,3,'this is a question: 141cff9c8a5ede541f9e46504ca1dd4c?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(84,1,3,'this is a question: 0b50fb1483c51d751e6da853c29b5958?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(85,1,3,'this is a question: 0b50fb1483c51d751e6da853c29b5958?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(86,1,3,'this is a question: c28cf0d4f512dfe8da9e1eb8f81a90a7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(87,1,1,'this is a question: c28cf0d4f512dfe8da9e1eb8f81a90a7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(88,1,3,'this is a question: fc39afdff3e9f065fa3e25e51a8e1776?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(89,1,3,'this is a question: fc39afdff3e9f065fa3e25e51a8e1776?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(90,1,3,'this is a question: 87d6c506406de3f5f51d0022a5bbc1fa?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(91,1,3,'this is a question: 12e82a9d30dbfa248c330b67319f2550?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(92,1,3,'this is a question: d208f4120e68fb437df116154044e532?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(93,1,3,'this is a question: 56c19d755b5f91c8373f4ef92ae15bb7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(94,1,3,'this is a question: 80b687fcb7032c6ffb7597b76b2f14f0?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(95,1,3,'this is a question: 02d3e1f28b610761f06aed1743952be2?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(96,1,1,'this is a question: af52c04c4aeec5c38a7f30e31df68deb?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(97,1,3,'this is a question: af52c04c4aeec5c38a7f30e31df68deb?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,145,1,11),(98,1,3,'this is a question: 56747ef501d88f7ccaf53923cbcb9c8f?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(99,1,3,'this is a question: 56747ef501d88f7ccaf53923cbcb9c8f?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(100,1,3,'this is a question: 6a59ae39f7aac141d5b70ee8254fc662?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(101,1,3,'this is a question: 48ca0291ace940afc5d48bdda26c1508?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,146,1,11),(102,1,3,'this is a question: 52c14f3cd8d879bcdf9bf73696df4c99?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(103,1,3,'this is a question: 48b3e8974f86cd0d65f98944152fe0c5?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(104,1,3,'this is a question: 54f268d33faea65a9e735e1f0b502d7f?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(105,1,3,'this is a question: fe56a63566a61b9c2f2d956908451180?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(106,1,3,'this is a question: 7c6c01db0f2cf2fb029dcef5b176a599?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(107,1,1,'this is a question: 51d6227a22acb66c9db9386229eee277?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(108,1,3,'this is a question: f4fbc54a0b94db2cc2eeae61d024fbfe?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(109,1,1,'this is a question: c46cd2027acb1dbee977944d7dd4c46a?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(110,1,3,'this is a question: afad75102fb78bfa485d010d523f0116?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(111,1,3,'this is a question: afad75102fb78bfa485d010d523f0116?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,147,1,11),(112,1,3,'this is a question: 275905d50eddafe830950875b899f51d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(113,1,1,'this is a question: ad442e7594b43fa446ea8268801369e2?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(114,1,3,'this is a question: 15b739f0cfdd215ca97dc14264354ebe?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,151,2,11),(115,1,3,'this is a question: 5f8c16c5d927a0f15d0c77d68f574007?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,148,1,11),(116,1,3,'this is a question: b22a50e0f27ffaeb1b2b60a001e7a148?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(117,1,3,'this is a question: 4f6cf071a92be1292ec65289843e0a96?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,149,1,11),(118,1,3,'this is a question: 0eac8e81507cc8aa07b4c47d71f40a29?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(119,1,3,'this is a question: 0eac8e81507cc8aa07b4c47d71f40a29?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(120,1,3,'this is a question: 0eac8e81507cc8aa07b4c47d71f40a29?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,2,11),(121,1,3,'this is a question: 0eac8e81507cc8aa07b4c47d71f40a29?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(122,1,3,'this is a question: e7989f8e4368f68793c62f4b5b56ff70?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,150,1,11),(123,1,1,'this is a question: e7989f8e4368f68793c62f4b5b56ff70?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,3,11),(124,1,3,'this is a question: b034415607263a9cc6438456f39beb1d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(125,1,3,'this is a question: 12b6bdeaab465fc7f71c1a8003e8b02d?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(126,1,3,'this is a question: da4eb2a7a8458aa4262e46eecc1af7a3?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(127,1,3,'this is a question: 9d8f22f01df081f4cf3c545074cb8746?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(128,1,3,'this is a question: d992f3c1477632e7d61e6189b7adf071?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(129,1,3,'this is a question: c906a154b6f3ca300e738753e63962ee?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(130,1,3,'this is a question: 9098237e66855af9421afde71042c269?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(131,1,3,'this is a question: 883c5f194f7e7d888101deaba86a1423?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(132,1,3,'this is a question: 6fab3b44e7c3d61050d2e7d633819ff7?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(133,1,3,'this is a question: d596565e7fd280bc136bf93b2a0e3456?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(134,1,3,'this is a question: 0f3deaf24237c0d477fe8c1126350f87?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(135,1,3,'this is a question: 04d7ae8c704a61843cde4dadddb29761?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(136,1,3,'this is a question: bf6dfd411064490acfe3be9ce307cecc?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(137,1,3,'this is a question: 340bb5968e6f81a3f095411b90b221ca?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(138,1,3,'this is a question: 09a0cb3bb66460c6c6a05e355ced6368?','4d78e8860d0e15a899dfe1fa7cab8a7f',1,'',0,0,1,11),(139,1,1,'this is a question: 141cff9c8a5ede541f9e46504ca1dd4c?','c9e1074f5b3f9fc8ea15d152add07294',2,'',82,0,2,11),(140,1,3,'this is a question: cd9f0aec95460e7c62a2ef09a9ca523c?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',47,0,1,11),(141,1,3,'this is a question: 819eba126465b6d57b0552d7f062dab3?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',48,0,1,11),(142,1,3,'this is a question: 538b02a0f7f93656c4cf87a8e991dce2?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',49,0,1,11),(143,1,3,'this is a question: 9abd7d6762ce1e1ec76b2332d60c5a2d?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',50,0,2,11),(144,1,3,'this is a question: 70a9b5e1b6754246a7b0badc50abb299?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',64,0,1,11),(145,1,3,'this is a question: af52c04c4aeec5c38a7f30e31df68deb?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',97,0,1,11),(146,1,2,'this is a question: 1c5071c0497fe7ece96a312749dc179c?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',101,0,1,11),(147,1,3,'this is a question: afad75102fb78bfa485d010d523f0116?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',111,0,1,11),(148,1,3,'this is a question: 5f8c16c5d927a0f15d0c77d68f574007?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',115,152,1,11),(149,1,3,'this is a question: 4f6cf071a92be1292ec65289843e0a96?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',117,0,1,11),(150,1,3,'this is a question: 2b580dc6f3973e3c7c1fb2b2299097c0?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',122,0,1,11),(151,1,2,'this is a question: 15b739f0cfdd215ca97dc14264354ebe?','4d78e8860d0e15a899dfe1fa7cab8a7f',2,'',114,0,2,11),(152,1,4,'this is a question: 5f8c16c5d927a0f15d0c77d68f574007?','4d78e8860d0e15a899dfe1fa7cab8a7f',3,'',148,0,1,11);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_reviewer`
--

LOCK TABLES `sqms_question_reviewer` WRITE;
/*!40000 ALTER TABLE `sqms_question_reviewer` DISABLE KEYS */;
INSERT INTO `sqms_question_reviewer` VALUES ('336ff4fc2d03661707a486e0296103fa',35),('f0f05a97b70106c9adde873a975cc185',36),('336ff4fc2d03661707a486e0296103fa',37),('f0f05a97b70106c9adde873a975cc185',40),('336ff4fc2d03661707a486e0296103fa',41),('336ff4fc2d03661707a486e0296103fa',42),('f0f05a97b70106c9adde873a975cc185',45),('336ff4fc2d03661707a486e0296103fa',46),('f0f05a97b70106c9adde873a975cc185',47),('f0f05a97b70106c9adde873a975cc185',48),('f0f05a97b70106c9adde873a975cc185',49),('336ff4fc2d03661707a486e0296103fa',50),('336ff4fc2d03661707a486e0296103fa',52),('336ff4fc2d03661707a486e0296103fa',54),('336ff4fc2d03661707a486e0296103fa',57),('f0f05a97b70106c9adde873a975cc185',58),('336ff4fc2d03661707a486e0296103fa',60),('336ff4fc2d03661707a486e0296103fa',61),('336ff4fc2d03661707a486e0296103fa',62),('f0f05a97b70106c9adde873a975cc185',63),('f0f05a97b70106c9adde873a975cc185',64),('f0f05a97b70106c9adde873a975cc185',71),('f0f05a97b70106c9adde873a975cc185',73),('336ff4fc2d03661707a486e0296103fa',74),('336ff4fc2d03661707a486e0296103fa',82),('f0f05a97b70106c9adde873a975cc185',83),('336ff4fc2d03661707a486e0296103fa',84),('f0f05a97b70106c9adde873a975cc185',85),('336ff4fc2d03661707a486e0296103fa',86),('336ff4fc2d03661707a486e0296103fa',88),('f0f05a97b70106c9adde873a975cc185',89),('336ff4fc2d03661707a486e0296103fa',90),('f0f05a97b70106c9adde873a975cc185',91),('336ff4fc2d03661707a486e0296103fa',92),('f0f05a97b70106c9adde873a975cc185',93),('336ff4fc2d03661707a486e0296103fa',94),('f0f05a97b70106c9adde873a975cc185',95),('f0f05a97b70106c9adde873a975cc185',97),('336ff4fc2d03661707a486e0296103fa',98),('f0f05a97b70106c9adde873a975cc185',99),('336ff4fc2d03661707a486e0296103fa',100),('f0f05a97b70106c9adde873a975cc185',101),('336ff4fc2d03661707a486e0296103fa',102),('f0f05a97b70106c9adde873a975cc185',103),('336ff4fc2d03661707a486e0296103fa',104),('f0f05a97b70106c9adde873a975cc185',105),('f0f05a97b70106c9adde873a975cc185',106),('336ff4fc2d03661707a486e0296103fa',108),('336ff4fc2d03661707a486e0296103fa',110),('f0f05a97b70106c9adde873a975cc185',111),('f0f05a97b70106c9adde873a975cc185',112),('336ff4fc2d03661707a486e0296103fa',114),('f0f05a97b70106c9adde873a975cc185',115),('336ff4fc2d03661707a486e0296103fa',116),('f0f05a97b70106c9adde873a975cc185',117),('336ff4fc2d03661707a486e0296103fa',118),('f0f05a97b70106c9adde873a975cc185',119),('336ff4fc2d03661707a486e0296103fa',120),('f0f05a97b70106c9adde873a975cc185',121),('f0f05a97b70106c9adde873a975cc185',122),('336ff4fc2d03661707a486e0296103fa',124),('336ff4fc2d03661707a486e0296103fa',125),('336ff4fc2d03661707a486e0296103fa',126),('336ff4fc2d03661707a486e0296103fa',127),('336ff4fc2d03661707a486e0296103fa',128),('336ff4fc2d03661707a486e0296103fa',129),('336ff4fc2d03661707a486e0296103fa',130),('336ff4fc2d03661707a486e0296103fa',131),('336ff4fc2d03661707a486e0296103fa',132),('336ff4fc2d03661707a486e0296103fa',133),('336ff4fc2d03661707a486e0296103fa',134),('336ff4fc2d03661707a486e0296103fa',135),('336ff4fc2d03661707a486e0296103fa',137),('336ff4fc2d03661707a486e0296103fa',138),('f0f05a97b70106c9adde873a975cc185',139),('f0f05a97b70106c9adde873a975cc185',140),('f0f05a97b70106c9adde873a975cc185',141),('f0f05a97b70106c9adde873a975cc185',142),('336ff4fc2d03661707a486e0296103fa',143),('f0f05a97b70106c9adde873a975cc185',143),('f0f05a97b70106c9adde873a975cc185',144),('f0f05a97b70106c9adde873a975cc185',145),('f0f05a97b70106c9adde873a975cc185',146),('f0f05a97b70106c9adde873a975cc185',147),('f0f05a97b70106c9adde873a975cc185',148),('f0f05a97b70106c9adde873a975cc185',149),('f0f05a97b70106c9adde873a975cc185',150),('f0f05a97b70106c9adde873a975cc185',152);
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
  PRIMARY KEY (`sqms_question_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_state`
--

LOCK TABLES `sqms_question_state` WRITE;
/*!40000 ALTER TABLE `sqms_question_state` DISABLE KEYS */;
INSERT INTO `sqms_question_state` VALUES (1,'new'),(2,'ready'),(3,'released'),(4,'deprecated');
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
  PRIMARY KEY (`sqms_question_state_rules_id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  UNIQUE KEY `id_UNIQUE` (`sqms_question_state_rules_id`),
  KEY `fk_sqms_question_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_question_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_question_state` (`sqms_question_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_question_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_question_state` (`sqms_question_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_state_rules`
--

LOCK TABLES `sqms_question_state_rules` WRITE;
/*!40000 ALTER TABLE `sqms_question_state_rules` DISABLE KEYS */;
INSERT INTO `sqms_question_state_rules` VALUES (6,3,4),(10,2,1),(11,2,3),(12,2,4),(13,1,2),(14,1,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_question_type`
--

LOCK TABLES `sqms_question_type` WRITE;
/*!40000 ALTER TABLE `sqms_question_type` DISABLE KEYS */;
INSERT INTO `sqms_question_type` VALUES (1,'Muster','Fragen diesen Typs dienen zur Übung und dürfen veröffentlicht werden.'),(2,'Prüfung','Fragen diesen Typs sind für den Leistungstest angedacht und dürfen nicht veröffentlicht werden.'),(3,'Special','Test');
/*!40000 ALTER TABLE `sqms_question_type` ENABLE KEYS */;
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
INSERT INTO `sqms_syllabus` VALUES (104,'38bd01e5e83e1871245c68ea4ca0dae8',1,1,11,'4d78e8860d0e15a899dfe1fa7cab8a7f',1,0,0,'2015-09-13',NULL,'<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit ame ,vmmbnt. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\n<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>\n<p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer</p>');
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
INSERT INTO `sqms_syllabus_element` VALUES (50,1,10.00,104,'833f55360184add3966a7410b2d11c2b','this is a description',0,0),(51,4,20.00,104,'0ac6236ea418c7d0c7237712472fcd84','this is a description',0,0),(52,3,45.00,104,'57087f82acbea801c02300b2ad2512ae','this is a description',0,0),(55,0,5.00,104,'08f2fcdfb9fa414052f657bd9700ba0a','<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\n<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>\n<p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer</p>',0,0),(57,5,20.00,104,'c626722b8fec0242ce64f63d3384f715','this is a description',0,0);
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
INSERT INTO `sqms_syllabus_element_question` VALUES (12,38,50),(14,39,50),(107,68,50),(111,70,50),(115,72,50),(122,75,50),(124,76,50),(128,78,50),(130,79,50),(136,66,52),(140,55,51),(141,56,51),(146,69,50),(170,53,50),(172,59,50),(178,80,52),(180,81,52),(181,65,52),(204,87,52),(244,107,52),(248,109,51),(257,113,51),(278,123,57),(289,43,52),(290,44,51),(303,67,50),(318,77,50),(319,34,50),(351,61,51),(352,62,52),(353,46,57),(354,57,50),(355,37,50),(356,100,52),(357,116,51),(358,98,52),(359,118,57),(360,120,57),(361,42,51),(362,52,52),(363,54,57),(364,94,52),(365,60,50),(366,41,51),(367,50,57),(368,90,52),(369,92,52),(370,88,52),(371,114,51),(372,102,52),(373,35,50),(374,104,52),(375,110,51),(377,86,52),(378,82,52),(379,84,52),(380,108,51),(381,74,50),(446,126,52),(447,125,51),(448,124,52),(449,136,50),(450,138,52),(451,134,50),(452,132,51),(453,130,52),(455,133,50),(456,128,52),(457,127,52),(458,137,50),(459,131,52),(460,135,50),(461,129,52),(531,47,57),(532,45,51),(533,63,52),(534,40,51),(535,122,57),(536,97,52),(537,64,52),(538,117,51),(539,99,52),(540,95,52),(541,119,57),(542,121,57),(543,58,52),(544,101,52),(545,49,57),(546,48,57),(547,91,52),(548,93,52),(549,89,52),(550,105,52),(551,115,51),(552,103,52),(553,111,51),(555,83,52),(556,106,52),(557,36,50),(558,85,52),(559,112,51),(560,73,50),(561,71,50),(562,139,50),(565,51,57),(571,96,52),(578,140,57),(579,141,57),(580,142,57),(582,143,57),(589,150,57),(590,151,51),(592,144,52),(594,148,51),(596,152,51),(597,145,52),(598,146,52),(599,147,51),(600,149,51);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
  PRIMARY KEY (`sqms_syllabus_state_id`),
  UNIQUE KEY `id_UNIQUE` (`sqms_syllabus_state_id`),
  UNIQUE KEY `state_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_state`
--

LOCK TABLES `sqms_syllabus_state` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_state` DISABLE KEYS */;
INSERT INTO `sqms_syllabus_state` VALUES (1,'new',10),(2,'ready',80),(3,'released',100),(4,'deprecated',999);
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
  PRIMARY KEY (`sqms_syllabus_state_rules_id`,`sqms_state_id_FROM`,`sqms_state_id_TO`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state1_idx` (`sqms_state_id_FROM`),
  KEY `fk_sqms_syllabus_state_rules_sqms_state2_idx` (`sqms_state_id_TO`),
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state1` FOREIGN KEY (`sqms_state_id_FROM`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sqms_syllabus_state_rules_sqms_state2` FOREIGN KEY (`sqms_state_id_TO`) REFERENCES `sqms_syllabus_state` (`sqms_syllabus_state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_syllabus_state_rules`
--

LOCK TABLES `sqms_syllabus_state_rules` WRITE;
/*!40000 ALTER TABLE `sqms_syllabus_state_rules` DISABLE KEYS */;
INSERT INTO `sqms_syllabus_state_rules` VALUES (19,1,2),(20,1,4),(16,2,1),(17,2,3),(18,2,4),(12,3,4);
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
  PRIMARY KEY (`sqms_topic_id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `id_UNIQUE` (`sqms_topic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sqms_topic`
--

LOCK TABLES `sqms_topic` WRITE;
/*!40000 ALTER TABLE `sqms_topic` DISABLE KEYS */;
INSERT INTO `sqms_topic` VALUES (11,'008809e'),(10,'1fdd4a37'),(9,'63d34be');
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

-- Dump completed on 2016-01-15 16:42:41
