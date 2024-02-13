-- MariaDB dump 10.19-11.2.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: database.internal    Database: main
-- ------------------------------------------------------
-- Server version	10.6.16-MariaDB-1:10.6.16+maria~deb10-log

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
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20240210145638','2024-02-11 18:17:00',107),
('DoctrineMigrations\\Version20240210182430','2024-02-11 18:17:00',30),
('DoctrineMigrations\\Version20240210221118','2024-02-11 18:17:00',287),
('DoctrineMigrations\\Version20240211111245','2024-02-11 18:17:01',50),
('DoctrineMigrations\\Version20240211123450','2024-02-11 18:17:01',64);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pokemon`
--

DROP TABLE IF EXISTS `pokemon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pokemon` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type1` varchar(255) NOT NULL,
  `type2` varchar(255) DEFAULT NULL,
  `sprite_url` varchar(255) DEFAULT NULL,
  `generation` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pokemon`
--

LOCK TABLES `pokemon` WRITE;
/*!40000 ALTER TABLE `pokemon` DISABLE KEYS */;
INSERT INTO `pokemon` VALUES
(1,'bulbasaur','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png',1),
(2,'ivysaur','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/2.png',1),
(3,'venusaur','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/3.png',1),
(4,'charmander','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/4.png',1),
(5,'charmeleon','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/5.png',1),
(6,'charizard','fire','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/6.png',1),
(7,'squirtle','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/7.png',1),
(8,'wartortle','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/8.png',1),
(9,'blastoise','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/9.png',1),
(10,'caterpie','bug',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/10.png',1),
(11,'metapod','bug',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/11.png',1),
(12,'butterfree','bug','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/12.png',1),
(13,'weedle','bug','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/13.png',1),
(14,'kakuna','bug','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/14.png',1),
(15,'beedrill','bug','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/15.png',1),
(16,'pidgey','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/16.png',1),
(17,'pidgeotto','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/17.png',1),
(18,'pidgeot','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/18.png',1),
(19,'rattata','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/19.png',1),
(20,'raticate','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/20.png',1),
(21,'spearow','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/21.png',1),
(22,'fearow','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/22.png',1),
(23,'ekans','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/23.png',1),
(24,'arbok','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/24.png',1),
(25,'pikachu','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png',1),
(26,'raichu','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/26.png',1),
(27,'sandshrew','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/27.png',1),
(28,'sandslash','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/28.png',1),
(29,'nidoran-f','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/29.png',1),
(30,'nidorina','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/30.png',1),
(31,'nidoqueen','poison','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/31.png',1),
(32,'nidoran-m','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/32.png',1),
(33,'nidorino','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/33.png',1),
(34,'nidoking','poison','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/34.png',1),
(35,'clefairy','fairy',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/35.png',1),
(36,'clefable','fairy',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/36.png',1),
(37,'vulpix','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/37.png',1),
(38,'ninetales','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/38.png',1),
(39,'jigglypuff','normal','fairy','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/39.png',1),
(40,'wigglytuff','normal','fairy','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/40.png',1),
(41,'zubat','poison','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/41.png',1),
(42,'golbat','poison','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/42.png',1),
(43,'oddish','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/43.png',1),
(44,'gloom','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/44.png',1),
(45,'vileplume','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/45.png',1),
(46,'paras','bug','grass','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/46.png',1),
(47,'parasect','bug','grass','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/47.png',1),
(48,'venonat','bug','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/48.png',1),
(49,'venomoth','bug','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/49.png',1),
(50,'diglett','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/50.png',1),
(51,'dugtrio','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/51.png',1),
(52,'meowth','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/52.png',1),
(53,'persian','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/53.png',1),
(54,'psyduck','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/54.png',1),
(55,'golduck','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/55.png',1),
(56,'mankey','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/56.png',1),
(57,'primeape','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/57.png',1),
(58,'growlithe','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/58.png',1),
(59,'arcanine','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/59.png',1),
(60,'poliwag','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/60.png',1),
(61,'poliwhirl','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/61.png',1),
(62,'poliwrath','water','fighting','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/62.png',1),
(63,'abra','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/63.png',1),
(64,'kadabra','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/64.png',1),
(65,'alakazam','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/65.png',1),
(66,'machop','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/66.png',1),
(67,'machoke','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/67.png',1),
(68,'machamp','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/68.png',1),
(69,'bellsprout','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/69.png',1),
(70,'weepinbell','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/70.png',1),
(71,'victreebel','grass','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/71.png',1),
(72,'tentacool','water','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/72.png',1),
(73,'tentacruel','water','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/73.png',1),
(74,'geodude','rock','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/74.png',1),
(75,'graveler','rock','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/75.png',1),
(76,'golem','rock','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/76.png',1),
(77,'ponyta','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/77.png',1),
(78,'rapidash','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/78.png',1),
(79,'slowpoke','water','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/79.png',1),
(80,'slowbro','water','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/80.png',1),
(81,'magnemite','electric','steel','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/81.png',1),
(82,'magneton','electric','steel','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/82.png',1),
(83,'farfetchd','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/83.png',1),
(84,'doduo','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/84.png',1),
(85,'dodrio','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/85.png',1),
(86,'seel','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/86.png',1),
(87,'dewgong','water','ice','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/87.png',1),
(88,'grimer','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/88.png',1),
(89,'muk','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/89.png',1),
(90,'shellder','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/90.png',1),
(91,'cloyster','water','ice','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/91.png',1),
(92,'gastly','ghost','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/92.png',1),
(93,'haunter','ghost','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/93.png',1),
(94,'gengar','ghost','poison','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/94.png',1),
(95,'onix','rock','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/95.png',1),
(96,'drowzee','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/96.png',1),
(97,'hypno','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/97.png',1),
(98,'krabby','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/98.png',1),
(99,'kingler','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/99.png',1),
(100,'voltorb','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/100.png',1),
(101,'electrode','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/101.png',1),
(102,'exeggcute','grass','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/102.png',1),
(103,'exeggutor','grass','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/103.png',1),
(104,'cubone','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/104.png',1),
(105,'marowak','ground',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/105.png',1),
(106,'hitmonlee','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/106.png',1),
(107,'hitmonchan','fighting',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/107.png',1),
(108,'lickitung','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/108.png',1),
(109,'koffing','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/109.png',1),
(110,'weezing','poison',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/110.png',1),
(111,'rhyhorn','ground','rock','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/111.png',1),
(112,'rhydon','ground','rock','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/112.png',1),
(113,'chansey','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/113.png',1),
(114,'tangela','grass',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/114.png',1),
(115,'kangaskhan','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/115.png',1),
(116,'horsea','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/116.png',1),
(117,'seadra','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/117.png',1),
(118,'goldeen','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/118.png',1),
(119,'seaking','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/119.png',1),
(120,'staryu','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/120.png',1),
(121,'starmie','water','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/121.png',1),
(122,'mr-mime','psychic','fairy','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/122.png',1),
(123,'scyther','bug','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/123.png',1),
(124,'jynx','ice','psychic','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/124.png',1),
(125,'electabuzz','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/125.png',1),
(126,'magmar','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/126.png',1),
(127,'pinsir','bug',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/127.png',1),
(128,'tauros','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/128.png',1),
(129,'magikarp','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/129.png',1),
(130,'gyarados','water','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/130.png',1),
(131,'lapras','water','ice','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/131.png',1),
(132,'ditto','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/132.png',1),
(133,'eevee','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/133.png',1),
(134,'vaporeon','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/134.png',1),
(135,'jolteon','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/135.png',1),
(136,'flareon','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/136.png',1),
(137,'porygon','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/137.png',1),
(138,'omanyte','rock','water','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/138.png',1),
(139,'omastar','rock','water','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/139.png',1),
(140,'kabuto','rock','water','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/140.png',1),
(141,'kabutops','rock','water','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/141.png',1),
(142,'aerodactyl','rock','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/142.png',1),
(143,'snorlax','normal',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/143.png',1),
(144,'articuno','ice','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/144.png',1),
(145,'zapdos','electric','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/145.png',1),
(146,'moltres','fire','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/146.png',1),
(147,'dratini','dragon',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/147.png',1),
(148,'dragonair','dragon',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/148.png',1),
(149,'dragonite','dragon','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/149.png',1),
(150,'mewtwo','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/150.png',1),
(151,'mew','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/151.png',1),
(156,'quilava','fire',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/156.png',2),
(180,'flaaffy','electric',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/180.png',2),
(182,'bellossom','grass',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/182.png',2),
(192,'sunflora','grass',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/192.png',2),
(193,'yanma','bug','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/193.png',2),
(195,'quagsire','water','ground','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/195.png',2),
(219,'magcargo','fire','rock','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/219.png',2),
(249,'lugia','psychic','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/249.png',2),
(303,'mawile','steel','fairy','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/303.png',3),
(325,'spoink','psychic',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/325.png',3),
(368,'gorebyss','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/368.png',3),
(419,'floatzel','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/419.png',4),
(452,'drapion','poison','dark','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/452.png',4),
(642,'thundurus-incarnate','electric','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/642.png',5),
(651,'quilladin','grass',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/651.png',6),
(658,'greninja','water','dark','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/658.png',6),
(661,'fletchling','normal','flying','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/661.png',6),
(671,'florges','fairy',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/671.png',6),
(673,'gogoat','grass',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/673.png',6),
(692,'clauncher','water',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/692.png',6),
(709,'trevenant','ghost','grass','https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/709.png',6),
(764,'comfey','fairy',NULL,'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/764.png',7);
/*!40000 ALTER TABLE `pokemon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `generation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'robb@stark.com','[]','$2y$13$wPaw/oApEoOXGa60kIYqVewBWL7MrRyI/bd/mGfMPbglZt7HMy7M.',1),
(2,'baran78','[]','$2y$13$F22QBwvlMj4Zj0v69Rd.Cu6eYkZa1RHvLHf3W5JPg47mXs5HP.B.u',1),
(3,'maksnow18@gmail.com','[]','$2y$13$7TmYxVSCmDf/hPX7lD3fnOp9Qp7rGz5tCFrrdoKhwHq6oZIndh94e',2);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_pokemon`
--

