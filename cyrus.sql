-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cyrus
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `account_plan`
--

DROP TABLE IF EXISTS `account_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_plan` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `duration` int(16) NOT NULL,
  `price` double(12,2) NOT NULL,
  `stack` int(3) NOT NULL DEFAULT 1,
  `maximum` int(3) DEFAULT 0,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_plan`
--

LOCK TABLES `account_plan` WRITE;
/*!40000 ALTER TABLE `account_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_purchase`
--

DROP TABLE IF EXISTS `account_purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_purchase` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user` int(16) NOT NULL,
  `account_plan` int(3) NOT NULL,
  `price` double(12,2) NOT NULL,
  `purchased_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration` int(16) NOT NULL,
  `revoked_by` int(16) DEFAULT NULL,
  `revoked_reason` varchar(4000) DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `rescued_at` timestamp NULL DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `account_plan` (`account_plan`),
  KEY `revoked_by` (`revoked_by`),
  CONSTRAINT `account_purchase_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `account_purchase_ibfk_2` FOREIGN KEY (`account_plan`) REFERENCES `account_plan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `account_purchase_ibfk_3` FOREIGN KEY (`revoked_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_purchase`
--

LOCK TABLES `account_purchase` WRITE;
/*!40000 ALTER TABLE `account_purchase` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anime`
--

DROP TABLE IF EXISTS `anime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anime` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) DEFAULT NULL,
  `original_title` varchar(120) DEFAULT NULL,
  `synopsis` text DEFAULT NULL,
  `start_date` date DEFAULT current_timestamp(),
  `end_date` date DEFAULT NULL,
  `mature` int(1) DEFAULT 0 CHECK (`mature` in (0,1)),
  `launch_day` int(1) DEFAULT NULL CHECK (`launch_day` in (1,2,3,4,5,6,7)),
  `source` int(2) NOT NULL,
  `audience` int(2) NOT NULL,
  `trailer` varchar(1000) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  `launch_time` time DEFAULT NULL,
  `profile` int(24) DEFAULT NULL,
  `cape` int(24) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `audience` (`audience`),
  KEY `profile` (`profile`),
  KEY `cape` (`cape`),
  CONSTRAINT `anime_ibfk_1` FOREIGN KEY (`source`) REFERENCES `source_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `anime_ibfk_2` FOREIGN KEY (`audience`) REFERENCES `audience` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `anime_ibfk_3` FOREIGN KEY (`profile`) REFERENCES `resource` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `anime_ibfk_4` FOREIGN KEY (`cape`) REFERENCES `resource` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anime`
--

LOCK TABLES `anime` WRITE;
/*!40000 ALTER TABLE `anime` DISABLE KEYS */;
INSERT INTO `anime` VALUES (1,'Classroom of the Elite','Youkoso Jitsuryoku Shijou Shugi no Kyoushitsu e','On the surface, Koudo Ikusei Senior High School is a utopia. The students enjoy an unparalleled amount of freedom, and it is ranked highly in Japan. However, the reality is less than ideal. Four classes, A through D, are ranked in order of merit, and only the top classes receive favorable treatment.\n\nKiyotaka Ayanokouji is a student of Class D, where the school dumps its worst. There he meets the unsociable Suzune Horikita, who believes she was placed in Class D by mistake and desires to climb all the way to Class A, and the seemingly amicable class idol Kikyou Kushida, whose aim is to make as many friends as possible.\n\nWhile class membership is permanent, class rankings are not; students in lower ranked classes can rise in rankings if they score better than those in the top ones. Additionally, in Class D, there are no bars on what methods can be used to get ahead. In this cutthroat school, can they prevail against the odds and reach the top?','2022-07-04','2022-07-04',0,1,1,1,'https://www.youtube.com/embed/vdD_f5AXbnk',1,'23:47:38',1,5),(2,'Kaguya-sama: Love Is War','Kaguya-sama wa Kokurasetai: Tensai-tachi no Renai Zunousen','Veio de boa família? Sim! Tem uma personalidade promissora? Sim!Todos os jovens de elite com futuros brilhantes acabam indo parar na Academia Shuchiin.E ambos os líderes do conselho estudantil, Kaguya Shinomiya e Miyuki Shirogane, estão apaixonados um pelo outro.Mas seis meses se passaram e nada aconteceu?!Ambos são orgulhosos demais para confessar seu amor.Esse orgulho só piorou com o tempo, e agora ambos estão brigando pra ver quem faz o outro se declarar primeiro!A parte mais divertida do amor é o jogo da conquista!  Uma nova comédia romântica, sobre as batalhas intelectuais de dois estudantes de elite apaixonados.','2022-07-04',NULL,0,4,1,1,'https://www.youtube.com/embed/rZ95aZmQu_8',1,'23:57:37',2,6),(3,'Love After World Domination','Koi wa Sekai Seifuku no Ato de','A história acompanha a complicada relação entre Fudou Aikawa, líder do grupo de heróis, que sonham em trazer a paz para o mundo, e Desumi Magahara, uma brava guerreira de uma organização secreta que planeja a dominação global.','2022-07-05',NULL,0,2,1,1,'https://www.youtube.com/embed/t_LOPSpeYvE',1,'00:09:15',3,7),(4,'Tsukimichi -Moonlit Fantasy-','Tsuki ga Michibiku Isekai Douchuu','A história acompanha Makoto Misumi, um jovem comum que é invocado em um mundo de fantasia para ser o grande herói que salvará as pessoas. Entretanto, quando a Deusa do lugar o vê, acaba o destituindo do título e o banindo para uma região deserta por achar o rosto do rapaz feio demais. Tendo adquirido grandes poderes nesse novo mundo, Makoto consegue sobreviver ao seu banimento e encontrar várias criaturas incríveis, como aranhas e dragões, formando assim alianças inesperadas para que possa remodelar o mundo em que os Deuses e humanos o abandonaram.','2022-07-04',NULL,0,6,1,1,'https://www.youtube.com/embed/DbtwLh73D90',1,'00:15:37',4,8);
/*!40000 ALTER TABLE `anime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anime_gender`
--

DROP TABLE IF EXISTS `anime_gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anime_gender` (
  `anime` int(16) NOT NULL,
  `gender` int(3) NOT NULL,
  PRIMARY KEY (`anime`,`gender`),
  KEY `gender` (`gender`),
  CONSTRAINT `anime_gender_ibfk_1` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anime_gender_ibfk_2` FOREIGN KEY (`gender`) REFERENCES `genre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anime_gender`
--

LOCK TABLES `anime_gender` WRITE;
/*!40000 ALTER TABLE `anime_gender` DISABLE KEYS */;
INSERT INTO `anime_gender` VALUES (1,2),(1,3),(1,5),(1,6),(2,4),(2,5),(2,7),(2,8),(3,4),(3,12),(4,1),(4,2),(4,10),(4,11),(4,12),(4,13);
/*!40000 ALTER TABLE `anime_gender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audience`
--

DROP TABLE IF EXISTS `audience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audience` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `minimum_age` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audience`
--

LOCK TABLES `audience` WRITE;
/*!40000 ALTER TABLE `audience` DISABLE KEYS */;
INSERT INTO `audience` VALUES (1,'Jovem',15),(2,'Adulto',18),(3,'Criança',8);
/*!40000 ALTER TABLE `audience` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentanime`
--

DROP TABLE IF EXISTS `commentanime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentanime` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user` int(16) DEFAULT NULL,
  `anime` int(12) DEFAULT NULL,
  `post_date` date DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `spoiler` tinyint(1) DEFAULT NULL,
  `classification` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `anime` (`anime`),
  CONSTRAINT `commentanime_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `commentanime_ibfk_2` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentanime`
--

LOCK TABLES `commentanime` WRITE;
/*!40000 ALTER TABLE `commentanime` DISABLE KEYS */;
INSERT INTO `commentanime` VALUES (3,4,1,'2022-07-18','Anime incrível','Adorei o anime e tive goosebumps quando o Naruto ativou a gear 5, foi incrível!',1,5),(4,4,1,'2022-07-18','Fenomenal!','Um belo anime para todas as famílias, recomendo a 100%.',0,5),(5,4,2,'2022-07-18','Adorei este anime','Adorei a ideia do anime no entanto achei demasiado lenta a progressão da história.',0,4),(6,4,2,'2022-07-18','Sensacional, melhor anime visto','Simplesmente mágico quando o Kurosaki ativou a bankai',1,5),(7,4,3,'2022-07-18','Mediano, não gostei muito.','Tem uma boa ideia no entanto esta não é executada da melhor maneira.',0,3),(8,4,3,'2022-07-18','Perda de tempo','Detestei este anime, o Asta não para de gritar. Demasiado irritante.',1,1),(9,4,4,'2022-07-18','Uma bela opção para desfrutar a tarde.','Belo anime, bem bonito e uma bela história.',0,4);
/*!40000 ALTER TABLE `commentanime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentnews`
--

DROP TABLE IF EXISTS `commentnews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentnews` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user` int(16) DEFAULT NULL,
  `news` int(8) DEFAULT NULL,
  `post_date` date DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`),
  CONSTRAINT `commentnews_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `commentnews_ibfk_2` FOREIGN KEY (`news`) REFERENCES `news` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentnews`
--

LOCK TABLES `commentnews` WRITE;
/*!40000 ALTER TABLE `commentnews` DISABLE KEYS */;
INSERT INTO `commentnews` VALUES (1,4,NULL,'2022-07-18','teste'),(2,4,NULL,'2022-07-18','ooi'),(3,4,13,'2022-07-18','Noticia incrível! Muito obrigado :)');
/*!40000 ALTER TABLE `commentnews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentvideo`
--

DROP TABLE IF EXISTS `commentvideo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentvideo` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user` int(16) DEFAULT NULL,
  `video` int(16) DEFAULT NULL,
  `post_date` date DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `spoiler` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `video` (`video`),
  CONSTRAINT `commentvideo_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `commentvideo_ibfk_2` FOREIGN KEY (`video`) REFERENCES `video` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentvideo`
--

LOCK TABLES `commentvideo` WRITE;
/*!40000 ALTER TABLE `commentvideo` DISABLE KEYS */;
INSERT INTO `commentvideo` VALUES (3,4,1,'2022-07-18','melhor episodio',1),(4,4,2,'2022-07-18','quero logo o segundo episódio',0),(5,4,2,'2022-07-18','este final....',1),(6,4,4,'2022-07-18','é de pedir por mais!!',0);
/*!40000 ALTER TABLE `commentvideo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dubbing`
--

DROP TABLE IF EXISTS `dubbing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dubbing` (
  `id` int(18) NOT NULL AUTO_INCREMENT,
  `video` int(16) NOT NULL,
  `language` int(3) NOT NULL,
  `path` int(24) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  KEY `video` (`video`),
  KEY `language` (`language`),
  KEY `path` (`path`),
  CONSTRAINT `dubbing_ibfk_1` FOREIGN KEY (`video`) REFERENCES `video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dubbing_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dubbing_ibfk_3` FOREIGN KEY (`path`) REFERENCES `resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dubbing`
--

LOCK TABLES `dubbing` WRITE;
/*!40000 ALTER TABLE `dubbing` DISABLE KEYS */;
/*!40000 ALTER TABLE `dubbing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genre`
--

DROP TABLE IF EXISTS `genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genre` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genre`
--

LOCK TABLES `genre` WRITE;
/*!40000 ALTER TABLE `genre` DISABLE KEYS */;
INSERT INTO `genre` VALUES (1,'Ficção Científica'),(2,'Ação'),(3,'Drama'),(4,'Romance'),(5,'Escolar'),(6,'Psicológico'),(7,'Slice of Life'),(8,'Comédia'),(9,'Música'),(10,'Sobrenatural'),(11,'Aventura'),(12,'Fantasia'),(13,'Shounen'),(14,'Ecchi'),(15,'Magia'),(16,'Jogo'),(17,'Histórico'),(18,'Histórico'),(19,'Seinen');
/*!40000 ALTER TABLE `genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_settings` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `value_binary` blob DEFAULT NULL,
  `data_type` varchar(50) NOT NULL DEFAULT 'string',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_settings`
--

LOCK TABLES `global_settings` WRITE;
/*!40000 ALTER TABLE `global_settings` DISABLE KEYS */;
INSERT INTO `global_settings` VALUES (1,'Twitter','SocialMedia','https://www.twitter.com/',NULL,'string'),(2,'Facebook','SocialMedia','https://www.facebook.com',NULL,'string'),(3,'Instagram','SocialMedia','https://www.instagram.com',NULL,'string'),(4,'Youtube','SocialMedia','https://www.youtube.com',NULL,'string'),(5,'ProjectStartDate','Information','2021',NULL,'string');
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `user` int(16) NOT NULL,
  `video` int(16) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `watched_until` int(7) DEFAULT 0,
  PRIMARY KEY (`user`,`video`),
  KEY `video` (`video`),
  CONSTRAINT `history_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `history_ibfk_2` FOREIGN KEY (`video`) REFERENCES `video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` VALUES (4,1,'2022-07-18 12:03:07',3),(4,2,'2022-07-18 11:42:25',0),(4,3,'2022-07-18 11:49:31',70),(4,4,'2022-07-18 11:47:14',0),(5,1,'2022-07-13 21:11:45',12);
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `original_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `original_name` (`original_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` VALUES (1,'ja','Japonês','Japanese'),(2,'	pt','Português','Portuguese'),(3,'pt_BR','Português Brasileiro','Portuguese, Brazil'),(4,'EN','English','English'),(5,'FR','French','Français');
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `user` int(16) NOT NULL,
  `action_type` int(4) DEFAULT NULL,
  `arguments` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `action_type` (`action_type`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_2` FOREIGN KEY (`action_type`) REFERENCES `log_action` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_action`
--

DROP TABLE IF EXISTS `log_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_action` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_action`
--

LOCK TABLES `log_action` WRITE;
/*!40000 ALTER TABLE `log_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user` int(16) DEFAULT NULL,
  `created_at` date DEFAULT current_timestamp(),
  `spotlight` tinyint(1) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (13,4,'2022-07-18',1,1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_body`
--

DROP TABLE IF EXISTS `news_body`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_body` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `news` int(8) DEFAULT NULL,
  `user` int(16) DEFAULT NULL,
  `edited_at` date DEFAULT current_timestamp(),
  `content` longtext DEFAULT NULL,
  `title` varchar(800) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `preview` tinytext DEFAULT NULL,
  `thumbnail` int(24) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `news` (`news`),
  KEY `user` (`user`),
  KEY `thumbnail` (`thumbnail`),
  CONSTRAINT `news_body_ibfk_1` FOREIGN KEY (`news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_body_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `news_body_ibfk_3` FOREIGN KEY (`thumbnail`) REFERENCES `resource` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_body`
--

LOCK TABLES `news_body` WRITE;
/*!40000 ALTER TABLE `news_body` DISABLE KEYS */;
INSERT INTO `news_body` VALUES (12,13,4,'2022-07-18','<p style=\"margin-right: 0px; margin-bottom: 11px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Cada dia que passa nos aproximamos da grande estreia de&nbsp;<span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><a href=\"http://www.crunchyroll.com/pt-br/dragon-ball-super?utm_source=editorial_cr&amp;utm_medium=news&amp;utm_campaign=news_pt&amp;referrer=editorial_cr_news_news_pt\" style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; cursor: pointer;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Dragon Ball Super</span></a><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">: SUPER HERO</span></span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">, o longa-metragem que dá continuidade tanto à série em anime de&nbsp;</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><a href=\"http://www.crunchyroll.com/pt-br/dragon-ball-super?utm_source=editorial_cr&amp;utm_medium=news&amp;utm_campaign=news_pt&amp;referrer=editorial_cr_news_news_pt\" style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; cursor: pointer;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Dragon Ball Super</span></a></span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">&nbsp;como ao filme de&nbsp;</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Dragon Ball Super: Broly</span></span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">! A estreia global do filme, incluindo nos cinemas do Brasil, está marcada para o dia&nbsp;</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">18 de agosto de 2022</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">, tanto nas versões com o</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">&nbsp;áudio original e legendas em português</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">, como com&nbsp;</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">dublagem em português brasileiro</span><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">.</span></p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">&nbsp;</p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Agora, sem mais delongas, confira o trailer dublado do filme, seguido do seu elenco de vozes em português brasileiro!</p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><br></p><p style=\"text-align: center; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/HNY3rlgfxvA\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe></p><p style=\"text-align: center; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><br></p><p style=\"text-align: center; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><br></p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">O elenco de dublagem&nbsp;é formado por:</p><ul style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px 0px 0px 30px; border: 0px; outline: 0px; vertical-align: baseline; list-style-position: initial; list-style-image: initial;\"><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Vagner Fagunde</span>s, voz de Son Gohan</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Wendel Bezerra</span>, voz de Son Goku</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Yuri Chesman</span>, voz de Son Goten</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Luiz Antônio Lobue</span>, voz de Piccolo</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Tânia Gaidarji</span>, voz de Bulma</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Alfredo Rollo</span>, voz de Vegeta</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Fábio Lucindo</span>, voz de Krillin</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Marcelo Campos</span>, voz de Trunks</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Melissa Garcia</span>, voz de Videl</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Mariana Evangelista</span>, voz de Pan</li></ul><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">&nbsp;</p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">As novas vozes abaixo, em português, complementam o elenco:</p><ul style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px 0px 0px 30px; border: 0px; outline: 0px; vertical-align: baseline; list-style-position: initial; list-style-image: initial;\"><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Pedro Alcântara</span>, voz de Dr. Hedo</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Heitor Assali</span>, voz de Gamma 1</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Felipe Drummond</span>, voz de Gamma 2</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Ronaldo Júlio</span>, voz de Magenta</li><li style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">César Marchetti</span>, voz de Carmine</li></ul><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">&nbsp;</p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Direção de Dublagem:&nbsp;<span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline;\">Wendel Bezerra</span></p>','Meu compromisso é sempre vencer! Confira o trailer dublado e o elenco de dublagem de Dragon Ball Super: SUPER HERO','Prontos para elevar seu ki ao máximo nos cinemas brasileiros no dia 18 de agosto?','Cada dia que passa nos aproximamos da grande estreia do filme em anime Dragon Ball Super: SUPER HERO e agora temos o prazer de revelar tanto o seu trailer dublado, como o elenco de voz da dublagem brasileira! Confira tudo isso logo após o clique!',125);
/*!40000 ALTER TABLE `news_body` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'BACKOFFICE_ACCESS','Acesso ao Backoffice','Permite a este utilizador aceder ao Backoffice'),(2,'BACKOFFICE_ENTITY_USER_QUERY','Pesquisar um Utilizador','Permite ao utilizador procurar por um outro utilizador'),(3,'BACKOFFICE_ENTITY_USER_INSERT','Inserir um Utilizador','Permite ao utilizador inserir um outro utilizador'),(4,'BACKOFFICE_ENTITY_USER_UPDATE','Atualizar um Utilizador','Permite ao utilizador atualizar um outro utilizador'),(5,'BACKOFFICE_ENTITY_USER_REMOVE','Remover um Utilizador','Permite ao utilizador remover um outro utilizador'),(6,'TICKETS_ACCESS_OTHERS','Aceder a todos os Tickets','Aceder aos tickets de outros Utilizadores (e listá-los)');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `punishment`
--

DROP TABLE IF EXISTS `punishment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `punishment` (
  `id` int(24) NOT NULL AUTO_INCREMENT,
  `user` int(16) NOT NULL,
  `punishment_type` int(3) NOT NULL,
  `reason` varchar(4000) NOT NULL,
  `lasts_until` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `performed_by` int(16) NOT NULL,
  `performed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `revoked_by` int(16) DEFAULT NULL,
  `revoked_date` timestamp NULL DEFAULT NULL,
  `revoked_reason` varchar(4000) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `punishment_type` (`punishment_type`),
  KEY `performed_by` (`performed_by`),
  KEY `revoked_by` (`revoked_by`),
  CONSTRAINT `punishment_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `punishment_ibfk_2` FOREIGN KEY (`punishment_type`) REFERENCES `punishment_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `punishment_ibfk_3` FOREIGN KEY (`performed_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `punishment_ibfk_4` FOREIGN KEY (`revoked_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `punishment`
--

LOCK TABLES `punishment` WRITE;
/*!40000 ALTER TABLE `punishment` DISABLE KEYS */;
/*!40000 ALTER TABLE `punishment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `punishment_type`
--

DROP TABLE IF EXISTS `punishment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `punishment_type` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `punishment_type`
--

LOCK TABLES `punishment_type` WRITE;
/*!40000 ALTER TABLE `punishment_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `punishment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource`
--

DROP TABLE IF EXISTS `resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource` (
  `id` int(24) NOT NULL AUTO_INCREMENT,
  `original_name` varchar(1250) DEFAULT NULL,
  `extension` varchar(10) NOT NULL,
  `path` text NOT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource`
--

LOCK TABLES `resource` WRITE;
/*!40000 ALTER TABLE `resource` DISABLE KEYS */;
INSERT INTO `resource` VALUES (1,'a','jpg','http://localhost/Cyrus/resources/site/resources/1.jpg',1),(2,'a','png','http://localhost/Cyrus/resources/site/resources/2.png',1),(3,'a','png','http://localhost/Cyrus/resources/site/resources/3.png',1),(4,'Classroom of the Elite','png','http://localhost/Cyrus/resources/site/resources/4.png',1),(5,'a','webp','http://localhost/Cyrus/resources/site/resources/5.webp',1),(6,'Classroom of the Elite','jpg','http://localhost/Cyrus/resources/site/resources/6.jpg',1),(7,'a','png','http://localhost/Cyrus/resources/site/resources/7.png',1),(8,'Classroom of the Elite','jpg','http://localhost/Cyrus/resources/site/resources/8.jpg',1),(9,'a','jpg','http://localhost/Cyrus/resources/site/resources/9.jpg',1),(10,'Kaguya-sama: Love Is War','png','http://localhost/Cyrus/resources/site/resources/10.png',1),(11,'a','png','http://localhost/Cyrus/resources/site/resources/11.png',1),(12,'a','webp','http://localhost/Cyrus/resources/site/resources/12.webp',1),(13,'a','png','http://localhost/Cyrus/resources/site/resources/13.png',1),(14,'a','png','http://localhost/Cyrus/resources/site/resources/14.png',1),(15,'The Beggining','jpg','http://localhost/Cyrus/resources/site/resources/15.jpg',1),(16,'TT','mp4','http://localhost/Cyrus/resources/site/resources/16.mp4',1),(17,'The Beggining','jpg','http://localhost/Cyrus/resources/site/resources/17.jpg',1),(18,'TT','mp4','http://localhost/Cyrus/resources/site/resources/18.mp4',1),(19,'The Beggining','jpg','http://localhost/Cyrus/resources/site/resources/19.jpg',1),(20,'TT','mp4','http://localhost/Cyrus/resources/site/resources/20.mp4',1),(21,'11','vtt','http://localhost/Cyrus/resources/site/resources/21.vtt',1),(22,'11','vtt','http://localhost/Cyrus/resources/site/resources/22.vtt',1),(23,'11','vtt','http://localhost/Cyrus/resources/site/resources/23.vtt',1),(24,'1','jpg','http://localhost/Cyrus/resources/site/resources/24.jpg',1),(25,'a','mp4','http://localhost/Cyrus/resources/site/resources/25.mp4',1),(26,'1','vtt','http://localhost/Cyrus/resources/site/resources/26.vtt',1),(27,'1','vtt','http://localhost/Cyrus/resources/site/resources/27.vtt',1),(28,'1','vtt','http://localhost/Cyrus/resources/site/resources/28.vtt',1),(29,'War over','jpg','http://localhost/Cyrus/resources/site/resources/29.jpg',1),(30,'1','mp4','http://localhost/Cyrus/resources/site/resources/30.mp4',1),(31,'1','vtt','http://localhost/Cyrus/resources/site/resources/31.vtt',1),(32,'1','vtt','http://localhost/Cyrus/resources/site/resources/32.vtt',1),(33,'1','vtt','http://localhost/Cyrus/resources/site/resources/33.vtt',1),(34,'a','jpg','http://localhost/Cyrus/resources/site/resources/34.jpg',1),(35,'a','mp4','http://localhost/Cyrus/resources/site/resources/35.mp4',1),(36,'1','vtt','http://localhost/Cyrus/resources/site/resources/36.vtt',1),(37,'1','vtt','http://localhost/Cyrus/resources/site/resources/37.vtt',1),(38,'1','vtt','http://localhost/Cyrus/resources/site/resources/38.vtt',1),(39,'t','jpg','http://localhost/Cyrus/resources/site/resources/39.jpg',1),(40,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/40.png',1),(41,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/41.png',1),(42,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/42.png',1),(43,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(44,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(45,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(46,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(47,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(48,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(49,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(50,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(51,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(52,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/43.png',1),(53,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/53.png',1),(54,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/53.png',1),(55,'Ticket Attachment','jpg','http://localhost/Cyrus/resources/site/resources/55.jpg',1),(56,'Ticket Attachment','jpg','http://localhost/Cyrus/resources/site/resources/55.jpg',1),(57,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/57.png',1),(58,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/57.png',1),(59,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/59.png',1),(60,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/59.png',1),(61,'Ticket Attachment','jpg','http://localhost/Cyrus/resources/site/resources/61.jpg',1),(62,'Ticket Attachment','jpg','http://localhost/Cyrus/resources/site/resources/61.jpg',1),(63,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/63.png',1),(64,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/64.png',1),(65,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/63.png',1),(66,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/64.png',1),(67,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/67.png',1),(68,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/68.png',1),(69,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/69.png',1),(70,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/67.png',1),(71,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/68.png',1),(72,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/72.png',1),(73,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/73.png',1),(74,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/74.png',1),(75,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/72.png',1),(76,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/73.png',1),(77,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/74.png',1),(78,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/78.png',1),(79,'Ticket Attachment','png','http://localhost/Cyrus/resources/site/resources/78.png',1),(80,'qrlcmsombct81.png','png','http://localhost/Cyrus/resources/site/resources/80.png',1),(81,NULL,'png','http://localhost/Cyrus/resources/site/resources/80.png',1),(82,'c2io9u0pbct81.png','png','http://localhost/Cyrus/resources/site/resources/82.png',1),(83,NULL,'png','http://localhost/Cyrus/resources/site/resources/82.png',1),(84,'download.jpg','jpg','processing',1),(85,NULL,'jpg','http://localhost/Cyrus/resources/site/resources/84.jpg',1),(86,'26tuesombct81.png','png','http://localhost/Cyrus/resources/site/resources/86.png',1),(87,'qrlcmsombct81.png','png','http://localhost/Cyrus/resources/site/resources/87.png',1),(88,'qrlcmsombct81.png','png','http://localhost/Cyrus/resources/site/resources/87.png',1),(89,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(90,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(91,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(92,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(93,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(94,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(95,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(96,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(97,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(98,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(99,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/89.jpg',1),(100,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/100.jpg',1),(101,'proof.png','png','http://localhost/Cyrus/resources/site/resources/101.png',1),(102,'teste.jpg','jpg','http://localhost/Cyrus/resources/site/resources/102.jpg',1),(103,'teste.jpg','jpg','http://localhost/Cyrus/resources/site/resources/103.jpg',1),(104,'image.png','png','http://localhost/Cyrus/resources/site/resources/104.png',1),(105,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/105.jpg',1),(106,'image.png','png','http://localhost/Cyrus/resources/site/resources/106.png',1),(107,'ee7d47d5fff38ca234e2b5a8bd56bbb1.jpg','jpg','http://localhost/Cyrus/resources/site/resources/107.jpg',1),(108,'de67fez-e36e4d26-2a93-4543-a178-94a2f92bbca2.png','png','http://localhost/Cyrus/resources/site/resources/108.png',1),(109,'FH8jKwSUYAQdH8D.jpg','jpg','http://localhost/Cyrus/resources/site/resources/109.jpg',1),(110,'proof.png','png','http://localhost/Cyrus/resources/site/resources/110.png',1),(111,'de67fez-e36e4d26-2a93-4543-a178-94a2f92bbca2.png','png','http://localhost/Cyrus/resources/site/resources/111.png',1),(112,'proof.png','png','http://localhost/Cyrus/resources/site/resources/112.png',1),(113,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/113.jpg',1),(114,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/114.jpg',1),(115,'proof.png','png','http://localhost/Cyrus/resources/site/resources/115.png',1),(116,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/116.jpg',1),(117,'download.jpg','jpg','http://localhost/Cyrus/resources/site/resources/117.jpg',1),(118,'cyrus_icon_transparent_cropped.png','png','http://localhost/Cyrus/resources/site/resources/118.png',1),(119,'cyrus_icon_transparent_cropped.png','png','http://localhost/Cyrus/resources/site/resources/119.png',1),(120,'Screenshot_637.png','png','http://localhost/Cyrus/resources/site/resources/120.png',1),(121,'classroom-of-the-elite-new-project.png','png','http://localhost/Cyrus/resources/site/resources/121.png',1),(122,'f229ba88d2e5e523c4edfdea5d1844691656354502_main.png','png','http://localhost/Cyrus/resources/site/resources/122.png',1),(123,'Screenshot_638.png','png','http://localhost/Cyrus/resources/site/resources/123.png',1),(124,'ce63113d46fd5429983a34574dc1cb82.jpg','jpg','http://localhost/Cyrus/resources/site/resources/124.jpg',1),(125,'teste.png','png','http://localhost/Cyrus/resources/site/resources/125.png',1);
/*!40000 ALTER TABLE `resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Administrador');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `role` int(3) NOT NULL,
  `permission` int(4) NOT NULL,
  PRIMARY KEY (`role`,`permission`),
  KEY `permission` (`permission`),
  CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `season`
--

DROP TABLE IF EXISTS `season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `season` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `anime` int(16) NOT NULL,
  `numeration` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `synopsis` text DEFAULT NULL,
  `release_date` date NOT NULL DEFAULT current_timestamp(),
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anime` (`anime`,`numeration`),
  CONSTRAINT `season_ibfk_1` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `season`
--

LOCK TABLES `season` WRITE;
/*!40000 ALTER TABLE `season` DISABLE KEYS */;
/*!40000 ALTER TABLE `season` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `source_type`
--

DROP TABLE IF EXISTS `source_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source_type` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source_type`
--

LOCK TABLES `source_type` WRITE;
/*!40000 ALTER TABLE `source_type` DISABLE KEYS */;
INSERT INTO `source_type` VALUES (1,'Manga'),(7,'Light Novel');
/*!40000 ALTER TABLE `source_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subtitle`
--

DROP TABLE IF EXISTS `subtitle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subtitle` (
  `id` int(18) NOT NULL AUTO_INCREMENT,
  `video` int(16) NOT NULL,
  `language` int(3) NOT NULL,
  `path` int(24) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  KEY `video` (`video`),
  KEY `language` (`language`),
  KEY `path` (`path`),
  CONSTRAINT `subtitle_ibfk_1` FOREIGN KEY (`video`) REFERENCES `video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subtitle_ibfk_2` FOREIGN KEY (`language`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subtitle_ibfk_3` FOREIGN KEY (`path`) REFERENCES `resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subtitle`
--

LOCK TABLES `subtitle` WRITE;
/*!40000 ALTER TABLE `subtitle` DISABLE KEYS */;
INSERT INTO `subtitle` VALUES (1,1,4,21,1),(2,1,2,22,1),(3,1,5,23,1),(4,2,4,26,1),(5,2,2,27,1),(6,2,5,28,1),(7,3,2,31,1),(8,3,4,32,1),(9,3,5,33,1),(10,4,2,36,1),(11,4,4,37,1),(12,4,5,38,1);
/*!40000 ALTER TABLE `subtitle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `user` int(16) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `responsible` int(16) DEFAULT NULL,
  `status` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` int(16) DEFAULT NULL,
  `evaluation` int(2) DEFAULT NULL CHECK (`evaluation` >= 0 and `evaluation` <= 10),
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `responsible` (`responsible`),
  KEY `closed_by` (`closed_by`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`responsible`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`closed_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` VALUES (24,4,'nao carrega!',NULL,1,'2022-07-18 10:32:10',NULL,NULL,NULL),(25,4,'URGENTE',4,2,'2022-07-18 10:34:48','2022-07-18 10:45:09',4,NULL),(26,4,'Legenda errada :(',NULL,1,'2022-07-18 10:38:48',NULL,NULL,NULL),(27,4,'Login issues',NULL,1,'2022-07-18 10:43:59',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_message`
--

DROP TABLE IF EXISTS `ticket_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_message` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `ticket` int(16) DEFAULT NULL,
  `author` int(16) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `author` (`author`),
  CONSTRAINT `ticket_message_ibfk_1` FOREIGN KEY (`ticket`) REFERENCES `ticket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ticket_message_ibfk_2` FOREIGN KEY (`author`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_message`
--

LOCK TABLES `ticket_message` WRITE;
/*!40000 ALTER TABLE `ticket_message` DISABLE KEYS */;
INSERT INTO `ticket_message` VALUES (54,24,4,'quando clico em abrir o episodio, fica a carregar infinitamente, seria isto problema da minha internet ou do servidor?','2022-07-18 10:32:10'),(55,25,4,'so para dizer, que deviam ver classroom of the elite :)\n\n\nOTHERWISE  =%!!%','2022-07-18 10:34:48'),(56,25,4,'DEVIAM MUITO VER.\ndesculpem! :)','2022-07-18 10:36:19'),(57,26,4,'O ep 4 do anime classroom of the elite nao esta com a legenda certa, poderiam porfavor corrigi-la e depois avisarem-me para poder usufruir melhor deste belo anime?\nObrigado desde já,\n\nxx','2022-07-18 10:38:49'),(58,27,4,'Sometimes, while loggin in, it doesnt seem to be working, it always says things like, wrong password(and ofc im putting correct one) or wrong user, once i couldnt log the whole day which made me rlly upset since that was the day my fav anime was dropping the 2nd season.\n\nPlease find out about this,\nThanks anyways!','2022-07-18 10:43:59');
/*!40000 ALTER TABLE `ticket_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_message_attachment`
--

DROP TABLE IF EXISTS `ticket_message_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_message_attachment` (
  `message` int(16) NOT NULL,
  `resource` int(24) NOT NULL,
  PRIMARY KEY (`message`,`resource`),
  KEY `resource` (`resource`),
  CONSTRAINT `ticket_message_attachment_ibfk_1` FOREIGN KEY (`message`) REFERENCES `ticket_message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ticket_message_attachment_ibfk_2` FOREIGN KEY (`resource`) REFERENCES `resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_message_attachment`
--

LOCK TABLES `ticket_message_attachment` WRITE;
/*!40000 ALTER TABLE `ticket_message_attachment` DISABLE KEYS */;
INSERT INTO `ticket_message_attachment` VALUES (54,120),(55,121),(56,122),(57,123),(58,124);
/*!40000 ALTER TABLE `ticket_message_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` int(1) DEFAULT NULL CHECK (`sex` in (1,2,3)),
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(240) DEFAULT NULL,
  `profile_image` int(24) DEFAULT NULL,
  `profile_background` int(24) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `verified` int(1) DEFAULT 0 CHECK (`verified` in (0,1)),
  `display_language` int(3) DEFAULT NULL,
  `email_communication_language` int(3) DEFAULT NULL,
  `translation_language` int(3) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `profile_image` (`profile_image`),
  KEY `profile_background` (`profile_background`),
  KEY `display_language` (`display_language`),
  KEY `translation_language` (`translation_language`),
  KEY `email_communication_language` (`email_communication_language`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`profile_image`) REFERENCES `resource` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`profile_background`) REFERENCES `resource` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_ibfk_3` FOREIGN KEY (`display_language`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_ibfk_4` FOREIGN KEY (`translation_language`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_ibfk_5` FOREIGN KEY (`email_communication_language`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'vesilva24a@gmail.com','Administrador','81dc9bdb52d04dc20036dbd8313ed055',NULL,3,'2022-07-04 21:07:55',NULL,39,NULL,NULL,1,NULL,NULL,NULL,1),(5,'vesilva31a@gmail.com','Kurookami','81dc9bdb52d04dc20036dbd8313ed055','2022-07-09',1,'2022-07-09 13:31:04','The status!',107,108,'This is about me!',1,2,2,2,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_anime`
--

DROP TABLE IF EXISTS `user_anime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_anime` (
  `user` int(16) NOT NULL,
  `anime` int(16) NOT NULL,
  `status` int(2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user`,`anime`,`status`),
  KEY `anime` (`anime`),
  CONSTRAINT `user_anime_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_anime_ibfk_2` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_anime`
--

LOCK TABLES `user_anime` WRITE;
/*!40000 ALTER TABLE `user_anime` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_anime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `user` int(16) NOT NULL,
  `role` int(3) NOT NULL,
  PRIMARY KEY (`user`,`role`),
  KEY `role` (`role`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (4,1),(5,1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `anime` int(16) NOT NULL,
  `season` int(3) DEFAULT NULL,
  `video_type` int(2) NOT NULL,
  `numeration` int(6) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `synopsis` text DEFAULT NULL,
  `duration` int(7) NOT NULL,
  `opening_start` int(7) DEFAULT NULL,
  `opening_end` int(7) DEFAULT NULL,
  `ending_start` int(7) DEFAULT NULL,
  `ending_end` int(7) DEFAULT NULL,
  `path` int(24) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1)),
  `thumbnail` int(24) DEFAULT NULL,
  `release_date` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `anime` (`anime`),
  KEY `video_type` (`video_type`),
  KEY `path` (`path`),
  KEY `season` (`season`),
  KEY `thumbnail` (`thumbnail`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `video_ibfk_2` FOREIGN KEY (`anime`) REFERENCES `anime` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `video_ibfk_4` FOREIGN KEY (`video_type`) REFERENCES `video_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `video_ibfk_5` FOREIGN KEY (`path`) REFERENCES `resource` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `video_ibfk_6` FOREIGN KEY (`season`) REFERENCES `season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `video_ibfk_7` FOREIGN KEY (`thumbnail`) REFERENCES `resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
INSERT INTO `video` VALUES (1,1,NULL,1,1,'The Beggining','Ep 1',245,0,0,0,0,20,1,9,'2022-07-05'),(2,2,NULL,1,1,'Where it all began','ep 1',245,0,0,0,0,25,1,10,'2022-07-05'),(3,3,NULL,1,1,'War finally ov','Ep. 1',245,0,0,0,0,30,1,11,'2022-07-05'),(4,4,NULL,1,1,'Great heroes','Ep 1',245,0,0,0,0,35,1,12,'2022-07-05');
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_type`
--

DROP TABLE IF EXISTS `video_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_type` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_type`
--

LOCK TABLES `video_type` WRITE;
/*!40000 ALTER TABLE `video_type` DISABLE KEYS */;
INSERT INTO `video_type` VALUES (1,'Episódio'),(2,'OVA'),(3,'Filme');
/*!40000 ALTER TABLE `video_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-07-18 12:12:58
