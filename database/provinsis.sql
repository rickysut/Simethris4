-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: lasimethris04
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `provinsis`
--

DROP TABLE IF EXISTS `provinsis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinsis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provinsi_id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kd_bast` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double(13,10) DEFAULT NULL,
  `lng` double(13,10) DEFAULT NULL,
  `no_satker` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provinsis_kd_prop_unique` (`provinsi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinsis`
--

LOCK TABLES `provinsis` WRITE;
/*!40000 ALTER TABLE `provinsis` DISABLE KEYS */;
INSERT INTO `provinsis` VALUES (1,12,'SUMATERA UTARA','070000',3.5970310000,98.6785130000,'79318',NULL,NULL,NULL),(2,13,'SUMATERA BARAT','080000',-0.5791910000,100.5249090000,'89266',NULL,NULL,NULL),(3,14,'RIAU','090000',0.5065660000,101.4377900000,'99426',NULL,NULL,NULL),(4,15,'JAMBI','100000',-1.6751560000,102.6930150000,'109019',NULL,NULL,NULL),(5,16,'SUMATERA SELATAN','110000',-2.9909340000,104.7565540000,'119225',NULL,NULL,NULL),(6,17,'BENGKULU','260000',-3.4197860000,102.2078290000,'269189',NULL,NULL,NULL),(7,18,'LAMPUNG','120000',-4.9245110000,105.1843350000,'129224',NULL,NULL,NULL),(8,19,'KEPULAUAN BANGKA BELITUNG','290000',-2.7410510000,106.4405870000,'309208',NULL,NULL,NULL),(9,21,'KEPULAUAN RIAU','310000',3.7078160000,108.1030410000,'320097',NULL,NULL,NULL),(10,31,'DKI JAKARTA','010000',-6.1751100000,106.8650390000,'19032',NULL,NULL,NULL),(11,32,'JAWA BARAT','020000',-6.8542830000,107.7839420000,'29346',NULL,NULL,NULL),(12,33,'JAWA TENGAH','030000',-7.1240250000,110.3067600000,'39427',NULL,NULL,NULL),(13,34,'DI YOGYAKARTA','040000',-7.7970680000,110.3705290000,'49037',NULL,NULL,NULL),(14,35,'JAWA TIMUR','050000',-7.5833710000,112.6006030000,'59444',NULL,NULL,NULL),(15,36,'BANTEN','280000',-6.3993170000,106.0254050000,'299444',NULL,NULL,NULL),(16,51,'BALI','220000',-8.4419700000,115.1927330000,'229164',NULL,NULL,NULL),(17,52,'NUSA TENGGARA BARAT','230000',-8.6509790000,116.3249440000,'239220',NULL,NULL,NULL),(18,53,'NUSA TENGGARA TIMUR','240000',-8.6573820000,121.0793700000,'249020',NULL,NULL,NULL),(19,61,'KALIMANTAN BARAT','130000',0.0000000000,109.3333360000,'139021',NULL,NULL,NULL),(20,62,'KALIMANTAN TENGAH','140000',-1.4711110000,113.6405330000,'149214',NULL,NULL,NULL),(21,63,'KALIMANTAN SELATAN','150000',-3.3166940000,114.5901110000,'159192',NULL,NULL,NULL),(22,64,'KALIMANTAN TIMUR','160000',0.5386590000,116.4193890000,'169000',NULL,NULL,NULL),(23,65,'KALIMANTAN UTARA','340000',3.1848020000,116.2871450000,'417679',NULL,NULL,NULL),(24,71,'SULAWESI UTARA','170000',0.7762270000,124.3085240000,'179212',NULL,NULL,NULL),(25,72,'SULAWESI TENGAH','180000',-0.9779700000,120.4623420000,'189206',NULL,NULL,NULL),(26,73,'SULAWESI SELATAN','190000',-3.6687990000,119.9740530000,'199374',NULL,NULL,NULL),(27,74,'SULAWESI TENGGARA','200000',-3.9722010000,122.5149000000,'209186',NULL,NULL,NULL),(28,75,'GORONTALO','300000',0.7007950000,122.4412300000,'319005',NULL,NULL,NULL),(29,76,'SULAWESI BARAT','330000',-2.8441370000,119.2320780000,'340161',NULL,NULL,NULL),(30,81,'MALUKU','210000',-3.1439690000,128.8495060000,'219169',NULL,NULL,NULL),(31,82,'MALUKU UTARA','270000',0.7726330000,127.7483440000,'289039',NULL,NULL,NULL),(32,91,'PAPUA BARAT','320000',-0.8614530000,134.0620420000,'339029',NULL,NULL,NULL),(33,94,'PAPUA','250000',-4.2699280000,138.0803530000,'259022',NULL,NULL,NULL),(34,11,'ACEH','060000',4.6951350000,96.7493970000,'69027','2022-04-29 06:11:59','2022-04-29 07:28:52',NULL),(35,99,'TEST','099111111',1.0000000000,1.0000000000,'123456','2022-04-29 06:51:33','2022-04-29 06:51:58','2022-04-29 06:51:58');
/*!40000 ALTER TABLE `provinsis` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-20  3:48:45