DROP TABLE IF EXISTS `user_pokemon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_pokemon` (
  `user_id` int(11) NOT NULL,
  `pokemon_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`pokemon_id`),
  KEY `IDX_3AD18EF9A76ED395` (`user_id`),
  KEY `IDX_3AD18EF92FE71C3E` (`pokemon_id`),
  CONSTRAINT `FK_3AD18EF92FE71C3E` FOREIGN KEY (`pokemon_id`) REFERENCES `pokemon` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3AD18EF9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_pokemon`
--

LOCK TABLES `user_pokemon` WRITE;
/*!40000 ALTER TABLE `user_pokemon` DISABLE KEYS */;
INSERT INTO `user_pokemon` VALUES
(1,14),
(1,90),
(1,249),
(1,658),
(3,1),
(3,2),
(3,3),
(3,4),
(3,5),
(3,6),
(3,7),
(3,8),
(3,9),
(3,10),
(3,11),
(3,12),
(3,13),
(3,14),
(3,15),
(3,16),
(3,17),
(3,18),
(3,19),
(3,20),
(3,21),
(3,22),
(3,23),
(3,24),
(3,25),
(3,26),
(3,27),
(3,28),
(3,29),
(3,30),
(3,31),
(3,32),
(3,33),
(3,34),
(3,35),
(3,36),
(3,37),
(3,38),
(3,39),
(3,40),
(3,41),
(3,42),
(3,43),
(3,44),
(3,45),
(3,46),
(3,47),
(3,48),
(3,49),
(3,50),
(3,51),
(3,52),
(3,53),
(3,54),
(3,55),
(3,56),
(3,57),
(3,58),
(3,59),
(3,60),
(3,61),
(3,62),
(3,63),
(3,64),
(3,65),
(3,66),
(3,67),
(3,68),
(3,69),
(3,70),
(3,71),
(3,72),
(3,73),
(3,74),
(3,75),
(3,76),
(3,77),
(3,78),
(3,79),
(3,80),
(3,81),
(3,82),
(3,83),
(3,84),
(3,85),
(3,86),
(3,87),
(3,88),
(3,89),
(3,90),
(3,91),
(3,92),
(3,93),
(3,94),
(3,95),
(3,96),
(3,97),
(3,98),
(3,99),
(3,100),
(3,101),
(3,102),
(3,103),
(3,104),
(3,105),
(3,106),
(3,107),
(3,108),
(3,109),
(3,110),
(3,111),
(3,112),
(3,113),
(3,114),
(3,115),
(3,116),
(3,117),
(3,118),
(3,119),
(3,120),
(3,121),
(3,122),
(3,123),
(3,124),
(3,125),
(3,126),
(3,127),
(3,128),
(3,129),
(3,130),
(3,131),
(3,132),
(3,133),
(3,134),
(3,135),
(3,136),
(3,137),
(3,138),
(3,139),
(3,140),
(3,141),
(3,142),
(3,143),
(3,144),
(3,145),
(3,146),
(3,147),
(3,148),
(3,149),
(3,150),
(3,151);
/*!40000 ALTER TABLE `user_pokemon` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-12 14:52:00
