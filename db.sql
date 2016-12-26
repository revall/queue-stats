-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: asterisk
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

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
-- Table structure for table `cdr`
--

DROP TABLE IF EXISTS `cdr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cdr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `calldate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `clid` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `src` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dst` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dcontext` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastapp` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastdata` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `duration` float unsigned DEFAULT NULL,
  `billsec` float unsigned DEFAULT NULL,
  `disposition` enum('ANSWERED','BUSY','FAILED','NO ANSWER','CONGESTION') COLLATE utf8_bin DEFAULT NULL,
  `channel` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `dstchannel` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `amaflags` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `accountcode` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `uniqueid` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `userfield` float unsigned DEFAULT NULL,
  `answer` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `src` (`src`),
  KEY `dcontext` (`dcontext`),
  KEY `clid` (`clid`)
) ENGINE=InnoDB AUTO_INCREMENT=15589 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `queue_log`
--

DROP TABLE IF EXISTS `queue_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue_log` (
  `time` varchar(32) DEFAULT NULL,
  `callid` char(64) DEFAULT NULL,
  `queuename` char(64) DEFAULT NULL,
  `agent` char(64) DEFAULT NULL,
  `event` char(32) DEFAULT NULL,
  `data` char(64) DEFAULT NULL,
  `data1` char(64) DEFAULT NULL,
  `data2` char(64) DEFAULT NULL,
  `data3` char(64) DEFAULT NULL,
  `data4` char(64) DEFAULT NULL,
  `data5` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-26 22:31:55
