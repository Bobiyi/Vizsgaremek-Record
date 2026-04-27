/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: vizsga_record_test
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `artist`
--

DROP TABLE IF EXISTS `artist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `active_since` year(4) DEFAULT year(curdate()),
  `nationality` varchar(64) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `is_group` bit(1) DEFAULT b'0',
  `icon_path` varchar(255) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_Name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist`
--

LOCK TABLES `artist` WRITE;
/*!40000 ALTER TABLE `artist` DISABLE KEYS */;
INSERT INTO `artist` VALUES
(1,'Metallica',1981,'usa','https://www.metallica.com',0x01,'Artists/MetallicaIcon.jpg','Artists/MetallicaBanner.jpg'),
(2,'Michael Jackson',1964,'usa','https://www.michaeljackson.com',0x00,'Artists/MichaelJacksonIcon.jpg','Artists/MichaelJacksonBanner.jpg'),
(3,'Playboi Carti',2015,'usa','https://soundcloud.com/playboicarti',0x00,'Artists/PlayboiCartiIcon.jpg','Artists/PlayboiCartiBanner.jpg'),
(4,'1000 Eyes',2021,NULL,'https://thousandeyes.bandcamp.com',0x00,'Artists/1000EyesIcon.jpg','Artists/1000EyesBanner.jpg'),
(5,'Masayoshi Takanaka',1970,'jpn','https://takanaka.com',0x00,'Artists/MasayoshiTakanakaIcon.jpg','Artists/MasayoshiTakanakaBanner.jpg'),
(6,'Kárpátia',2003,'hun','https://www.karpatiazenekar.hu',0x00,'Artists/KarpatiaIcon.jpg','Artists/KarpatiaBanner.jpg'),
(7,'Korda György',1958,'hun',NULL,0x00,'Artists/KordaGyorgyIcon.jpg','Artists/KordaGyorgyBanner.jpg'),
(8,'The Neighbourhood',2011,'usa','https://tour.thenbhd.com',0x01,'Artists/TheNeighbourhoodIcon.jpg','Artists/TheNeighbourhoodBanner.jpg'),
(9,'Astrophysics',2018,'bra','https://astrophysicsbrazil.bandcamp.com/music',0x01,'Artists/AstrophysicsIcon.jpg','Artists/AstrophysicsBanner.jpg'),
(10,'TV Girl',2013,'usa','https://tvgirl.bandcamp.com',0x01,'Artists/TVGirlIcon.jpg','Artists/TVGirlBanner.jpg'),
(11,'Jordana',2018,'usa','https://jordana.cool',0x00,'Artists/JordanaIcon.jpg','Artists/JordanaBanner.jpg'),
(12,'Julie',2020,'usa','https://julie.bandcamp.com',0x01,'Artists/JulieIcon.jpg','Artists/JulieBanner.jpg'),
(13,'WEDNESDAY CAMPANELLA',2013,'jpn',NULL,0x01,'Artists/WednesdayCampanellaIcon.jpg','Artists/WednesdayCampanellaBanner.jpg'),
(14,'MASS OF THE FERMENTING DREGS',2006,'jpn','https://www.motfd.com/',0x01,'Artists/MassOfTheFermentingDregsIcon.jpg','Artists/MassOfTheFermentingDregsBanner.jpg'),
(15,'mollywood',2024,'hun','https://astromusic.hu/band/mollywood/',0x00,'Artists/MollywoodIcon.jpg','Artists/MollywoodBanner.jpg'),
(16,'Irina',2023,'hun','https://soundcloud.com/edina-nagy-865719925',0x00,'Artists/IrinaIcon.jpg','Artists/IrinaBanner.jpg'),
(17,'The Marías',2017,'pri','https://www.themarias.us',0x01,'Artists/TheMariasIcon.jpg','Artists/TheMariasBanner.jpg'),
(18,'Broken Social Scene',2001,'can','https://drop.cobrand.com/d/BrokenSocialScene/BrokenSocialScene',0x01,'Artists/BrokenSocialSceneIcon.jpg','Artists/BrokenSocialSceneBanner.jpg'),
(19,'jschlatt',2024,'usa',NULL,0x00,'Artists/JschlattIcon.jpg','Artists/JschlattBanner.jpg'),
(20,'Luca Maxim',2019,'geo','https://soundcloud.com/lucamaxim',0x00,'Artists/LucaMaximIcon.jpg','Artists/LucaMaximBanner.jpg'),
(21,'Nirvana',1989,'usa','https://www.nirvana.com',0x01,'Artists/NirvanaIcon.jpg','Artists/NirvanaBanner.jpg'),
(22,'Tyler, The Creator',2011,'usa','https://soundcloud.com/tylerthecreatorofficial',0x00,'Artists/TylerTheCreatorIcon.jpg','Artists/TylerTheCreatorBanner.jpg'),
(23,'Frank Sinatra',1935,'usa','https://www.sinatra.com',0x00,'Artists/FrankSinatraIcon.jpg','Artists/FrankSinatraBanner.jpg'),
(24,'The Cranberries',2009,'irl','https://www.cranberries.com',0x01,'Artists/TheCranberriesIcon.jpg','Artists/TheCranberriesBanner.jpg'),
(25,'bôa',1993,'gbr','https://www.boaukofficial.com',0x01,'Artists/boaIcon.jpg','Artists/boaBanner.jpg'),
(26,'what is your name?',2022,'can','https://whatisyourname.bandcamp.com/music',0x00,'Artists/WhatIsYourNameIcon.jpg','Artists/WhatIsYourNameBanner.jpg');
/*!40000 ALTER TABLE `artist` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_hungarian_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger trg_insert_artist_year_check BEFORE INSERT on artist
for each row
BEGIN
	IF NEW.active_since > year(CURRENT_DATE()) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Active since cannot be in the future';
	end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `artist_record`
--

DROP TABLE IF EXISTS `artist_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artist_record` (
  `artist_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `role` enum('featured','producer') DEFAULT NULL,
  PRIMARY KEY (`artist_id`,`record_id`),
  KEY `frk_Record_ID` (`record_id`),
  KEY `idx_Record_Artist` (`artist_id`,`record_id`),
  CONSTRAINT `frk_Artist_ID` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`id`) ON DELETE CASCADE,
  CONSTRAINT `frk_Record_ID` FOREIGN KEY (`record_id`) REFERENCES `record` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist_record`
--

LOCK TABLES `artist_record` WRITE;
/*!40000 ALTER TABLE `artist_record` DISABLE KEYS */;
INSERT INTO `artist_record` VALUES
(1,1,'producer'),
(1,2,'producer'),
(1,3,'producer'),
(2,4,'producer'),
(2,5,'producer'),
(3,6,'producer'),
(3,7,'producer'),
(3,8,'producer'),
(4,9,'producer'),
(4,10,'producer'),
(4,11,'producer'),
(5,12,'producer'),
(5,13,'producer'),
(5,14,'producer'),
(5,15,'producer'),
(6,16,'producer'),
(6,17,'producer'),
(7,18,'producer'),
(8,19,'producer'),
(8,20,'producer'),
(8,21,'producer'),
(9,22,'producer'),
(10,23,'producer'),
(10,24,'producer'),
(10,25,'featured'),
(10,48,'producer'),
(10,49,'producer'),
(10,50,'producer'),
(10,51,'producer'),
(11,25,'producer'),
(12,26,'producer'),
(12,27,'producer'),
(12,28,'producer'),
(12,29,'producer'),
(13,30,'producer'),
(13,31,'producer'),
(13,32,'producer'),
(14,33,'producer'),
(14,34,'producer'),
(15,35,'producer'),
(15,36,'producer'),
(16,37,'producer'),
(16,38,'producer'),
(16,39,'producer'),
(17,40,'producer'),
(17,41,'producer'),
(17,42,'producer'),
(18,43,'producer'),
(18,44,'producer'),
(18,45,'producer'),
(18,46,'producer'),
(18,47,'producer'),
(19,52,'producer'),
(19,53,'producer'),
(20,54,'producer'),
(20,55,'producer'),
(20,56,'producer'),
(20,57,'producer'),
(21,58,'producer'),
(21,59,'producer'),
(21,60,'producer'),
(21,61,'producer'),
(21,62,'producer'),
(22,63,'producer'),
(22,64,'producer'),
(22,65,'producer'),
(22,66,'producer'),
(22,67,'producer'),
(22,68,'producer'),
(22,69,'producer'),
(22,70,'producer'),
(23,71,'producer'),
(23,72,'producer'),
(24,73,'producer'),
(24,74,'producer'),
(25,75,'producer'),
(25,76,'producer'),
(26,77,'producer'),
(26,78,'producer'),
(26,79,'producer'),
(26,80,'producer'),
(26,81,'producer');
/*!40000 ALTER TABLE `artist_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourite`
--

DROP TABLE IF EXISTS `favourite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `favourite` (
  `user_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`record_id`),
  KEY `frk_Favourite_Record_ID` (`record_id`),
  KEY `idx_Favourite` (`user_id`,`record_id`),
  CONSTRAINT `frk_Favourite_Record_ID` FOREIGN KEY (`record_id`) REFERENCES `record` (`id`),
  CONSTRAINT `frk_User_ID` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourite`
--

LOCK TABLES `favourite` WRITE;
/*!40000 ALTER TABLE `favourite` DISABLE KEYS */;
INSERT INTO `favourite` VALUES
(9,1),
(10,1);
/*!40000 ALTER TABLE `favourite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',12,'api-token','4855b2bb07bb62d07f9c38b84a7c6a0bd59d6c3e0ad79cd29192edab8502c383','[\"*\"]',NULL,'2026-04-30 05:20:47','2026-04-27 05:20:47','2026-04-27 05:20:47');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `release_year` year(4) DEFAULT year(curdate()),
  `length` int(11) DEFAULT 1,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frk_Type` (`type_id`),
  CONSTRAINT `frk_Type` FOREIGN KEY (`type_id`) REFERENCES `record_type` (`id`),
  CONSTRAINT `chk_Length` CHECK (`length` > 0)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `record`
--

LOCK TABLES `record` WRITE;
/*!40000 ALTER TABLE `record` DISABLE KEYS */;
INSERT INTO `record` VALUES
(1,'Master Of Puppets',1,1986,8,'Records/MasterOfPuppets.jpg'),
(2,'Ride The Lightning',1,1984,8,'Records/RideTheLightning.jpg'),
(3,'Reload',1,1997,13,'Records/Reload.jpg'),
(4,'Bad',1,1987,9,'Records/Bad.jpg'),
(5,'Dangerous',1,1991,9,'Records/Dangerous.jpg'),
(6,'Whole Lotta Red',1,2020,24,'Records/WholeLottaRed.jpg'),
(7,'Die Lit',1,2018,19,'Records/DieLit.jpg'),
(8,'MUSIC',1,2025,30,'Records/MUSIC.jpg'),
(9,'1000 Eyes',1,2021,13,'Records/1000Eyes.jpg'),
(10,'Duality',1,2024,15,'Records/Duality.jpg'),
(11,'SIGNALIS: MEMORIES',1,2024,7,'Records/SignalisMemories.jpg'),
(12,'THE RAINBOW GOBLINS',1,1981,14,'Records/TheRainbowGoblins.jpg'),
(13,'FINGER DANCIN\'',2,2006,4,'Records/FingerDancin.jpg'),
(14,'BRASILIAN SKIES',1,1978,8,'Records/BrasilianSkies.jpg'),
(15,'ALL OF ME',1,2006,14,'Records/AllOfMe.jpg'),
(16,'Bátraké a szerencse',1,2014,11,'Records/BatrakeASzerencse.jpg'),
(17,'A Száműzött',1,2013,13,'Records/ASzamuzott.jpg'),
(18,'Napfény kell a világnak - Tegnap és ma',1,2005,15,'Records/NapfenyKellAVilagnakTegnapEsMa.jpg'),
(19,'(((((ultraSOUND)))))',1,2025,15,'Records/UltraSound.jpg'),
(20,'Hard To Imagine The Neighbourhood Ever Changing',1,2018,21,'Records/HardToImagineTheNeighbourhoodEverChanging.jpg'),
(21,'Thank you,',3,2012,2,'Records/Thankyou.jpg'),
(22,'HOPE LEFT ME',1,2022,12,'Records/HopeLeftMe.jpg'),
(23,'Who Really Cares',1,2016,10,'Records/WhoReallyCares.jpg'),
(24,'Fauxllennium',1,2024,7,'Records/Fauxllennium.jpg'),
(25,'Summer\'s Over',1,2021,7,'Records/SummersOver.jpg'),
(26,'flutter',3,2020,1,'Records/Flutter.jpg'),
(27,'starjump/kit',3,2020,2,'Records/StarjumpKit.jpg'),
(28,'pushing daisies',2,2021,6,'Records/PushingDaisies.jpg'),
(29,'my anti-aircraft friend',1,2024,10,'Records/MyAntiAircraftFriend.jpg'),
(30,'Kawaii girl',1,2025,8,'Records/KawaiiGirl.jpg'),
(31,'POP DELIVERY',1,2024,8,'Records/PopDelivery.jpg'),
(32,'Summer Time Ghost',3,2025,1,'Records/SummerTimeGhost.jpg'),
(33,'World Is Yours',2,2009,6,'Records/WorldIsYours.jpg'),
(34,'MASS OF THE FERMENTING DREGS',2,2008,6,'Records/MassOfTheFermentingDregs.jpg'),
(35,'Larissza Radio',1,2025,9,'Records/LarisszaRadio.jpg'),
(36,'Europa',1,2025,11,'Records/Europa.jpg'),
(37,'gyógynövény',3,2023,1,'Records/Gyogynoveny.jpg'),
(38,'dohányozni tilos',1,2024,9,'Records/DohanyozniTilos.jpg'),
(39,'halovány',3,2025,1,'Records/Halovany.jpg'),
(40,'Submarine',1,2024,14,'Records/Submarine.jpg'),
(41,'CINEMA',1,2021,13,'Records/Cinema.jpg'),
(42,'No One Noticed',3,2024,1,'Records/NoOneNoticed.jpg'),
(43,'Feel Good Lost',1,2001,12,'Records/FeelGoodLost.jpg'),
(44,'Anthems For A Seventeen Year-Old Girl',3,2002,1,'Records/AnthemsForASeventeenYearOldGirl.jpg'),
(45,'You Forgot It In People',1,2013,13,'Records/YouForgotItInPeople.jpg'),
(46,'Bee Hives',1,2004,9,'Records/BeeHives.jpg'),
(47,'Broken Social Scene',1,2005,14,'Records/BrokenSocialScene.jpg'),
(48,'French Exit',1,2014,12,'Records/FrenchExit.jpg'),
(49,'The Night in Question: French Exit Outtakes',1,2020,8,'Records/TheNightInQuestion.jpg'),
(50,'Grapes Upon The Vine',1,2023,12,'Records/GrapesUponTheVine.jpg'),
(51,'Death of a Party Girl',1,2018,10,'Records/DeathOfAPartyGirl.jpg'),
(52,'A Very 1999 Christmas',1,2024,8,'Records/AVery1999Christmas.jpg'),
(53,'A Very 1999 Christmas (Deluxe)',1,2025,11,'Records/AVery1999ChristmasDeluxe.jpg'),
(54,'i can\'t feel a thing',3,2026,1,'Records/ICantFeelAThing.jpg'),
(55,'Mr. Worldwide',3,2025,1,'Records/MrWorldwide.jpg'),
(56,'Azerbaijan Technology',3,2025,1,'Records/AzerbaijanTechnology.jpg'),
(57,'Nearly Cried of Happiness',1,2023,13,'Records/NearlyCriedOfHappiness.jpg'),
(58,'Bleach',1,1989,13,'Records/Bleach.jpg'),
(59,'Nevermind',1,1991,13,'Records/Nevermind.jpg'),
(60,'In Utero',1,1993,12,'Records/InUtero.jpg'),
(61,'MTV Unplugged In New York',1,1994,14,'Records/MTVUnpluggedInNewYork.jpg'),
(62,'Incesticide',1,1992,15,'Records/Incesticide.jpg'),
(63,'DON\'T TAP THE GLASS',1,2025,10,'Records/DontTapTheGlass.jpg'),
(64,'CHROMAKOPIA',1,2024,14,'Records/Chromakopia.jpg'),
(65,'CALL ME IF YOU GET LOST: The Estate Sale',1,2023,24,'Records/CallMeIfYouGetLostTheEstateSale.jpg'),
(66,'CALL ME IF YOU GET LOST',1,2021,16,'Records/CallMeIfYouGetLost.jpg'),
(67,'IGOR',1,2019,12,'Records/Igor.jpg'),
(68,'Flower Boy',1,2017,14,'Records/FlowerBoy.jpg'),
(69,'Cherry Bomb',1,2015,13,'Records/CherryBomb.jpg'),
(70,'Wolf',1,2013,18,'Records/Wolf.jpg'),
(71,'That\'s Life',1,1966,10,'Records/ThatsLife.jpg'),
(72,'My Way',1,1969,12,'Records/MyWay.jpg'),
(73,'Everybody Else Is Doing It, So Why Can\'t We?',1,1993,12,'Records/EverybodyElseIsDoingItSoWhyCantWe.jpg'),
(74,'No Need To Argue',1,1994,13,'Records/NoNeedToArgue.jpg'),
(75,'Twilight',1,2001,14,'Records/Twilight.jpg'),
(76,'Whiplash',3,2024,4,'Records/Whiplash.jpg'),
(77,'the now now and never',1,2022,8,'Records/TheNowNowAndNever.jpg'),
(78,'My Name Is...',1,2023,9,'Records/MyNameIs.jpg'),
(79,'Rabbit EP',2,2023,3,'Records/RabbitEP.jpg'),
(80,'beyond old names, everyone\'s songs.',1,2023,12,'Records/BeyondOldNamesEveryonesSongs.jpg'),
(81,'1 Side Rd',2,2022,4,'Records/1SideRd.jpg'),
(82,'Request To Accept 69ef0e4e3df4b',1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `record` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_hungarian_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger trg_insert_record_year_check BEFORE INSERT on record
for each row
BEGIN
	IF NEW.release_year > year(CURRENT_DATE()) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Release year cannot be in the future';
	end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `record_type`
--

DROP TABLE IF EXISTS `record_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `record_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_Type` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `record_type`
--

LOCK TABLES `record_type` WRITE;
/*!40000 ALTER TABLE `record_type` DISABLE KEYS */;
INSERT INTO `record_type` VALUES
(1,'Album'),
(2,'EP'),
(3,'Single');
/*!40000 ALTER TABLE `record_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('new_artist','new_record','edit_artist','edit_record') NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `admin_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_Request_User` (`user_id`),
  KEY `idx_Request_Status` (`status`),
  CONSTRAINT `frk_Request_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES
(1,2,'new_record','{\"recordName\":\"Queue Record 69ef0e4e20d87\",\"typeId\":1,\"releaseYear\":2020,\"length\":10}','pending',NULL,'2026-04-27 07:20:46',NULL),
(2,3,'new_artist','{\"artistName\":\"Pending Artist 69ef0e4e2e24c\",\"isGroup\":0}','pending',NULL,'2026-04-27 07:20:46',NULL),
(3,4,'new_record','{\"recordName\":\"Request To Accept 69ef0e4e36517\",\"typeId\":1}','pending',NULL,'2026-04-27 07:20:46',NULL),
(4,5,'new_record','{\"recordName\":\"Request To Accept 69ef0e4e3df4b\",\"typeId\":1}','accepted','Allowed for Admins.','2026-04-27 07:20:46','2026-04-27 05:20:46'),
(5,6,'new_artist','{\"artistName\":\"Request To Reject 69ef0e4e49aa6\",\"isGroup\":1}','rejected','Rejected in test','2026-04-27 07:20:46','2026-04-27 05:20:46');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password_hash` varchar(128) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` date DEFAULT curdate(),
  `updated_at` date DEFAULT curdate(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_Email` (`email`),
  UNIQUE KEY `uc_Phone` (`phone`),
  KEY `idx_User_Name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'admin1234','admin','admin@record.hu','06309353729','admin','2026-04-27','2026-04-27'),
(2,'$2y$04$z2Ix2o9vLi4GyX95Ld77beJafZOJDJafA.b46JG3A7skDzO4t0V2K','queue-user-69ef0e4e1988f','queue-69ef0e4e19890@example.com','80657450','user','2026-04-27','2026-04-27'),
(3,'$2y$04$fE6BsKCi2CotptNIltOB5ON5YZQ/Q0cdANVOawqilxSgBJqISdovq','queue-list-user-69ef0e4e26f15','queue-list-69ef0e4e26f16@example.com','23298948','admin','2026-04-27','2026-04-27'),
(4,'$2y$04$vWc49QBQ3vdxCv5I7SoRZO3CLiTIeI2lTwrZ0H9oQXYuc8CkdC25m','queue-request-user-69ef0e4e35b1b','queue-request-69ef0e4e35b1c@example.com','93295797','user','2026-04-27','2026-04-27'),
(5,'$2y$04$GUjEnR8NpYtIB6S/DWvAuOVXYsI6XeQig8apZZfZDFviXFJ6gv2c6','queue-request-user-69ef0e4e3d530','queue-request-69ef0e4e3d532@example.com','27674694','admin','2026-04-27','2026-04-27'),
(6,'$2y$04$zAs.tGhiMjSTT/DSH9rwJOaMH5wcTLinJ4J5dtfN8u1S9rIcthxja','queue-reject-user-69ef0e4e430d3','queue-reject-69ef0e4e430d4@example.com','45802253','user','2026-04-27','2026-04-27'),
(7,'$2y$04$QGiDNP4kcP.DtZAzaSsDXessvmfvkOmVmL2xDwDMQp8WEOJJ4yDfK','queue-admin-69ef0e4e4918d','queue-admin-69ef0e4e4918f@example.com','79121707','admin','2026-04-27','2026-04-27'),
(8,'$2y$04$nw1CUBdi8Mno.NhHiBBJcetX8pfHb8xTnj6xlPuvhkimgE1b6H3OS','test-user-69ef0e4ec7e3f','test-69ef0e4ec7e41@example.com','45116961','user','2026-04-27','2026-04-27'),
(9,'$2y$04$hr24MAHzixdIrEDMOpO0Fea74WKCWjAM0vYQEW38vecco7kJzsgF6','test-user-69ef0e4ed79b5','test-69ef0e4ed79b6@example.com','79315282','user','2026-04-27','2026-04-27'),
(10,'$2y$04$1R8MaEHC730FE3PRrl98ZexkFAoYDpyA4LNYbHR//FBeBYsg4Fzca','test-user-69ef0e4eeb3a4','test-69ef0e4eeb3a5@example.com','96207716','user','2026-04-27','2026-04-27'),
(11,'$2y$04$13j.V6rouZLoxTuhO.vknOFkJwCSHecxFGhApqjeziXOA9KlphlVm','register-user-69ef0e4f0d814','register-69ef0e4f0d814@example.com','54241064','user','2026-04-27','2026-04-27'),
(12,'$2y$04$XrwaEQ22q.b/jF8HRgEUKOHiZwblg4Y/G96LPRl9AxfXIcmCSaw8.','login-user-69ef0e4f185f9','login-69ef0e4f185f9@example.com','99762063','user','2026-04-27','2026-04-27'),
(13,'$2y$04$H4Fsj0NiUSXxDjkGjWnRq.iRlFH1e3Efv5s4DpkqiTfGzoIOQ10B2','wrong-pass-user-69ef0e4f23be7','wrong-pass-69ef0e4f23be7@example.com','44925371','user','2026-04-27','2026-04-27'),
(14,'$2y$04$PKh7VB/EhFtqG88TGIEifOWvAjialNeU9CqLGaFsLHZi9nsVRQHFy','normal-user-69ef0e4f353ea','normal-69ef0e4f353eb@example.com','32524218','user','2026-04-27','2026-04-27'),
(15,'$2y$04$0kXnAMz.3UeCrj3bDfUf2eS4W.RwJYlfdtZaj6X4sZlwcJ1qDTQ5C','admin-user-69ef0e4f3d06d','admin-69ef0e4f3d06e@example.com','84837975','admin','2026-04-27','2026-04-27'),
(16,'$2y$04$tn1BAkojtOcGJC.lwnxBH.R2DackbLt8NnarTPE.ZKpvsdbNLxeNu','logout-user-69ef0e4f448ee','logout-69ef0e4f448ee@example.com','64062849','user','2026-04-27','2026-04-27');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'vizsga_record_test'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-27  9:25:08
