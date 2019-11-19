-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: videoconferencia
-- ------------------------------------------------------
-- Server version	10.1.37-MariaDB

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
-- Table structure for table `estado_videoconferencia`
--

DROP TABLE IF EXISTS `estado_videoconferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado_videoconferencia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `estado` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_videoconferencia`
--

LOCK TABLES `estado_videoconferencia` WRITE;
/*!40000 ALTER TABLE `estado_videoconferencia` DISABLE KEYS */;
INSERT INTO `estado_videoconferencia` VALUES (1,'iniciada en termino'),(2,'iniciada con demora'),(3,'no iniciada'),(4,'suspendida'),(5,'finalizada en termino'),(6,'finalizada con demora'),(7,'interrumpida por problema tecnico'),(8,'interrumpida por comportamiento del interno');
/*!40000 ALTER TABLE `estado_videoconferencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interno_unidad`
--

DROP TABLE IF EXISTS `interno_unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interno_unidad` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `apellido` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `unidad` bigint(20) NOT NULL,
  `email_representante` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `interno_unidad_FK` (`unidad`),
  CONSTRAINT `interno_unidad_FK` FOREIGN KEY (`unidad`) REFERENCES `unidades` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interno_unidad`
--

LOCK TABLES `interno_unidad` WRITE;
/*!40000 ALTER TABLE `interno_unidad` DISABLE KEYS */;
INSERT INTO `interno_unidad` VALUES (1,'Interno1','Interno1',1,'repint1@gmail.com'),(2,'Interno2','Interno2',1,'repint2@gmail.com'),(3,'Interno3','Interno3',2,'repint3@gmail.com'),(4,'Interno4','Interno4',2,'repint4@gmail.com'),(5,'Interno5','Interno5',3,'repint1@gmail.com'),(6,'Interno6','Interno6',3,'repint2@gmail.com'),(7,'Interno7','Interno7',4,'repint3@gmail.com'),(8,'Interno8','Interno8',4,'repint4@gmail.com'),(9,'Interno9','Interno9',4,'repint1@gmail.com');
/*!40000 ALTER TABLE `interno_unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participante_videoconferencia`
--

DROP TABLE IF EXISTS `participante_videoconferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participante_videoconferencia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo_participante` bigint(20) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `participante_videoconferencia_FK` (`tipo_participante`),
  CONSTRAINT `participante_videoconferencia_FK` FOREIGN KEY (`tipo_participante`) REFERENCES `tipo_participante` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participante_videoconferencia`
--

LOCK TABLES `participante_videoconferencia` WRITE;
/*!40000 ALTER TABLE `participante_videoconferencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `participante_videoconferencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_videoconferencia`
--

DROP TABLE IF EXISTS `registro_videoconferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registro_videoconferencia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `estado` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `videoconferencia` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `registro_videoconferencia_FK` (`estado`),
  KEY `registro_videoconferencia_FK_1` (`videoconferencia`),
  CONSTRAINT `registro_videoconferencia_FK` FOREIGN KEY (`estado`) REFERENCES `estado_videoconferencia` (`id`),
  CONSTRAINT `registro_videoconferencia_FK_1` FOREIGN KEY (`videoconferencia`) REFERENCES `videoconferencias` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_videoconferencia`
--

LOCK TABLES `registro_videoconferencia` WRITE;
/*!40000 ALTER TABLE `registro_videoconferencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro_videoconferencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_participante`
--

DROP TABLE IF EXISTS `tipo_participante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_participante` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_participante`
--

LOCK TABLES `tipo_participante` WRITE;
/*!40000 ALTER TABLE `tipo_participante` DISABLE KEYS */;
INSERT INTO `tipo_participante` VALUES (1,'interno'),(2,'abogado'),(3,'juez'),(4,'procurador');
/*!40000 ALTER TABLE `tipo_participante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_videoconferencia`
--

DROP TABLE IF EXISTS `tipo_videoconferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_videoconferencia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_videoconferencia`
--

LOCK TABLES `tipo_videoconferencia` WRITE;
/*!40000 ALTER TABLE `tipo_videoconferencia` DISABLE KEYS */;
INSERT INTO `tipo_videoconferencia` VALUES (1,'comparendo'),(2,'entrevista');
/*!40000 ALTER TABLE `tipo_videoconferencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidades` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `numeroUnidad` bigint(20) NOT NULL,
  `coordenadas` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES (1,'Unidad 1',1,'-34.99879, -58.04139','emailU1@mjus.gob.ar'),(2,'Unidad 2',2,'-36.83867, -60.22645','emailU2@mjus.gob.ar'),(3,'Unidad 3',3,'-33.35242, -60.19768','emailU3@mjus.gob.ar'),(4,'Unidad 4',4,'-38.68651, -62.27582','emailU4@mjus.gob.ar');
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videoconferencias`
--

DROP TABLE IF EXISTS `videoconferencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videoconferencias` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `unidad` bigint(20) NOT NULL,
  `estado` bigint(20) NOT NULL,
  `tipo` bigint(20) NOT NULL,
  `nro_causa` varchar(100) NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `solicitante` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `videoconferencias_FK` (`tipo`),
  KEY `videoconferencias_FK_1` (`unidad`),
  KEY `videoconferencias_FK_2` (`estado`),
  KEY `videoconferencias_FK_3` (`solicitante`),
  CONSTRAINT `videoconferencias_FK` FOREIGN KEY (`tipo`) REFERENCES `tipo_videoconferencia` (`id`),
  CONSTRAINT `videoconferencias_FK_1` FOREIGN KEY (`unidad`) REFERENCES `unidades` (`id`),
  CONSTRAINT `videoconferencias_FK_2` FOREIGN KEY (`estado`) REFERENCES `estado_videoconferencia` (`id`),
  CONSTRAINT `videoconferencias_FK_3` FOREIGN KEY (`solicitante`) REFERENCES `participante_videoconferencia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videoconferencias`
--

LOCK TABLES `videoconferencias` WRITE;
/*!40000 ALTER TABLE `videoconferencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `videoconferencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'videoconferencia'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-17 12:28:01
