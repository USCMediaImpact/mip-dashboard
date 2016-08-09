use media_impact;
-- MySQL dump 10.13  Distrib 5.7.9, for osx10.9 (x86_64)
--
-- Host: localhost    Database: media_impact
-- ------------------------------------------------------
-- Server version	5.7.11-4-log

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
-- Table structure for table `SCPR_data_quality_weekly`
--

DROP TABLE IF EXISTS `SCPR_data_quality_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SCPR_data_quality_weekly` (
  `date` date NOT NULL,
  `GA_Users` decimal(16,4) DEFAULT NULL,
  `MIP_Users` decimal(16,4) DEFAULT NULL,
  `I_inDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `K_inDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `I_notInDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `K_notInDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `I_newSubscriberCameThroughEmail` decimal(16,4) DEFAULT NULL,
  `K_newSubscriberCameThroughEmail` decimal(16,4) DEFAULT NULL,
  `I_SubscribersThisWeek` decimal(16,4) DEFAULT NULL,
  `K_SubscribersThisWeek` decimal(16,4) DEFAULT NULL,
  `I_NewSubscribers` decimal(16,4) DEFAULT NULL,
  `K_NewSubscribers` decimal(16,4) DEFAULT NULL,
  `I_TotalDatabaseSubscribers` decimal(16,4) DEFAULT NULL,
  `K_TotalDatabaseSubscribers` decimal(16,4) DEFAULT NULL,
  `K_PercentDatabaseSubscribersWhoCame` decimal(16,4) DEFAULT NULL,
  `EmailNewsletterClicks` decimal(16,4) DEFAULT NULL,
  `I_databaseDonorsWhoVisited` decimal(16,4) DEFAULT NULL,
  `K_databaseDonorsWhoVisited` decimal(16,4) DEFAULT NULL,
  `I_donatedOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `K_donatedOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `I_totalDonorsOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `K_totalDonorsOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `I_totalDonorsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_totalDonorsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseDonorsWhoCame` decimal(16,4) DEFAULT NULL,
  `K_individualsWhoCameThisWeek` decimal(16,4) DEFAULT NULL,
  `K_individualsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseIndividualsWhoCame` decimal(16,4) DEFAULT NULL,
  `Total_Identified_Donors_This_Week` decimal(16,4) DEFAULT NULL,
  `I_databaseMembersWhoVisited` decimal(16,4) DEFAULT NULL,
  `K_databaseMembersWhoVisited` decimal(16,4) DEFAULT NULL,
  `I_loggedInOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `K_loggedInOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `I_totalMembersOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `K_totalMembersOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `I_totalMembersInDatabase` decimal(16,4) DEFAULT NULL,
  `K_totalMembersInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseMembersWhoCame` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SCPR_data_stories_weekly`
--

DROP TABLE IF EXISTS `SCPR_data_stories_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SCPR_data_stories_weekly` (
  `date` date NOT NULL,
  `path_article_md5` varchar(200) NOT NULL,
  `Page_Path` varchar(2000) DEFAULT NULL,
  `Article` varchar(2000) DEFAULT NULL,
  `Pageviews` decimal(16,4) DEFAULT NULL,
  `Scroll_Start` decimal(16,4) DEFAULT NULL,
  `Scroll_25` decimal(16,4) DEFAULT NULL,
  `Scroll_50` decimal(16,4) DEFAULT NULL,
  `Scroll_75` decimal(16,4) DEFAULT NULL,
  `Scroll_100` decimal(16,4) DEFAULT NULL,
  `Scroll_Supplemental` decimal(16,4) DEFAULT NULL,
  `Scroll_End` decimal(16,4) DEFAULT NULL,
  `Time_15` decimal(16,4) DEFAULT NULL,
  `Time_30` decimal(16,4) DEFAULT NULL,
  `Time_45` decimal(16,4) DEFAULT NULL,
  `Time_60` decimal(16,4) DEFAULT NULL,
  `Time_75` decimal(16,4) DEFAULT NULL,
  `Time_90` decimal(16,4) DEFAULT NULL,
  `Comments` decimal(16,4) DEFAULT NULL,
  `Republish` decimal(16,4) DEFAULT NULL,
  `Emails` decimal(16,4) DEFAULT NULL,
  `Tweets` decimal(16,4) DEFAULT NULL,
  `Facebook_Recommendations` decimal(16,4) DEFAULT NULL,
  `Tribpedia_Related_Clicks` decimal(16,4) DEFAULT NULL,
  `Related_Clicks` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`,`path_article_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SCPR_data_users_weekly`
--

DROP TABLE IF EXISTS `SCPR_data_users_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SCPR_data_users_weekly` (
  `date` date NOT NULL,
  `TotalMembersThisWeek` decimal(16,4) DEFAULT NULL,
  `KPI_TotalMembersKnownToMIP` decimal(16,4) DEFAULT NULL,
  `CameToSiteThroughEmail` decimal(16,4) DEFAULT NULL,
  `KPI_TotalEmailSubscribersKnownToMIP` decimal(16,4) DEFAULT NULL,
  `KPI_PercentKnownSubsWhoCame` decimal(16,4) DEFAULT NULL,
  `NewEmailSubscribers` decimal(16,4) DEFAULT NULL,
  `TotalDonorsThisWeek` decimal(16,4) DEFAULT NULL,
  `KPI_TotalDonorsKnownToMIP` decimal(16,4) DEFAULT NULL,
  `Duplicated_CameThroughEmailPlusDonors` decimal(16,4) DEFAULT NULL,
  `Unduplicated_TotalUsersKPI` decimal(16,4) DEFAULT NULL,
  `Duplicated_Database_CameThroughEmailPlusDonors` decimal(16,4) DEFAULT NULL,
  `Unduplicated_Database_TotalUsersKPI` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TT_data_quality_weekly`
--

DROP TABLE IF EXISTS `TT_data_quality_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TT_data_quality_weekly` (
  `date` date NOT NULL,
  `GA_Users` decimal(16,4) DEFAULT NULL,
  `MIP_Users` decimal(16,4) DEFAULT NULL,
  `I_inDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `K_inDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `I_notInDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `K_notInDatabaseCameToSite` decimal(16,4) DEFAULT NULL,
  `I_newSubscriberCameThroughEmail` decimal(16,4) DEFAULT NULL,
  `K_newSubscriberCameThroughEmail` decimal(16,4) DEFAULT NULL,
  `I_SubscribersThisWeek` decimal(16,4) DEFAULT NULL,
  `K_SubscribersThisWeek` decimal(16,4) DEFAULT NULL,
  `I_NewSubscribers` decimal(16,4) DEFAULT NULL,
  `K_NewSubscribers` decimal(16,4) DEFAULT NULL,
  `I_TotalDatabaseSubscribers` decimal(16,4) DEFAULT NULL,
  `K_TotalDatabaseSubscribers` decimal(16,4) DEFAULT NULL,
  `K_PercentDatabaseSubscribersWhoCame` decimal(16,4) DEFAULT NULL,
  `EmailNewsletterClicks` decimal(16,4) DEFAULT NULL,
  `I_databaseDonorsWhoVisited` decimal(16,4) DEFAULT NULL,
  `K_databaseDonorsWhoVisited` decimal(16,4) DEFAULT NULL,
  `I_donatedOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `K_donatedOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `I_totalDonorsOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `K_totalDonorsOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `I_totalDonorsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_totalDonorsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseDonorsWhoCame` decimal(16,4) DEFAULT NULL,
  `K_individualsWhoCameThisWeek` decimal(16,4) DEFAULT NULL,
  `K_individualsInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseIndividualsWhoCame` decimal(16,4) DEFAULT NULL,
  `Total_Identified_Donors_This_Week` decimal(16,4) DEFAULT NULL,
  `I_databaseMembersWhoVisited` decimal(16,4) DEFAULT NULL,
  `K_databaseMembersWhoVisited` decimal(16,4) DEFAULT NULL,
  `I_loggedInOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `K_loggedInOnSiteForFirstTime` decimal(16,4) DEFAULT NULL,
  `I_totalMembersOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `K_totalMembersOnSiteThisWeek` decimal(16,4) DEFAULT NULL,
  `I_totalMembersInDatabase` decimal(16,4) DEFAULT NULL,
  `K_totalMembersInDatabase` decimal(16,4) DEFAULT NULL,
  `K_percentDatabaseMembersWhoCame` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TT_data_stories_weekly`
--

DROP TABLE IF EXISTS `TT_data_stories_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TT_data_stories_weekly` (
  `date` date NOT NULL,
  `path_article_md5` varchar(200) NOT NULL,
  `Combo_URL` varchar(2000) DEFAULT NULL,
  `Article` varchar(2000) DEFAULT NULL,
  `Pageviews` decimal(16,4) DEFAULT NULL,
  `Scroll_Start` decimal(16,4) DEFAULT NULL,
  `Scroll_25` decimal(16,4) DEFAULT NULL,
  `Scroll_50` decimal(16,4) DEFAULT NULL,
  `Scroll_75` decimal(16,4) DEFAULT NULL,
  `Scroll_100` decimal(16,4) DEFAULT NULL,
  `Scroll_Supplemental` decimal(16,4) DEFAULT NULL,
  `Scroll_End` decimal(16,4) DEFAULT NULL,
  `Time_15` decimal(16,4) DEFAULT NULL,
  `Time_30` decimal(16,4) DEFAULT NULL,
  `Time_45` decimal(16,4) DEFAULT NULL,
  `Time_60` decimal(16,4) DEFAULT NULL,
  `Time_75` decimal(16,4) DEFAULT NULL,
  `Time_90` decimal(16,4) DEFAULT NULL,
  `Comments` decimal(16,4) DEFAULT NULL,
  `Republish` decimal(16,4) DEFAULT NULL,
  `Emails` decimal(16,4) DEFAULT NULL,
  `Tweets` decimal(16,4) DEFAULT NULL,
  `Facebook_Recommendations` decimal(16,4) DEFAULT NULL,
  `Tribpedia_Related_Clicks` decimal(16,4) DEFAULT NULL,
  `Related_Clicks` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`,`path_article_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TT_data_users_weekly`
--

DROP TABLE IF EXISTS `TT_data_users_weekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TT_data_users_weekly` (
  `date` date NOT NULL,
  `TotalMembersThisWeek` decimal(16,4) DEFAULT NULL,
  `KPI_TotalMembersKnownToMIP` decimal(16,4) DEFAULT NULL,
  `CameToSiteThroughEmail` decimal(16,4) DEFAULT NULL,
  `KPI_TotalEmailSubscribersKnownToMIP` decimal(16,4) DEFAULT NULL,
  `KPI_PercentKnownSubsWhoCame` decimal(16,4) DEFAULT NULL,
  `KPI_NewEmailSubscribers` decimal(16,4) DEFAULT NULL,
  `TotalDonorsThisWeek` decimal(16,4) DEFAULT NULL,
  `KPI_TotalDonorsKnownToMIP` decimal(16,4) DEFAULT NULL,
  `Duplicated_MembersPlusCameThroughEmailPlusDonors` decimal(16,4) DEFAULT NULL,
  `Unduplicated_TotalUsersKPI` decimal(16,4) DEFAULT NULL,
  `Duplicated_Database_MembersPlusCameThroughEmailPlusDonors` decimal(16,4) DEFAULT NULL,
  `Unduplicated_Database_TotalUsersKPI` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analyses`
--

DROP TABLE IF EXISTS `analyses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analyses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_id` varchar(200) DEFAULT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_name` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `screen_shot` varchar(45) DEFAULT NULL,
  `path` varchar(2000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `website` varchar(500) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `ready` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_views`
--

DROP TABLE IF EXISTS `page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_views` (
  `date` date NOT NULL,
  `pv` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `values` text,
  `enable_sync` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-30  4:42:23
