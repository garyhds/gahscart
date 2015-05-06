-- MySQL dump 10.13  Distrib 5.1.69, for redhat-linux-gnu (x86_64)
--
-- Host: sql.useractive.com    Database: ghornbec
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1-log

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
-- Table structure for table `scart_customers`
--

DROP TABLE IF EXISTS `scart_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scart_customers` (
  `custid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_fname` varchar(12) NOT NULL,
  `cust_lname` varchar(12) NOT NULL,
  `cust_address` varchar(40) DEFAULT NULL,
  `cust_city` varchar(30) NOT NULL,
  `cust_state` varchar(20) NOT NULL,
  `cust_zip` varchar(10) NOT NULL,
  `cust_phone` varchar(20) DEFAULT NULL,
  `cust_email` varchar(25) DEFAULT NULL,
  `cust_status` enum('active','inactive','manager') DEFAULT NULL,
  `cust_userid` varchar(12) NOT NULL,
  `cust_passwd` varchar(12) DEFAULT NULL,
  `cust_stamp` datetime DEFAULT NULL,
  PRIMARY KEY (`custid`),
  UNIQUE KEY `cust_userid` (`cust_userid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scart_customers`
--

LOCK TABLES `scart_customers` WRITE;
/*!40000 ALTER TABLE `scart_customers` DISABLE KEYS */;
INSERT INTO `scart_customers` VALUES (17,'Bill','Reissyner','425 East Hunting Park Avenue','Philadelphia','PA','19124',NULL,NULL,'active','breissyn',NULL,'2013-12-18 18:00:00'),(16,'Sheri','Burg','NEED Address','NEED City','NEED State','NEED Zip',NULL,NULL,'inactive','sburg',NULL,'2013-12-03 18:00:00'),(15,'Mir','Ali','2707 E. Thousand Oaks Blvd','Thousand Oaks, CA 91362','CA','91362',NULL,NULL,'inactive','mali',NULL,'2007-05-28 18:00:00'),(14,'Toby','Oliver','14132 Firestone Blvd','Santa Fe Springs','CA','90670',NULL,'','active','toliver',NULL,'2007-04-18 18:00:00'),(13,'Tony','Panzica','800 Avenida de Los Arboles','Thousand Oaks','CA','91360',NULL,NULL,'active','tpanzica',NULL,'2007-04-18 18:00:00'),(12,'Kate','Olson','21 Queen Street','Auckland','New Zealand','','805-555-1213','kate.o@somewhere.com','active','kolson','password','2007-02-03 18:00:00'),(11,'Tony','Falato','1724 E Avenida De Los Arboles, #H','Thousand Oaks','CA','93162','805-555-1212','tony.f@somewhere.com','active','tfalato','password','2007-05-28 18:00:00'),(18,'Eric','Groenendyk','40 Niles Street','Loma Linda','CA','92354',NULL,NULL,'active','egroenen',NULL,'2007-04-18 18:00:00'),(19,'Virginia','Walker','Need Address','Tulsa','OK','74169-0360',NULL,NULL,'manager','vwalker','manager','2007-02-05 18:00:00'),(20,'Silvia','Scally','1000 Business Center Circle, #100','Thousand Oaks','CA','91320',NULL,NULL,'inactive','sscally',NULL,'2007-05-07 18:00:00'),(21,'Jethro','Gibbs','14132 Firestone Blvd','Thousand Oaks','California','91321','805-555-1212','fullname@somewhere.com','active','jgibbs','password','2014-01-04 00:28:51'),(23,'Gary','Hornbeck','1001 San Clemente Way','Camarillo','California','93010','805-388-8623','garyh.ds@gmail.com','active','ghornbec','password','2014-02-23 13:39:46'),(24,'First','Finallastnam','14132 Final Firestone Blvd','Thousand Oaks','California','94169-0360','805-555-1212','fullname@somewhere.com','active','ffinalla','password','2014-06-08 12:32:36'),(25,'Second','Finallastnam','14132 Final Firestone Blvd','Thousand Oaks','California','94169-0360','805-555-1212','fullname@wherever.com','active','sfinalla','password','2014-06-08 12:55:46'),(29,'Ari','Chou','123 Sesame St.','Placeville','Maryland','12345','2342342345','fake','active','achou','testtest','2015-02-17 14:11:52'),(28,'Third','Finallbstnam','14132 Final Firestone Blvd','Thousand Oaks','California','94169-0360','805-555-1212','garyh.ds@gmail.com','active','tfinallb','password','2015-02-09 15:41:56');
/*!40000 ALTER TABLE `scart_customers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-09 17:34:57
