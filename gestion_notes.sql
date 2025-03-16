-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: gestion_notes
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (2,'admin','482f7629a2511d23ef4e958b13a5ba54bdba06f2');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'DSTI1A'),(2,'DSTI1B'),(3,'DSTI1C'),(6,'DSTI2A'),(7,'DSTI2B'),(4,'DSTTR1A'),(5,'DSTTR1B'),(8,'DSTTR2A'),(9,'L3 GLSI');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etudiants`
--

DROP TABLE IF EXISTS `etudiants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `classe_id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`),
  KEY `classe_id` (`classe_id`),
  CONSTRAINT `etudiants_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etudiants`
--

LOCK TABLES `etudiants` WRITE;
/*!40000 ALTER TABLE `etudiants` DISABLE KEYS */;
INSERT INTO `etudiants` VALUES (1,'DIOUF','Khalifa Babacar','xalifaxxdm@gmail.com','xalifa','$2y$10$zLbCXISNVX.QAdsUZrP6He/dIp.ActWAgfTYuOQe0QvGmdE2d9pRi',6,NULL),(2,'FALL','Massamba','massamba@gmail.com','mass','$2y$10$JEggfx09xhJXUTeuQR9elekRZ02KqPK8ilC6t.IIwfZGqL3f/lObO',3,NULL),(3,'FALL','Fatima','fatima.fall@esp.sn','tima','$2y$10$6dZPx950gnzkqe5BskMwqugk.fdSJZG9QXnThfpXpMjN7Lwvyjvxa',7,NULL),(6,'FALL','Modou','modoufall@esp.sn','modoufall','$2y$10$dgITR3KryHKWNyICv9JnceSeLPbdxSP4QPyQjWINa.5FiQIiTgsmm',6,'profils/1996313f-881d-47b9-8c3c-9fcbb5e69f10.jpg'),(7,'BALL','Thierno','thierno.ball@gmail.com','thierno','$2y$10$nW4rYLyW0yXv9bn3IBH5AObvG1wwtvbO7mW2lU2NkJvq03khSUKyu',1,'profils/img1.jpg'),(8,'NDIAYE','Serigne A A','abdl@gmail.com','abdl','$2y$10$ngHtHJM0ReW4mxanY4vYNOgUaiS7B.V0yVcrBBavyHbDovJyB4Kv6',2,'profils/img3.png'),(9,'SY','Moussa','moussasy@gmail.com','moussa','$2y$10$9WWJlUmu/dX9o9LySv6H8.htFiwDMIVQVoe4nKPS3q0r/AHNH3H76',3,'profils/img4.png'),(11,'DIOUF','Salimata','salimatadiop@esp.sn','sali','$2y$10$sh7God82FONnJZU7EGb8guChDGnsK.wS2J5zpXkpSJTR.lW1SpD/S',4,'profils/img8.png'),(13,'MBAYE','Souleymane','souleymane.mbaye@gmail.com','souleymane','$2y$10$Rt/nQD5EEnc6pk18KkUV6OXmfzzibYQnqhtmiol5.SgZip/KnAkKu',6,'profils/img4.png');
/*!40000 ALTER TABLE `etudiants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matieres`
--

LOCK TABLES `matieres` WRITE;
/*!40000 ALTER TABLE `matieres` DISABLE KEYS */;
INSERT INTO `matieres` VALUES (1,'Algèbre'),(6,'Algorithmique et Programmation'),(7,'Analyse'),(4,'Architecture des ordinateurs'),(3,'Droit'),(2,'Economie'),(18,'Gestion de l\'entreprise'),(17,'Gestion de Projet'),(10,'Langage C'),(8,'Mathématiques Discrètes'),(15,'MSI'),(13,'Programmation Backend'),(14,'Programmation Orientée Objet'),(11,'Recherche Opérationnelle'),(19,'Réseaux'),(12,'SGBD'),(16,'Statistiques'),(9,'Système d\'exploitation'),(5,'Technologie des ordinateurs');
/*!40000 ALTER TABLE `matieres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etudiant_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note` decimal(4,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `etudiant_id` (`etudiant_id`),
  KEY `matiere_id` (`matiere_id`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (1,1,8,11.00),(2,6,9,5.00),(4,1,6,19.00),(5,1,15,15.00),(6,1,11,18.00),(7,1,17,14.00),(8,6,9,10.00),(9,1,13,8.00);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-16 18:52:57
