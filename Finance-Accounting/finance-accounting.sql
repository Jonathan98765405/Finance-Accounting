-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: finance_and_accounting
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `ap_activities`
--

DROP TABLE IF EXISTS `ap_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ap_activities_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `ap_activities_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `ap_invoices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_activities`
--

LOCK TABLES `ap_activities` WRITE;
/*!40000 ALTER TABLE `ap_activities` DISABLE KEYS */;
INSERT INTO `ap_activities` VALUES (1,1,'Invoice INV-2024-00125 from Global Supplies Co. received.','invoice_received','done','2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,2,'Payment of PHP 42,500 to Tech Solutions Inc. approved.','payment_approved','done','2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,9,'Payment scheduled for INV-3006 on July 24, 2026.','payment_scheduled','scheduled','2026-07-11 07:27:00','2026-07-11 07:27:00'),(4,10,'Payment of PHP 45,000 to Evergreen Supplies, completed.','payment_completed','done','2026-07-11 07:27:00','2026-07-11 07:27:00'),(5,1,'Three-way match completed for INV-2024-00125.','three_way_match','done','2026-07-11 07:27:00','2026-07-11 07:27:00'),(6,11,'Invoice INV-2026-777 from Tech Solutions Inc. received.','invoice_received','done','2026-07-11 07:50:05','2026-07-11 07:50:05'),(7,11,'Invoice INV-2026-777 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 07:50:37','2026-07-11 07:50:37'),(8,12,'Invoice INV-2026-999 from Global Supplies Co. received.','invoice_received','done','2026-07-11 07:57:48','2026-07-11 07:57:48'),(9,12,'Invoice INV-2026-999 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 07:57:56','2026-07-11 07:57:56'),(10,13,'Invoice INV-2026-111 from Prime Industrial received.','invoice_received','done','2026-07-11 08:11:39','2026-07-11 08:11:39'),(11,13,'Invoice INV-2026-111 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 08:11:47','2026-07-11 08:11:47'),(12,2,'Payment scheduled for INV-3002 on July 12, 2026.','payment_scheduled','scheduled','2026-07-11 08:17:09','2026-07-11 08:17:09'),(13,6,'Payment of PHP 85,000.00 to ABC Trading, completed.','payment_completed','done','2026-07-11 08:32:57','2026-07-11 08:32:57'),(14,2,'Payment of PHP 42,500.00 to Tech Solutions Inc., completed.','payment_completed','done','2026-07-11 08:33:00','2026-07-11 08:33:00'),(15,9,'Payment of PHP 73,500.00 to Blue Ocean Corp., completed.','payment_completed','done','2026-07-11 08:33:02','2026-07-11 08:33:02'),(16,14,'Invoice INV-2026-987 from Evergreen Supplies received.','invoice_received','done','2026-07-11 08:44:25','2026-07-11 08:44:25'),(17,14,'Invoice INV-2026-987 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 08:44:39','2026-07-11 08:44:39'),(18,NULL,'Purchase order PO-2026-123 created for Tech Solutions Inc..','po_created','done','2026-07-11 08:49:46','2026-07-11 08:49:46'),(19,15,'Invoice INV-2026-123 from Tech Solutions Inc. received.','invoice_received','done','2026-07-11 08:52:52','2026-07-11 08:52:52'),(20,15,'Invoice INV-2026-123 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 08:53:00','2026-07-11 08:53:00'),(21,NULL,'Goods receipt GNR-2026-123 recorded for PO-2026-123.','goods_received','done','2026-07-11 08:56:38','2026-07-11 08:56:38'),(22,16,'Invoice INV-2026-234 from Tech Solutions Inc. received.','invoice_received','done','2026-07-11 09:01:25','2026-07-11 09:01:25'),(23,16,'Invoice INV-2026-234 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 09:01:52','2026-07-11 09:01:52'),(24,16,'Three-way match completed for INV-2026-234.','three_way_match','done','2026-07-11 09:03:07','2026-07-11 09:03:07'),(25,16,'Payment scheduled for INV-2026-234 on August 11, 2026.','payment_scheduled','scheduled','2026-07-11 09:04:16','2026-07-11 09:04:16'),(26,10,'Remittance advice RA-2026-005 emailed to Evergreen Supplies.','remittance_sent','done','2026-07-11 09:41:16','2026-07-11 09:41:16'),(27,4,'Clarification requested for INV-3011.','clarification_requested','done','2026-07-11 10:04:26','2026-07-11 10:04:26'),(28,NULL,'Purchase order PO-2026-678 created for Industrial Parts Corp..','po_created','done','2026-07-11 10:09:52','2026-07-11 10:09:52'),(29,NULL,'Goods receipt GNR-2026-678 recorded for PO-2026-678.','goods_received','done','2026-07-11 10:10:20','2026-07-11 10:10:20'),(30,17,'Invoice INV-2026-907 from Industrial Parts Corp. received.','invoice_received','done','2026-07-11 10:11:50','2026-07-11 10:11:50'),(31,17,'Invoice INV-2026-907 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 10:11:58','2026-07-11 10:11:58'),(32,17,'Three-way match completed for INV-2026-907.','three_way_match','done','2026-07-11 10:12:10','2026-07-11 10:12:10'),(33,1,'Payment scheduled for INV-2024-00125 on July 11, 2026.','payment_scheduled','scheduled','2026-07-11 10:13:03','2026-07-11 10:13:03'),(34,11,'Clarification requested for INV-2026-777.','clarification_requested','done','2026-07-11 22:03:06','2026-07-11 22:03:06'),(35,18,'Invoice INV-2026-00123 from ABC Trading received.','invoice_received','done','2026-07-11 22:07:14','2026-07-11 22:07:14'),(36,18,'Invoice INV-2026-00123 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 22:07:28','2026-07-11 22:07:28'),(37,18,'Clarification requested for INV-2026-00123.','clarification_requested','done','2026-07-11 22:07:46','2026-07-11 22:07:46'),(38,NULL,'Purchase order PO-2026-00192 created for Evergreen Supplies.','po_created','done','2026-07-11 22:11:00','2026-07-11 22:11:00'),(39,NULL,'Goods receipt GNR-2026-00192 recorded for PO-2026-00192.','goods_received','done','2026-07-11 22:11:24','2026-07-11 22:11:24'),(40,19,'Invoice INV-2026-00192 from Evergreen Supplies received.','invoice_received','done','2026-07-11 22:12:52','2026-07-11 22:12:52'),(41,19,'Invoice INV-2026-00192 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 22:13:20','2026-07-11 22:13:20'),(42,19,'Clarification requested for INV-2026-00192.','clarification_requested','done','2026-07-11 22:14:10','2026-07-11 22:14:10'),(43,19,'Three-way match completed for INV-2026-00192.','three_way_match','done','2026-07-11 22:14:27','2026-07-11 22:14:27'),(44,NULL,'Purchase order PO-2026-0001 created for Logistic Service LLC..','po_created','done','2026-07-11 22:17:05','2026-07-11 22:17:05'),(45,NULL,'Goods receipt GNR-2026-0001 recorded for PO-2026-0001.','goods_received','done','2026-07-11 22:18:20','2026-07-11 22:18:20'),(46,20,'Invoice INV-2026-0001 from Logistic Service LLC. received.','invoice_received','done','2026-07-11 22:23:13','2026-07-11 22:23:13'),(47,20,'Invoice INV-2026-0001 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 22:25:32','2026-07-11 22:25:32'),(48,20,'Three-way match completed for INV-2026-0001.','three_way_match','done','2026-07-11 22:25:57','2026-07-11 22:25:57'),(49,21,'Invoice INV-2026-0789 from Evergreen Supplies received.','invoice_received','done','2026-07-11 22:47:29','2026-07-11 22:47:29'),(50,21,'Invoice INV-2026-0789 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 22:47:39','2026-07-11 22:47:39'),(51,21,'Three-way match completed for INV-2026-0789.','three_way_match','done','2026-07-11 22:47:44','2026-07-11 22:47:44'),(52,22,'Invoice INV-2026-0009 from Evergreen Supplies received.','invoice_received','done','2026-07-11 23:00:34','2026-07-11 23:00:34'),(53,22,'Invoice INV-2026-0009 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 23:00:44','2026-07-11 23:00:44'),(54,22,'Three-way match completed for INV-2026-0009.','three_way_match','done','2026-07-11 23:00:48','2026-07-11 23:00:48'),(55,10,'Remittance advice RA-2026-005 emailed to Evergreen Supplies.','remittance_sent','done','2026-07-11 23:02:35','2026-07-11 23:02:35'),(56,23,'Invoice INV-2026-0082 from Evergreen Supplies received.','invoice_received','done','2026-07-11 23:10:13','2026-07-11 23:10:13'),(57,23,'Invoice INV-2026-0082 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-11 23:10:21','2026-07-11 23:10:21'),(58,23,'Three-way match completed for INV-2026-0082.','three_way_match','done','2026-07-11 23:10:34','2026-07-11 23:10:34'),(59,12,'Purchase order linked to invoice INV-2026-999.','po_linked','done','2026-07-11 23:15:34','2026-07-11 23:15:34'),(60,12,'Purchase order unlinked from invoice INV-2026-999.','po_linked','done','2026-07-11 23:18:32','2026-07-11 23:18:32'),(61,NULL,'Purchase order PO-2026-999 created for Global Supplies Co..','po_created','done','2026-07-11 23:19:32','2026-07-11 23:19:32'),(62,NULL,'Goods receipt GNR-2026-999 recorded for PO-2026-999.','goods_received','done','2026-07-11 23:19:45','2026-07-11 23:19:45'),(63,12,'Purchase order linked to invoice INV-2026-999.','po_linked','done','2026-07-11 23:19:54','2026-07-11 23:19:54'),(64,12,'Three-way match completed for INV-2026-999.','three_way_match','done','2026-07-11 23:19:59','2026-07-11 23:19:59'),(65,17,'Payment scheduled for INV-2026-907 on November 21, 2026.','payment_scheduled','scheduled','2026-07-11 23:31:07','2026-07-11 23:31:07'),(66,3,'Invoice INV-3010 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-18 03:33:01','2026-07-18 03:33:01'),(67,NULL,'Goods receipt GRN-233-232 recorded for PO-2026-678.','goods_received','done','2026-07-18 03:34:07','2026-07-18 03:34:07'),(68,NULL,'Purchase order PO-2026-111 created for Prime Industrial.','po_created','done','2026-07-18 03:34:48','2026-07-18 03:34:48'),(69,NULL,'Goods receipt GNR-2026-111 recorded for PO-2026-111.','goods_received','done','2026-07-18 03:35:08','2026-07-18 03:35:08'),(70,13,'Purchase order linked to invoice INV-2026-111.','po_linked','done','2026-07-18 03:35:18','2026-07-18 03:35:18'),(71,13,'Three-way match completed for INV-2026-111.','three_way_match','done','2026-07-18 03:35:22','2026-07-18 03:35:22'),(72,NULL,'Purchase order PO-3010 created for Industrial Parts Corp..','po_created','done','2026-07-18 07:02:33','2026-07-18 07:02:33'),(73,NULL,'Goods receipt GNR-3010 recorded for PO-3010.','goods_received','done','2026-07-18 07:04:46','2026-07-18 07:04:46'),(74,3,'Purchase order linked to invoice INV-3010.','po_linked','done','2026-07-18 07:04:58','2026-07-18 07:04:58'),(75,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-18 07:07:34','2026-07-18 07:07:34'),(76,3,'Purchase order linked to invoice INV-3010.','po_linked','done','2026-07-18 07:10:31','2026-07-18 07:10:31'),(77,NULL,'Purchase order PO-123 created for Blue Ocean Corp..','po_created','done','2026-07-18 07:39:33','2026-07-18 07:39:33'),(78,NULL,'Purchase order PO-123 deleted.','po_deleted','done','2026-07-18 07:39:47','2026-07-18 07:39:47'),(79,10,'Remittance advice RA-2026-005 emailed to Evergreen Supplies.','remittance_sent','done','2026-07-18 07:43:07','2026-07-18 07:43:07'),(80,NULL,'Purchase order PO-2026-1111 created for Tech Solutions Inc..','po_created','done','2026-07-18 19:41:39','2026-07-18 19:41:39'),(81,NULL,'Goods receipt GRN-2026-1111 recorded for PO-2026-1111.','goods_received','done','2026-07-18 19:42:05','2026-07-18 19:42:05'),(82,24,'Invoice INV-2026-1111 from Tech Solutions Inc. received.','invoice_received','done','2026-07-18 19:44:14','2026-07-18 19:44:14'),(83,24,'Invoice INV-2026-1111 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-18 19:44:41','2026-07-18 19:44:41'),(84,24,'Three-way match completed for INV-2026-1111.','three_way_match','done','2026-07-18 19:44:50','2026-07-18 19:44:50'),(85,3,'Purchase order unlinked from invoice INV-3010.','po_linked','done','2026-07-19 03:49:18','2026-07-19 03:49:18'),(86,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:49:52','2026-07-19 03:49:52'),(87,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:50:07','2026-07-19 03:50:07'),(88,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:50:20','2026-07-19 03:50:20'),(89,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:50:33','2026-07-19 03:50:33'),(90,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:50:46','2026-07-19 03:50:46'),(91,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:50:59','2026-07-19 03:50:59'),(92,NULL,'Purchase order PO-3010 updated.','po_updated','done','2026-07-19 03:51:09','2026-07-19 03:51:09'),(93,3,'Purchase order linked to invoice INV-3010.','po_linked','done','2026-07-19 03:51:20','2026-07-19 03:51:20'),(94,15,'Purchase order unlinked from invoice INV-2026-123.','po_linked','done','2026-07-19 03:54:41','2026-07-19 03:54:41'),(95,NULL,'Goods receipt GNR-2026-004 recorded for PO-2024-004.','goods_received','done','2026-07-19 04:04:20','2026-07-19 04:04:20'),(96,NULL,'Goods receipt GRN-2026-005 recorded for PO-2024-005.','goods_received','done','2026-07-19 04:04:44','2026-07-19 04:04:44'),(97,NULL,'Purchase order PO-2026-4444 created for Prime Industrial.','po_created','done','2026-07-19 04:09:59','2026-07-19 04:09:59'),(98,NULL,'Goods receipt GRN-2026-4444 recorded for PO-2026-4444.','goods_received','done','2026-07-19 04:10:23','2026-07-19 04:10:23'),(99,25,'Invoice INV-2026-4444 from Prime Industrial received.','invoice_received','done','2026-07-19 04:11:25','2026-07-19 04:11:25'),(100,25,'Invoice INV-2026-4444 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-19 04:11:48','2026-07-19 04:11:48'),(101,25,'Three-way match completed for INV-2026-4444.','three_way_match','done','2026-07-19 04:12:06','2026-07-19 04:12:06'),(102,25,'Payment scheduled for INV-2026-4444 on July 30, 2026.','payment_scheduled','scheduled','2026-07-19 04:12:39','2026-07-19 04:12:39'),(103,25,'Payment of PHP 1,568,000.00 to Prime Industrial, completed.','payment_completed','done','2026-07-19 04:12:53','2026-07-19 04:12:53'),(104,25,'Remittance advice RA-2026-010 emailed to Prime Industrial.','remittance_sent','done','2026-07-19 04:13:16','2026-07-19 04:13:16'),(105,NULL,'Purchase order PO-2026-0002 created for Prime Industrial.','po_created','done','2026-07-19 04:30:10','2026-07-19 04:30:10'),(106,NULL,'Goods receipt GRN-2026-0002 recorded for PO-2026-0002.','goods_received','done','2026-07-19 04:30:32','2026-07-19 04:30:32'),(107,26,'Invoice INV-2026-0002 from Prime Industrial received.','invoice_received','done','2026-07-19 04:31:23','2026-07-19 04:31:23'),(108,26,'Invoice INV-2026-0002 verified and sent to Three-Way Match.','invoice_verified','done','2026-07-19 04:31:30','2026-07-19 04:31:30'),(109,26,'Three-way match completed for INV-2026-0002.','three_way_match','done','2026-07-19 04:31:37','2026-07-19 04:31:37');
/*!40000 ALTER TABLE `ap_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_goods_receipts`
--

DROP TABLE IF EXISTS `ap_goods_receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_goods_receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grn_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` bigint unsigned NOT NULL,
  `receipt_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ap_goods_receipts_grn_number_unique` (`grn_number`),
  KEY `ap_goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `ap_goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `ap_purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_goods_receipts`
--

LOCK TABLES `ap_goods_receipts` WRITE;
/*!40000 ALTER TABLE `ap_goods_receipts` DISABLE KEYS */;
INSERT INTO `ap_goods_receipts` VALUES (1,'GRN-2024-021',1,'2026-05-12',75000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,'GRN-2024-022',2,'2026-06-03',42500.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,'GRN-2024-023',3,'2026-06-07',85000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(4,'GNR-2026-123',6,'2026-07-11',5600.00,'2026-07-11 08:56:38','2026-07-11 08:56:38'),(5,'GNR-2026-678',7,'2026-07-11',8960.00,'2026-07-11 10:10:20','2026-07-11 10:10:20'),(6,'GNR-2026-00192',8,'2026-07-12',36064.00,'2026-07-11 22:11:24','2026-07-11 22:11:24'),(7,'GNR-2026-0001',9,'2026-07-12',62720.00,'2026-07-11 22:18:20','2026-07-11 22:18:20'),(8,'GNR-2026-999',10,'2026-07-12',22.40,'2026-07-11 23:19:45','2026-07-11 23:19:45'),(9,'GRN-233-232',7,'2026-07-17',8960.00,'2026-07-18 03:34:07','2026-07-18 03:34:07'),(10,'GNR-2026-111',11,'2026-07-18',44.80,'2026-07-18 03:35:08','2026-07-18 03:35:08'),(11,'GNR-3010',12,'2026-07-18',35000.00,'2026-07-18 07:04:46','2026-07-18 07:04:46'),(12,'GRN-2026-1111',14,'2026-07-19',11200.00,'2026-07-18 19:42:05','2026-07-18 19:42:05'),(13,'GNR-2026-004',4,'2026-07-19',97000.00,'2026-07-19 04:04:20','2026-07-19 04:04:20'),(14,'GRN-2026-005',5,'2026-07-19',38200.00,'2026-07-19 04:04:44','2026-07-19 04:04:44'),(15,'GRN-2026-4444',15,'2026-07-19',1568000.00,'2026-07-19 04:10:23','2026-07-19 04:10:23'),(16,'GRN-2026-0002',16,'2026-07-19',44800.00,'2026-07-19 04:30:32','2026-07-19 04:30:32');
/*!40000 ALTER TABLE `ap_goods_receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_invoice_documents`
--

DROP TABLE IF EXISTS `ap_invoice_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_invoice_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `document_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'uploaded',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ap_invoice_documents_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `ap_invoice_documents_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `ap_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_invoice_documents`
--

LOCK TABLES `ap_invoice_documents` WRITE;
/*!40000 ALTER TABLE `ap_invoice_documents` DISABLE KEYS */;
INSERT INTO `ap_invoice_documents` VALUES (1,1,'Supplier Invoice','INV-2024-00125.pdf',NULL,1887436,'uploaded','2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,1,'Purchase Order','PO-2024-001.pdf',NULL,1003520,'uploaded','2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,1,'Delivery Receipt','GRN-2024-021.pdf',NULL,1153433,'uploaded','2026-07-11 07:27:00','2026-07-11 07:27:00');
/*!40000 ALTER TABLE `ap_invoice_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_invoice_items`
--

DROP TABLE IF EXISTS `ap_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '1.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ap_invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `ap_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `ap_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_invoice_items`
--

LOCK TABLES `ap_invoice_items` WRITE;
/*!40000 ALTER TABLE `ap_invoice_items` DISABLE KEYS */;
INSERT INTO `ap_invoice_items` VALUES (1,1,'Laptop',5.00,15000.00,75000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,2,'Office chairs',50.00,850.00,42500.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,3,'Steel brackets',200.00,175.00,35000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(4,4,'Freight services - June',1.00,25000.00,25000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(5,5,'Printer paper (reams)',400.00,55.50,22200.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(6,5,'Toner cartridges',20.00,800.00,16000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(7,6,'Raw materials - batch A',1.00,85000.00,85000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(8,7,'Industrial motors',10.00,9700.00,97000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(9,8,'Warehouse racking',1.00,61900.00,61900.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(10,9,'Shipping containers',3.00,24500.00,73500.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(11,10,'Landscaping services - Q2',1.00,45000.00,45000.00,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(12,11,'Fishball',3.00,20.00,60.00,'2026-07-11 07:50:05','2026-07-11 07:50:05'),(13,12,'itlog',2.00,10.00,20.00,'2026-07-11 07:57:48','2026-07-11 07:57:48'),(14,13,'Taho',2.00,20.00,40.00,'2026-07-11 08:11:39','2026-07-11 08:11:39'),(15,14,'Lenovo LOQ',3.00,56000.00,168000.00,'2026-07-11 08:44:25','2026-07-11 08:44:25'),(16,15,'Dark Blade',2.00,1600.00,3200.00,'2026-07-11 08:52:52','2026-07-11 08:52:52'),(17,16,'Laptop',1.00,5000.00,5000.00,'2026-07-11 09:01:25','2026-07-11 09:01:25'),(18,17,'Phone',1.00,8000.00,8000.00,'2026-07-11 10:11:50','2026-07-11 10:11:50'),(19,18,'System Unit',1.00,56000.00,56000.00,'2026-07-11 22:07:14','2026-07-11 22:07:14'),(20,19,'Monitor',5.00,6440.00,32200.00,'2026-07-11 22:12:52','2026-07-11 22:12:52'),(21,20,'System Unit',1.00,56000.00,56000.00,'2026-07-11 22:23:13','2026-07-11 22:23:13'),(22,21,'System Unit',5.00,6440.00,32200.00,'2026-07-11 22:47:29','2026-07-11 22:47:29'),(23,22,'Monitor',5.00,6440.00,32200.00,'2026-07-11 23:00:34','2026-07-11 23:00:34'),(24,23,'Gaming Table',5.00,6440.00,32200.00,'2026-07-11 23:10:13','2026-07-11 23:10:13'),(25,24,'Must Buy',1.00,10000.00,10000.00,'2026-07-18 19:44:14','2026-07-18 19:44:14'),(26,25,'Laptop Lenovo Legion',20.00,70000.00,1400000.00,'2026-07-19 04:11:25','2026-07-19 04:11:25'),(27,26,'Laptop',2.00,20000.00,40000.00,'2026-07-19 04:31:23','2026-07-19 04:31:23');
/*!40000 ALTER TABLE `ap_invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_invoices`
--

DROP TABLE IF EXISTS `ap_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `purchase_order_id` bigint unsigned DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PHP',
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_verification',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verification_remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `po_matched` tinyint(1) NOT NULL DEFAULT '0',
  `grn_matched` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_matched` tinyint(1) NOT NULL DEFAULT '0',
  `match_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ap_invoices_invoice_number_unique` (`invoice_number`),
  KEY `ap_invoices_supplier_id_foreign` (`supplier_id`),
  KEY `ap_invoices_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `ap_invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `ap_purchase_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ap_invoices_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `ap_suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_invoices`
--

LOCK TABLES `ap_invoices` WRITE;
/*!40000 ALTER TABLE `ap_invoices` DISABLE KEYS */;
INSERT INTO `ap_invoices` VALUES (1,'INV-2024-00125',1,1,'2026-05-15','2026-07-30','Net 30','PHP','Finance Department',NULL,'scheduled',75000.00,0.00,0.00,75000.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:27:00','2026-07-11 10:13:03'),(2,'INV-3002',2,2,'2026-06-02','2026-07-20','Net 30','PHP','Finance Department',NULL,'paid',42500.00,0.00,0.00,42500.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:27:00','2026-07-11 08:33:00'),(3,'INV-3010',3,12,'2026-07-05','2026-08-05','Net 30','PHP','Finance Department',NULL,'verified',35000.00,0.00,0.00,35000.00,NULL,NULL,0,0,0,NULL,'2026-07-11 07:27:00','2026-07-19 03:51:20'),(4,'INV-3011',4,NULL,'2026-06-20','2026-07-15','Net 30','PHP','Finance Department',NULL,'clarification_requested',25000.00,0.00,0.00,25000.00,NULL,NULL,0,0,0,NULL,'2026-07-11 07:27:00','2026-07-11 10:04:26'),(5,'INV-3004',5,5,'2026-06-12','2026-07-18','Net 30','PHP','Finance Department',NULL,'approved',38200.00,0.00,0.00,38200.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:27:00','2026-07-11 07:27:00'),(6,'INV-3001',6,3,'2026-06-08','2026-07-25','Net 30','PHP','Finance Department',NULL,'paid',85000.00,0.00,0.00,85000.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:27:00','2026-07-11 08:32:57'),(7,'INV-3003',7,4,'2026-05-22','2026-06-21','Net 30','PHP','Finance Department',NULL,'overdue',97000.00,0.00,0.00,97000.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:27:00','2026-07-11 07:27:00'),(8,'INV-3005',8,NULL,'2026-06-25','2026-07-22','Net 30','PHP','Finance Department',NULL,'pending_verification',61900.00,0.00,0.00,61900.00,NULL,NULL,0,0,0,NULL,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(9,'INV-3006',9,NULL,'2026-06-24','2026-07-24','Net 30','PHP','Finance Department',NULL,'paid',73500.00,0.00,0.00,73500.00,NULL,NULL,0,0,0,NULL,'2026-07-11 07:27:00','2026-07-11 08:33:02'),(10,'INV-3007',10,NULL,'2026-06-01','2026-07-23','Net 30','PHP','Finance Department',NULL,'paid',45000.00,0.00,0.00,45000.00,NULL,NULL,0,0,0,NULL,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(11,'INV-2026-777',2,NULL,'2026-07-11','2026-12-15','Cash on Delivery','PHP',NULL,'1234453534534','clarification_requested',60.00,7.20,0.00,67.20,'Labyu',NULL,0,0,0,NULL,'2026-07-11 07:50:05','2026-07-11 22:03:06'),(12,'INV-2026-999',1,10,'2026-07-11','2026-12-16','Cash on Delivery','PHP','DIT','2342351231','approved',20.00,2.40,0.00,22.40,NULL,NULL,1,1,1,'APPROVED','2026-07-11 07:57:48','2026-07-11 23:19:58'),(13,'INV-2026-111',7,11,'2026-07-12','2026-12-16','Cash on Delivery','PHP','DIT','213654647756345','approved',40.00,4.80,0.00,44.80,'Eatwell',NULL,1,1,1,'APPROVED','2026-07-11 08:11:39','2026-07-18 03:35:22'),(14,'INV-2026-987',10,3,'2026-07-12','2026-12-16','Cash on Delivery','PHP','DIT','821763871263','verified',168000.00,20160.00,0.00,188160.00,'Enjoy',NULL,0,0,0,NULL,'2026-07-11 08:44:25','2026-07-11 08:44:39'),(15,'INV-2026-123',2,NULL,'2026-07-12','2026-12-31','Cash on Delivery','PHP','DIT','123','verified',3200.00,384.00,0.00,3584.00,NULL,NULL,0,0,0,NULL,'2026-07-11 08:52:52','2026-07-19 03:54:41'),(16,'INV-2026-234',2,6,'2026-07-12','2026-08-12','Cash on Delivery','PHP','DIT','235436134','scheduled',5000.00,600.00,0.00,5600.00,'Thankyouuuu','HAHAHAHAHHA',1,1,1,'APPROVED','2026-07-11 09:01:25','2026-07-11 09:04:16'),(17,'INV-2026-907',3,7,'2026-07-12','2026-12-11','Net 45','PHP','DIT','235356233324','scheduled',8000.00,960.00,0.00,8960.00,'hi',NULL,1,1,1,'APPROVED','2026-07-11 10:11:50','2026-07-11 23:31:07'),(18,'INV-2026-00123',6,3,'2026-07-12','2026-09-15','Cash on Delivery','USD','CEIT','3456457453','clarification_requested',56000.00,6720.00,0.00,62720.00,'Careful opening.',NULL,0,0,0,NULL,'2026-07-11 22:07:14','2026-07-11 22:07:46'),(19,'INV-2026-00192',10,8,'2026-07-12','2026-09-15','Net 15','USD','CEIT','0345683458','approved',32200.00,3864.00,0.00,36064.00,'Handle with care.','Done.',1,1,1,'APPROVED','2026-07-11 22:12:52','2026-07-11 22:14:27'),(20,'INV-2026-0001',4,9,'2026-07-12','2026-09-16','Cash on Delivery','PHP','Finance Dep.','8793459345','approved',56000.00,6720.00,0.00,62720.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 22:23:13','2026-07-11 22:25:57'),(21,'INV-2026-0789',10,8,'2026-07-12','2026-08-08','Cash on Delivery','PHP','Finance','0572303460043536','approved',32200.00,3864.00,0.00,36064.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 22:47:29','2026-07-11 22:47:44'),(22,'INV-2026-0009',10,8,'2026-07-13','2026-09-21','Net 30','PHP','Finance','09567460435','approved',32200.00,3864.00,0.00,36064.00,NULL,NULL,1,1,1,'APPROVED','2026-07-11 23:00:34','2026-07-11 23:00:48'),(23,'INV-2026-0082',10,8,'2026-07-12','2026-10-13','Cash on Delivery','EUR','DIT','0897510370042','approved',32200.00,3864.00,0.00,36064.00,'handle with care.',NULL,1,1,1,'APPROVED','2026-07-11 23:10:13','2026-07-11 23:10:33'),(24,'INV-2026-1111',2,14,'2026-07-24','2026-07-27','Net 30','PHP','Finance Department','11223344','approved',10000.00,1200.00,0.00,11200.00,NULL,NULL,1,1,1,'APPROVED','2026-07-18 19:44:14','2026-07-18 19:44:50'),(25,'INV-2026-4444',7,15,'2026-07-19','2026-08-19','Cash on Delivery','PHP',NULL,NULL,'paid',1400000.00,168000.00,0.00,1568000.00,NULL,NULL,1,1,1,'APPROVED','2026-07-19 04:11:25','2026-07-19 04:12:53'),(26,'INV-2026-0002',7,16,'2026-07-19','2026-08-19','Cash on Delivery','PHP',NULL,NULL,'approved',40000.00,4800.00,0.00,44800.00,NULL,NULL,1,1,1,'APPROVED','2026-07-19 04:31:23','2026-07-19 04:31:37');
/*!40000 ALTER TABLE `ap_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_payments`
--

DROP TABLE IF EXISTS `ap_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bank Transfer',
  `bank_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remittance_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remittance_pdf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remittance_sent_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remittance_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ap_payments_remittance_number_unique` (`remittance_number`),
  KEY `ap_payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `ap_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `ap_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_payments`
--

LOCK TABLES `ap_payments` WRITE;
/*!40000 ALTER TABLE `ap_payments` DISABLE KEYS */;
INSERT INTO `ap_payments` VALUES (1,1,'PAY-2026-001','2026-06-15',75000.00,'Bank Transfer',NULL,'high','approved',NULL,NULL,NULL,NULL,NULL,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,2,'PAY-2026-002',NULL,42500.00,'Cheque',NULL,'medium','approved',NULL,NULL,NULL,NULL,NULL,'2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,6,'PAY-2026-003','2026-07-11',85000.00,'Bank Transfer',NULL,'high','paid',NULL,'RA-2026-003','remittances/RA-2026-003.pdf',NULL,NULL,'2026-07-11 07:27:00','2026-07-11 10:13:19'),(4,9,'PAY-2026-004','2026-07-24',73500.00,'Bank Transfer',NULL,'high','paid',NULL,NULL,NULL,NULL,NULL,'2026-07-11 07:27:00','2026-07-11 08:33:02'),(5,10,'PAY-2026-005','2026-07-01',45000.00,'Cheque',NULL,'low','paid',NULL,'RA-2026-005','remittances/RA-2026-005.pdf','hello@evergreensupplies.com','2026-07-18 07:43:07','2026-07-11 07:27:00','2026-07-18 07:43:07'),(6,2,'PAY-2026-006','2026-07-12',42500.00,'Cash',NULL,'high','paid',NULL,NULL,NULL,NULL,NULL,'2026-07-11 08:17:09','2026-07-11 08:33:00'),(7,16,'PAY-2026-007','2026-08-11',5600.00,'Bank Transfer',NULL,'medium','scheduled','Maraming salamat sayo giliw',NULL,NULL,NULL,NULL,'2026-07-11 09:04:16','2026-07-11 09:04:16'),(8,1,'PAY-2026-008','2026-07-11',75000.00,'Bank Transfer',NULL,'low','scheduled',NULL,NULL,NULL,NULL,NULL,'2026-07-11 10:13:03','2026-07-11 10:13:03'),(9,17,'PAY-2026-009','2026-11-21',8960.00,'Cash',NULL,'high','scheduled',NULL,NULL,NULL,NULL,NULL,'2026-07-11 23:31:07','2026-07-11 23:31:07'),(10,25,'PAY-2026-010','2026-07-19',1568000.00,'Cash',NULL,'high','paid',NULL,'RA-2026-010','remittances/RA-2026-010.pdf','contact@primeindustrial.com','2026-07-19 04:13:16','2026-07-19 04:12:39','2026-07-19 04:13:16');
/*!40000 ALTER TABLE `ap_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_purchase_order_items`
--

DROP TABLE IF EXISTS `ap_purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ap_purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `ap_purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `ap_purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_purchase_order_items`
--

LOCK TABLES `ap_purchase_order_items` WRITE;
/*!40000 ALTER TABLE `ap_purchase_order_items` DISABLE KEYS */;
INSERT INTO `ap_purchase_order_items` VALUES (4,14,'Must Buy',1.00,10000.00,10000.00,'2026-07-18 19:41:39','2026-07-18 19:41:39'),(11,12,'Steel brackets',200.00,157.00,31400.00,'2026-07-19 03:51:09','2026-07-19 03:51:09'),(12,15,'Laptop Lenovo Legion',20.00,70000.00,1400000.00,'2026-07-19 04:09:59','2026-07-19 04:09:59'),(13,16,'Laptop',2.00,20000.00,40000.00,'2026-07-19 04:30:10','2026-07-19 04:30:10');
/*!40000 ALTER TABLE `ap_purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_purchase_orders`
--

DROP TABLE IF EXISTS `ap_purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `po_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ap_purchase_orders_po_number_unique` (`po_number`),
  KEY `ap_purchase_orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `ap_purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `ap_suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_purchase_orders`
--

LOCK TABLES `ap_purchase_orders` WRITE;
/*!40000 ALTER TABLE `ap_purchase_orders` DISABLE KEYS */;
INSERT INTO `ap_purchase_orders` VALUES (1,'PO-2024-001',1,'2026-05-10',75000.00,'closed','2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,'PO-2024-002',2,'2026-06-01',42500.00,'closed','2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,'PO-2024-003',6,'2026-06-05',85000.00,'closed','2026-07-11 07:27:00','2026-07-11 07:27:00'),(4,'PO-2024-004',7,'2026-05-20',97000.00,'received','2026-07-11 07:27:00','2026-07-19 04:04:20'),(5,'PO-2024-005',5,'2026-06-10',38200.00,'received','2026-07-11 07:27:00','2026-07-19 04:04:44'),(6,'PO-2026-123',2,'2026-07-16',5600.00,'received','2026-07-11 08:49:46','2026-07-11 08:56:38'),(7,'PO-2026-678',3,'2026-08-10',8960.00,'received','2026-07-11 10:09:52','2026-07-11 10:10:20'),(8,'PO-2026-00192',10,'2026-07-12',36064.00,'received','2026-07-11 22:11:00','2026-07-11 22:11:24'),(9,'PO-2026-0001',4,'2026-07-12',62720.00,'received','2026-07-11 22:17:05','2026-07-11 22:18:20'),(10,'PO-2026-999',1,'2026-07-11',22.40,'received','2026-07-11 23:19:32','2026-07-11 23:19:45'),(11,'PO-2026-111',7,'2026-07-18',44.80,'received','2026-07-18 03:34:48','2026-07-18 03:35:08'),(12,'PO-3010',3,'2026-07-18',35168.00,'received','2026-07-18 07:02:33','2026-07-19 03:51:09'),(14,'PO-2026-1111',2,'2026-07-30',11200.00,'received','2026-07-18 19:41:39','2026-07-18 19:42:05'),(15,'PO-2026-4444',7,'2026-07-19',1568000.00,'received','2026-07-19 04:09:59','2026-07-19 04:10:23'),(16,'PO-2026-0002',7,'2026-07-19',44800.00,'received','2026-07-19 04:30:10','2026-07-19 04:30:32');
/*!40000 ALTER TABLE `ap_purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_suppliers`
--

DROP TABLE IF EXISTS `ap_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_suppliers`
--

LOCK TABLES `ap_suppliers` WRITE;
/*!40000 ALTER TABLE `ap_suppliers` DISABLE KEYS */;
INSERT INTO `ap_suppliers` VALUES (1,'Global Supplies Co.','global@supplies.com','+63 2 8123 4501',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(2,'Tech Solutions Inc.','contact@techsolutions.com','+63 2 8123 4502',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(3,'Industrial Parts Corp.','sales@industrialparts.com','+63 2 8123 4503',NULL,'Net 45','2026-07-11 07:27:00','2026-07-11 07:27:00'),(4,'Logistic Service LLC.','info@logisticservice.com','+63 2 8123 4504',NULL,'Net 15','2026-07-11 07:27:00','2026-07-11 07:27:00'),(5,'Metro Office Supply','orders@metrooffice.com','+63 2 8123 4505',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(6,'ABC Trading','abctrading@gmail.com','+63 2 8123 4506',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(7,'Prime Industrial','contact@primeindustrial.com','+63 2 8123 4507',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(8,'Northwind Traders','sales@northwindtraders.com','+63 2 8123 4508',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(9,'Blue Ocean Corp.','info@blueocean.com','+63 2 8123 4509',NULL,'Net 30','2026-07-11 07:27:00','2026-07-11 07:27:00'),(10,'Evergreen Supplies','hello@evergreensupplies.com','+63 2 8123 4510',NULL,'Net 15','2026-07-11 07:27:00','2026-07-11 07:27:00');
/*!40000 ALTER TABLE `ap_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_customers`
--

DROP TABLE IF EXISTS `ar_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` text,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_customers`
--

LOCK TABLES `ar_customers` WRITE;
/*!40000 ALTER TABLE `ar_customers` DISABLE KEYS */;
INSERT INTO `ar_customers` VALUES (1,'ABC Corporation','ABC Trading Inc.','Manila Philippines','abc@gmail.com','09123456789',NULL,NULL),(2,'XYZ Enterprises','XYZ Solutions','Cavite Philippines','xyz@gmail.com','09987654321',NULL,NULL),(3,'Juan Dela Cruz','ABC Trading Corporation','Cavite City','juan@abctrading.com','09171234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(4,'Maria Santos','Santos Enterprises','Manila','maria@santos.com','09181234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(5,'Pedro Reyes','Prime Solutions Inc.','Laguna','pedro@prime.com','09191234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(6,'Ana Garcia','Garcia Supplies','Batangas','ana@garciasupplies.com','09201234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(7,'Carlos Mendoza','Mendoza Construction','Quezon City','carlos@mendoza.com','09211234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(8,'Sofia Navarro','Navarro Retail Store','Pasay City','sofia@navarro.com','09221234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(9,'Miguel Torres','Torres Technologies','Makati City','miguel@torrestech.com','09231234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(10,'Angela Cruz','Cruz Marketing Agency','Taguig City','angela@cruzmarketing.com','09241234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(11,'Roberto Lim','Lim Industrial Supply','Pasig City','roberto@limindustrial.com','09251234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(12,'Patricia Flores','Flores Trading Co.','Marikina City','patricia@flores.com','09261234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(13,'Daniel Aquino','Aquino Logistics','Paranaque City','daniel@aquino.com','09271234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(14,'Kristine Ramos','Ramos Office Solutions','Muntinlupa City','kristine@ramos.com','09281234567','2026-07-18 16:47:08','2026-07-18 16:47:08'),(15,'Jerome Villanueva','Villanueva Enterprises','Caloocan City','jerome@villanueva.com','09291234567','2026-07-18 16:47:08','2026-07-18 16:47:08');
/*!40000 ALTER TABLE `ar_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_invoice_items`
--

DROP TABLE IF EXISTS `ar_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `ar_invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `ar_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_invoice_items`
--

LOCK TABLES `ar_invoice_items` WRITE;
/*!40000 ALTER TABLE `ar_invoice_items` DISABLE KEYS */;
INSERT INTO `ar_invoice_items` VALUES (14,13,'laptop',5,55000.00,275000.00,NULL,NULL),(16,14,'Gpu',4,55000.00,220000.00,NULL,NULL),(17,15,'Monitor',10,47000.00,470000.00,NULL,NULL),(18,15,'System unit',3,55000.00,165000.00,NULL,NULL),(19,16,'Iphone17',2,85000.00,170000.00,NULL,NULL),(23,20,'laptop',5,45000.00,225000.00,NULL,NULL),(26,23,'RTX5090',3,85000.00,255000.00,NULL,NULL),(33,30,'Paldo Nanaman',2,50000.00,100000.00,NULL,NULL),(34,31,'Barbiee',2,200000.00,400000.00,NULL,NULL),(40,34,'Brief Ni Dannielle',300,1500.00,450000.00,NULL,NULL),(42,36,'Boxer ni Bergado',1000,400.00,400000.00,NULL,NULL),(43,37,'Tshirt ni Harvie',250,2555.00,638750.00,NULL,NULL);
/*!40000 ALTER TABLE `ar_invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_invoices`
--

DROP TABLE IF EXISTS `ar_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `payment_terms` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT '0.00',
  `tax` decimal(12,2) DEFAULT '0.00',
  `total` decimal(12,2) DEFAULT '0.00',
  `balance` decimal(12,2) DEFAULT '0.00',
  `status` enum('Paid','Unpaid','Partial','Overdue') DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `ar_invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ar_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_invoices`
--

LOCK TABLES `ar_invoices` WRITE;
/*!40000 ALTER TABLE `ar_invoices` DISABLE KEYS */;
INSERT INTO `ar_invoices` VALUES (13,'INV-20260718181101',1,'2026-04-30','2026-05-30','Net 30',275000.00,33000.00,308000.00,0.00,'Paid',NULL,'2026-07-18 10:11:01','2026-07-18 10:14:29'),(14,'INV-20260718181614',2,'2026-07-19','2026-08-19','Net 30',220000.00,26400.00,246400.00,146400.00,'Partial',NULL,'2026-07-18 10:16:14','2026-07-18 10:18:12'),(15,'INV-20260718181921',3,'2026-04-15','2026-05-15','Net 30',635000.00,76200.00,711200.00,711200.00,'Overdue',NULL,'2026-07-18 10:19:21','2026-07-19 06:45:00'),(16,'INV-20260718182017',5,'2026-06-18','2026-08-19','Net 30',170000.00,20400.00,190400.00,190400.00,'Unpaid',NULL,'2026-07-18 10:20:17','2026-07-18 10:20:17'),(20,'INV-20260718182324',8,'2026-07-19','2026-07-19','Net 30',225000.00,27000.00,252000.00,52000.00,'Overdue',NULL,'2026-07-18 10:23:24','2026-07-19 06:45:00'),(23,'INV-20260718183332',10,'2026-05-24','2026-06-24','Net 30',255000.00,30600.00,285600.00,285600.00,'Overdue',NULL,'2026-07-18 10:33:32','2026-07-19 06:45:00'),(30,'INV-20260719053303',9,'2026-07-19','2026-07-22','Net 30',100000.00,12000.00,112000.00,62000.00,'Partial',NULL,'2026-07-18 21:33:03','2026-07-19 03:49:56'),(31,'INV-20260719120830',3,'2026-01-01','2026-01-19','Net 30',400000.00,48000.00,448000.00,148000.00,'Overdue',NULL,'2026-07-19 04:08:30','2026-07-19 06:45:00'),(34,'INV-20260719141848',1,'2026-02-19','2026-07-03','Net 30',450000.00,54000.00,504000.00,404000.00,'Overdue',NULL,'2026-07-19 06:18:48','2026-07-19 06:45:00'),(36,'INV-20260719143544',2,'2026-07-19','2026-07-22','Net 30',400000.00,48000.00,448000.00,448000.00,'Unpaid',NULL,'2026-07-19 06:35:44','2026-07-19 06:35:44'),(37,'INV-20260719144348',9,'2026-03-19','2026-03-05','Net 30',638750.00,76650.00,715400.00,715400.00,'Overdue',NULL,'2026-07-19 06:43:48','2026-07-19 06:45:00');
/*!40000 ALTER TABLE `ar_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_payments`
--

DROP TABLE IF EXISTS `ar_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `ar_payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `ar_invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ar_payments_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `ar_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_payments`
--

LOCK TABLES `ar_payments` WRITE;
/*!40000 ALTER TABLE `ar_payments` DISABLE KEYS */;
INSERT INTO `ar_payments` VALUES (11,13,1,'2026-07-19','GCash','PAY-20260718-0001',20000.00,NULL,'2026-07-18 10:11:41','2026-07-18 10:11:41'),(12,13,1,'2026-07-18','Cash','PAY-20260718-0002',200000.00,NULL,'2026-07-18 10:12:25','2026-07-18 10:12:25'),(13,13,1,'2026-07-19','GCash','PAY-20260718-0003',5000.00,NULL,'2026-07-18 10:14:00','2026-07-18 10:14:00'),(14,13,1,'2026-07-19','Cash','PAY-20260718-0004',83000.00,NULL,'2026-07-18 10:14:29','2026-07-18 10:14:29'),(15,14,2,'2026-07-19','Cash','PAY-20260718-0005',100000.00,NULL,'2026-07-18 10:17:05','2026-07-18 10:17:05'),(18,20,8,'2026-07-19','Bank Transfer','PAY-20260718-0007',200000.00,NULL,'2026-07-18 10:31:32','2026-07-18 10:31:32'),(21,30,9,'2026-07-19','Cash','PAY-20260719-0010',50000.00,NULL,'2026-07-19 03:49:56','2026-07-19 03:49:56'),(25,31,3,'2026-07-19','Bank Transfer','PAY-20260719-0009',100000.00,NULL,'2026-07-19 06:10:39','2026-07-19 06:10:39'),(26,31,3,'2026-07-19','Bank Transfer','PAY-20260719-0010',100000.00,NULL,'2026-07-19 06:11:59','2026-07-19 06:11:59'),(27,31,3,'2026-07-19','Bank Transfer','PAY-20260719-0011',100000.00,NULL,'2026-07-19 06:12:20','2026-07-19 06:12:20'),(28,34,1,'2026-07-19','Bank Transfer','PAY-20260719-0012',100000.00,NULL,'2026-07-19 06:24:22','2026-07-19 06:24:22');
/*!40000 ALTER TABLE `ar_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_receivables`
--

DROP TABLE IF EXISTS `ar_receivables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_receivables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_receivables`
--

LOCK TABLES `ar_receivables` WRITE;
/*!40000 ALTER TABLE `ar_receivables` DISABLE KEYS */;
INSERT INTO `ar_receivables` VALUES (1,'INV-1001','ABC Corporation','2026-07-01','2026-07-30',56000.00,46000.00,NULL,NULL),(2,'INV-1002','XYZ Enterprises','2026-05-01','2026-06-01',112000.00,112000.00,NULL,NULL);
/*!40000 ALTER TABLE `ar_receivables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ar_reminders`
--

DROP TABLE IF EXISTS `ar_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ar_reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned NOT NULL,
  `status` enum('Sent','Pending') DEFAULT 'Pending',
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `ar_reminders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ar_customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ar_reminders_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `ar_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ar_reminders`
--

LOCK TABLES `ar_reminders` WRITE;
/*!40000 ALTER TABLE `ar_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `ar_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `budget` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_key` (`category_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budgets`
--

LOCK TABLES `budgets` WRITE;
/*!40000 ALTER TABLE `budgets` DISABLE KEYS */;
INSERT INTO `budgets` VALUES (1,'marketing','Marketing',1200000.00,1024692.00,'2026-07-19 12:23:17','2026-07-19 12:23:17'),(2,'operations','Operations',2750000.00,2654321.00,'2026-07-19 12:23:17','2026-07-19 12:23:17'),(3,'sales','Sales',2300000.00,2123456.00,'2026-07-19 12:23:17','2026-07-19 12:23:17'),(4,'technology','Technology',1950000.00,1876543.00,'2026-07-19 12:23:17','2026-07-19 12:23:17'),(5,'human-resources','Human Resources',1550000.00,1543210.00,'2026-07-19 12:23:17','2026-07-19 12:23:17'),(6,'finance','Finance',3250000.00,3123456.00,'2026-07-19 12:23:17','2026-07-19 12:23:17');
/*!40000 ALTER TABLE `budgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fa_asset_categories`
--

DROP TABLE IF EXISTS `fa_asset_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fa_asset_categories` (
  `category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_useful_life` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fa_asset_categories`
--

LOCK TABLES `fa_asset_categories` WRITE;
/*!40000 ALTER TABLE `fa_asset_categories` DISABLE KEYS */;
INSERT INTO `fa_asset_categories` VALUES (1,'Office Equipment','Computers, printers, aircon, etc.',5,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(2,'Furniture & Fixtures','Desks, chairs, cabinets',10,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(3,'Vehicles','Company-owned vehicles',8,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(4,'Buildings','Office buildings and warehouses',25,'2026-07-12 22:33:00','2026-07-12 22:33:00');
/*!40000 ALTER TABLE `fa_asset_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fa_asset_depreciation_schedules`
--

DROP TABLE IF EXISTS `fa_asset_depreciation_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fa_asset_depreciation_schedules` (
  `schedule_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `period_date` date NOT NULL,
  `depreciation_expense` decimal(14,2) NOT NULL,
  `accumulated_depreciation` decimal(14,2) NOT NULL,
  `book_value` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `asset_depreciation_schedules_asset_id_foreign` (`asset_id`),
  CONSTRAINT `asset_depreciation_schedules_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `fa_fixed_assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fa_asset_depreciation_schedules`
--

LOCK TABLES `fa_asset_depreciation_schedules` WRITE;
/*!40000 ALTER TABLE `fa_asset_depreciation_schedules` DISABLE KEYS */;
INSERT INTO `fa_asset_depreciation_schedules` VALUES (1,1,'2024-01-15',8400.00,8400.00,36600.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(2,1,'2025-01-15',8400.00,16800.00,28200.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(3,1,'2026-01-15',8400.00,25200.00,19800.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(4,2,'2024-03-10',2800.00,2800.00,12200.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(5,2,'2025-03-10',2800.00,5600.00,9400.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(6,2,'2026-03-10',2800.00,8400.00,6600.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(7,3,'2023-06-20',7200.00,7200.00,30800.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(8,3,'2024-06-20',7200.00,14400.00,23600.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(9,3,'2025-06-20',7200.00,21600.00,16400.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(10,3,'2026-06-20',7200.00,28800.00,9200.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(11,4,'2022-05-05',2050.00,2050.00,19950.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(12,4,'2023-05-05',2050.00,4100.00,17900.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(13,4,'2024-05-05',2050.00,6150.00,15850.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(14,4,'2025-05-05',2050.00,8200.00,13800.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(15,4,'2026-05-05',2050.00,10250.00,11750.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(16,5,'2022-05-05',5200.00,5200.00,49800.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(17,5,'2023-05-05',5200.00,10400.00,44600.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(18,5,'2024-05-05',5200.00,15600.00,39400.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(19,5,'2025-05-05',5200.00,20800.00,34200.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(20,5,'2026-05-05',5200.00,26000.00,29000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(21,6,'2021-09-12',1150.00,1150.00,10850.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(22,6,'2022-09-12',1150.00,2300.00,9700.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(23,6,'2023-09-12',1150.00,3450.00,8550.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(24,6,'2024-09-12',1150.00,4600.00,7400.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(25,6,'2025-09-12',1150.00,5750.00,6250.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(26,7,'2023-02-18',162500.00,162500.00,1287500.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(27,7,'2024-02-18',162500.00,325000.00,1125000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(28,7,'2025-02-18',162500.00,487500.00,962500.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(29,7,'2026-02-18',162500.00,650000.00,800000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(30,8,'2020-11-30',80000.00,80000.00,640000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(31,8,'2021-11-30',80000.00,160000.00,560000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(32,8,'2022-11-30',80000.00,240000.00,480000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(33,8,'2023-11-30',80000.00,320000.00,400000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(34,8,'2024-11-30',80000.00,400000.00,320000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(35,8,'2025-11-30',80000.00,480000.00,240000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(36,9,'2019-01-01',300000.00,300000.00,8200000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(37,9,'2020-01-01',300000.00,600000.00,7900000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(38,9,'2021-01-01',300000.00,900000.00,7600000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(39,9,'2022-01-01',300000.00,1200000.00,7300000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(40,9,'2023-01-01',300000.00,1500000.00,7000000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(41,9,'2024-01-01',300000.00,1800000.00,6700000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(42,9,'2025-01-01',300000.00,2100000.00,6400000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(43,9,'2026-01-01',300000.00,2400000.00,6100000.00,'2026-07-12 22:33:00','2026-07-12 22:33:00'),(46,3,'2026-07-18',600.00,29859.08,8140.92,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(47,4,'2026-07-18',170.83,10809.90,11190.10,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(48,5,'2026-07-18',433.33,27420.23,27579.77,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(49,7,'2026-07-18',13541.67,728217.97,721782.03,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(50,8,'2026-07-18',6666.67,536041.56,183958.44,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(51,9,'2026-07-18',25000.00,2583854.46,5916145.54,'2026-07-18 07:26:12','2026-07-18 07:26:12');
/*!40000 ALTER TABLE `fa_asset_depreciation_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fa_fixed_assets`
--

DROP TABLE IF EXISTS `fa_fixed_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fa_fixed_assets` (
  `asset_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_tag` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_cost` decimal(14,2) NOT NULL,
  `salvage_value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `useful_life_years` int NOT NULL,
  `warranty_years` int DEFAULT NULL,
  `depreciation_method` enum('straight_line','declining_balance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'straight_line',
  `accumulated_depreciation` decimal(14,2) NOT NULL DEFAULT '0.00',
  `book_value` decimal(14,2) NOT NULL DEFAULT '0.00',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `condition` enum('New','Good','Fair','Poor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Good',
  `status` enum('active','disposed','under_maintenance','fully_depreciated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `disposal_date` date DEFAULT NULL,
  `disposal_value` decimal(14,2) DEFAULT NULL,
  `disposal_reason` enum('sold','scrapped','donated','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gain_loss` decimal(14,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `fixed_assets_asset_tag_unique` (`asset_tag`),
  KEY `fixed_assets_category_id_foreign` (`category_id`),
  CONSTRAINT `fixed_assets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `fa_asset_categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fa_fixed_assets`
--

LOCK TABLES `fa_fixed_assets` WRITE;
/*!40000 ALTER TABLE `fa_fixed_assets` DISABLE KEYS */;
INSERT INTO `fa_fixed_assets` VALUES (1,'FA-2023-001','Dell OptiPlex Desktop PC','1021211',1,'2023-01-15',45000.00,3000.00,5,2,'straight_line',20325.65,24674.35,'Accounting Office','UNDEr MAINTENANCE','Fair','under_maintenance',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-19 04:27:46'),(2,'FA-2023-002','HP LaserJet Printer',NULL,1,'2023-03-10',15000.00,1000.00,5,NULL,'straight_line',9361.00,5639.00,'Admin Office',NULL,'Poor','disposed',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-12 23:24:08'),(3,'FA-2022-003','Split-Type Air Conditioner',NULL,1,'2022-06-20',38000.00,2000.00,5,NULL,'straight_line',29859.08,8140.92,'Conference Room',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-18 07:26:12'),(4,'FA-2021-004','Executive Office Desk',NULL,2,'2021-05-05',22000.00,1500.00,10,NULL,'straight_line',10809.90,11190.10,'Manager Office',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-18 07:26:12'),(5,'FA-2021-005','Ergonomic Office Chairs (Set of 10)',NULL,2,'2021-05-05',55000.00,3000.00,10,NULL,'straight_line',27420.23,27579.77,'Open Workspace',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-18 07:26:12'),(6,'FA-2020-006','Filing Cabinet (Steel, 4-Drawer)','12314123',2,'2020-09-12',10000.00,500.00,10,1,'straight_line',5000.67,4999.33,'Records Room',NULL,'Poor','under_maintenance',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-12 22:44:12'),(7,'FA-2022-007','Toyota Hiace Delivery Van','11201212356',3,'2022-02-18',1450000.00,150000.00,8,NULL,'straight_line',700000.00,750000.00,'Motor Pool',NULL,'Fair','under_maintenance',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-19 04:56:43'),(8,'FA-2019-008','Mitsubishi Mirage Company Car',NULL,3,'2019-11-30',720000.00,80000.00,8,NULL,'straight_line',536041.56,183958.44,'Motor Pool',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-18 07:26:12'),(9,'FA-2018-009','Main Office Building',NULL,4,'2018-01-01',8500000.00,1000000.00,25,NULL,'straight_line',2583854.46,5916145.54,'Head Office',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-12 22:33:00','2026-07-18 07:26:12'),(12,'FA-2026-010','Gold ni Harvie',NULL,3,'2026-07-07',500000.00,0.00,5,NULL,'straight_line',0.00,500000.00,'Trece',NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-18 07:30:54','2026-07-18 07:30:54'),(13,'FA-2026-011','DDR5 RAM Stick',NULL,1,'2026-07-01',15000.00,0.00,5,NULL,'straight_line',0.00,15000.00,NULL,NULL,'Good','active',NULL,NULL,NULL,NULL,'2026-07-19 04:54:53','2026-07-19 04:54:53');
/*!40000 ALTER TABLE `fa_fixed_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fin_audits`
--

DROP TABLE IF EXISTS `fin_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audit_year` smallint unsigned NOT NULL,
  `audit_month` tinyint unsigned NOT NULL COMMENT '1-12',
  `audit_type` enum('Internal','External','Regulatory','Financial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `scheduled_date` date DEFAULT NULL,
  `recurrence` enum('none','monthly','quarterly','annually') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `auditor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Complaint','Pending','Failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `date_completed` date DEFAULT NULL,
  `findings` text COLLATE utf8mb4_unicode_ci,
  `checklist` json DEFAULT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fin_audits_year_month` (`audit_year`,`audit_month`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fin_audits`
--

LOCK TABLES `fin_audits` WRITE;
/*!40000 ALTER TABLE `fin_audits` DISABLE KEYS */;
INSERT INTO `fin_audits` VALUES (1,NULL,2026,1,'Internal','medium','2026-01-15','none','J. Santos','Complaint','2026-01-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(2,NULL,2026,2,'External','medium','2026-02-15','none','M. Reyes','Complaint','2026-02-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(3,NULL,2026,3,'Regulatory','medium','2026-03-28','none','External Auditor Co.','Failed','2026-03-28','Discrepancies found during review. Follow-up audit required.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(4,NULL,2026,4,'Financial','medium','2026-04-15','none','A. Cruz','Complaint','2026-04-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(5,NULL,2026,5,'Internal','medium','2026-05-15','none','Compliance Team','Complaint','2026-05-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(6,NULL,2026,6,'External','medium','2026-06-15','none','J. Santos','Pending','2026-06-15','Review in progress, awaiting final sign-off.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(7,NULL,2026,7,'Regulatory','medium','2026-07-15','none','M. Reyes','Complaint','2026-07-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(8,NULL,2026,8,'Financial','medium','2026-08-28','none','External Auditor Co.','Failed','2026-08-28','Discrepancies found during review. Follow-up audit required.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(9,NULL,2026,9,'Internal','medium','2026-09-15','none','A. Cruz','Complaint','2026-09-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(10,NULL,2026,10,'External','medium','2026-10-15','none','Compliance Team','Complaint','2026-10-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(11,NULL,2026,11,'Regulatory','medium','2026-11-15','none','J. Santos','Pending','2026-11-15','Review in progress, awaiting final sign-off.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22'),(12,NULL,2026,12,'Financial','medium','2026-12-15','none','M. Reyes','Complaint','2026-12-15','No issues found. All records verified.',NULL,1,'2026-07-19 05:47:45','2026-07-19 05:54:22');
/*!40000 ALTER TABLE `fin_audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fin_compliance_activities`
--

DROP TABLE IF EXISTS `fin_compliance_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_compliance_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `activity_date` date NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_type` enum('Internal Audit','External Audit','Regulatory','Tax Filing','Compliance Review','Budget','Financial Audit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Passed','Failed','Pending','Scheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fin_compliance_activities_date` (`activity_date`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fin_compliance_activities`
--

LOCK TABLES `fin_compliance_activities` WRITE;
/*!40000 ALTER TABLE `fin_compliance_activities` DISABLE KEYS */;
INSERT INTO `fin_compliance_activities` VALUES (1,'2026-06-30','Internal Audit Completed','Internal Audit','Passed','Q2 internal controls review closed with no major findings.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(2,'2026-06-29','Internal Audit Completed','Internal Audit','Passed','Payroll process audit completed, minor recommendations issued.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(3,'2026-06-28','Tax Filing Completed','Tax Filing','Passed','Monthly VAT/GST return filed on time.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(4,'2026-06-28','Regulatory Update','Regulatory','Passed','New disclosure requirement reviewed and acknowledged by compliance team.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(5,'2026-06-25','External Audit Scheduled','External Audit','Scheduled','Annual external audit scheduled with third-party auditor for Q3.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(6,'2026-06-20','Compliance Review Failed','Compliance Review','Failed','March compliance audit failed review; corrective action plan initiated.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(7,'2026-06-18','Budget Variance Flagged','Budget','Pending','Operations department spent 6.8% over approved budget.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(8,'2026-06-10','Financial Audit Completed','Financial Audit','Passed','Year-to-date financial statements reconciled and verified.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(9,'2026-06-05','VAT/GST Return Filed','Tax Filing','Passed','VAT/GST return filed successfully with no discrepancies.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(10,'2026-05-28','Regulatory Update','Regulatory','Passed','Updated AML guidelines circulated to relevant departments.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(11,'2026-05-15','Internal Audit Completed','Internal Audit','Passed','Procurement controls audit completed successfully.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(12,'2026-05-05','Compliance Review Passed','Compliance Review','Passed','April compliance audit passed with a 98% score.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(13,'2026-04-22','External Audit Completed','External Audit','Passed','Third-party external audit completed, report issued to the board.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(14,'2026-04-10','Tax Filing Completed','Tax Filing','Passed','Q1 corporate tax filing submitted ahead of deadline.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(15,'2026-03-18','Compliance Review Failed','Compliance Review','Failed','March compliance audit failed review due to incomplete documentation.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(16,'2026-02-14','Internal Audit Completed','Internal Audit','Passed','February internal review closed, no issues found.','2026-07-19 05:05:19','2026-07-19 05:05:19'),(17,'2026-01-20','Regulatory Update','Regulatory','Passed','Start-of-year regulatory checklist reviewed and updated.','2026-07-19 05:05:19','2026-07-19 05:05:19');
/*!40000 ALTER TABLE `fin_compliance_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fin_tax_calendar`
--

DROP TABLE IF EXISTS `fin_tax_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_tax_calendar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Upcoming','Filed','Overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Upcoming',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fin_tax_calendar`
--

LOCK TABLES `fin_tax_calendar` WRITE;
/*!40000 ALTER TABLE `fin_tax_calendar` DISABLE KEYS */;
INSERT INTO `fin_tax_calendar` VALUES (1,'Income Tax','2026-04-15',121250.00,'Upcoming','2026-07-19 05:05:19','2026-07-19 05:05:19'),(2,'VAT / GST','2026-03-31',304800.00,'Filed','2026-07-19 05:05:19','2026-07-19 05:05:19'),(3,'Payroll Tax','2026-05-10',31500.00,'Upcoming','2026-07-19 05:05:19','2026-07-19 05:05:19'),(4,'Withholding Tax','2026-03-31',9500.00,'Filed','2026-07-19 05:05:19','2026-07-19 05:05:19');
/*!40000 ALTER TABLE `fin_tax_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fin_tax_filings`
--

DROP TABLE IF EXISTS `fin_tax_filings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_tax_filings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tax_year` smallint unsigned NOT NULL,
  `tax_type` enum('Income Tax','VAT / GST','Payroll Tax','Withholding Tax') COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'display value, e.g. 25%',
  `taxable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_due` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deadline` date NOT NULL,
  `status` enum('Filed','Calculated','Pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fin_tax_filings_year` (`tax_year`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fin_tax_filings`
--

LOCK TABLES `fin_tax_filings` WRITE;
/*!40000 ALTER TABLE `fin_tax_filings` DISABLE KEYS */;
INSERT INTO `fin_tax_filings` VALUES (1,2026,'Income Tax','25%',485000.00,121250.00,'2026-04-15','Calculated','2026-07-19 05:05:19','2026-07-19 05:05:19'),(2,2026,'VAT / GST','12%',2540000.00,304800.00,'2026-03-31','Filed','2026-07-19 05:05:19','2026-07-19 05:05:19'),(3,2026,'Payroll Tax','7.5%',420000.00,31500.00,'2026-05-10','Pending','2026-07-19 05:05:19','2026-07-19 05:05:19'),(4,2026,'Withholding Tax','10%',95000.00,9500.00,'2026-03-31','Filed','2026-07-19 05:05:19','2026-07-19 05:05:19');
/*!40000 ALTER TABLE `fin_tax_filings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gl_accounts`
--

DROP TABLE IF EXISTS `gl_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gl_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('Asset','Liability','Equity','Revenue','Expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_code` (`account_code`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gl_accounts`
--

LOCK TABLES `gl_accounts` WRITE;
/*!40000 ALTER TABLE `gl_accounts` DISABLE KEYS */;
INSERT INTO `gl_accounts` VALUES (1,'1000','Cash on Hand','Asset','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(2,'1100','Accounts Receivable','Asset','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(3,'2000','Accounts Payable','Liability','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(4,'3000','Owner\'s Equity','Equity','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(5,'4000','Sales Revenue','Revenue','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(6,'5000','Office Supplies Expense','Expense','Active','2026-07-18 11:30:18','2026-07-18 11:30:18'),(7,'5100','Accounts Payable Purchases','Expense','Active','2026-07-18 03:35:22','2026-07-18 03:35:22'),(28,'1500','Fixed Assets','Asset','Active','2026-07-18 15:09:30','2026-07-18 15:09:30'),(29,'1510','Accumulated Depreciation','Asset','Active','2026-07-18 15:09:30','2026-07-18 15:09:30'),(30,'5200','Depreciation Expense','Expense','Active','2026-07-18 15:09:30','2026-07-18 15:09:30'),(31,'7000','Gain/Loss on Disposal','Revenue','Active','2026-07-18 15:09:30','2026-07-18 15:09:30');
/*!40000 ALTER TABLE `gl_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gl_entries`
--

DROP TABLE IF EXISTS `gl_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gl_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('Draft','Posted') COLLATE utf8mb4_unicode_ci DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gl_entries`
--

LOCK TABLES `gl_entries` WRITE;
/*!40000 ALTER TABLE `gl_entries` DISABLE KEYS */;
INSERT INTO `gl_entries` VALUES (1,'2026-07-01','JE-001','Initial cash investment','Posted','2026-07-18 11:30:18','2026-07-18 11:30:18'),(2,'2026-07-05','JE-002','Sold goods on credit','Posted','2026-07-18 11:30:18','2026-07-18 11:30:18'),(3,'2026-07-10','JE-003','Purchased office supplies','Draft','2026-07-18 11:30:18','2026-07-18 11:30:18'),(4,'2026-07-12','AP-INV-INV-2026-111','AP Invoice INV-2026-111 — Prime Industrial','Posted','2026-07-18 03:35:22','2026-07-18 03:35:22'),(5,'2026-05-15','AP-INV-INV-2024-00125','AP Invoice INV-2024-00125 — Global Supplies Co.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(6,'2026-06-02','AP-INV-INV-3002','AP Invoice INV-3002 — Tech Solutions Inc.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(7,'2026-06-12','AP-INV-INV-3004','AP Invoice INV-3004 — Metro Office Supply','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(8,'2026-06-08','AP-INV-INV-3001','AP Invoice INV-3001 — ABC Trading','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(9,'2026-05-22','AP-INV-INV-3003','AP Invoice INV-3003 — Prime Industrial','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(10,'2026-06-24','AP-INV-INV-3006','AP Invoice INV-3006 — Blue Ocean Corp.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(11,'2026-06-01','AP-INV-INV-3007','AP Invoice INV-3007 — Evergreen Supplies','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(12,'2026-07-11','AP-INV-INV-2026-999','AP Invoice INV-2026-999 — Global Supplies Co.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(13,'2026-07-12','AP-INV-INV-2026-234','AP Invoice INV-2026-234 — Tech Solutions Inc.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(14,'2026-07-12','AP-INV-INV-2026-907','AP Invoice INV-2026-907 — Industrial Parts Corp.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(15,'2026-07-12','AP-INV-INV-2026-00192','AP Invoice INV-2026-00192 — Evergreen Supplies','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(16,'2026-07-12','AP-INV-INV-2026-0001','AP Invoice INV-2026-0001 — Logistic Service LLC.','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(17,'2026-07-12','AP-INV-INV-2026-0789','AP Invoice INV-2026-0789 — Evergreen Supplies','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(18,'2026-07-13','AP-INV-INV-2026-0009','AP Invoice INV-2026-0009 — Evergreen Supplies','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(19,'2026-07-12','AP-INV-INV-2026-0082','AP Invoice INV-2026-0082 — Evergreen Supplies','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(20,'2026-07-11','AP-PAY-PAY-2026-003','Payment PAY-2026-003 — Invoice INV-3001 (ABC Trading)','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(21,'2026-07-24','AP-PAY-PAY-2026-004','Payment PAY-2026-004 — Invoice INV-3006 (Blue Ocean Corp.)','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(22,'2026-07-01','AP-PAY-PAY-2026-005','Payment PAY-2026-005 — Invoice INV-3007 (Evergreen Supplies)','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(23,'2026-07-12','AP-PAY-PAY-2026-006','Payment PAY-2026-006 — Invoice INV-3002 (Tech Solutions Inc.)','Posted','2026-07-18 11:36:00','2026-07-18 11:36:00'),(27,'2026-07-18','FA-DEP-20260718152612-AGQR','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(28,'2026-07-18','FA-DEP-20260718152612-VDWD','Depreciation expense for asset FA-2021-004 - Executive Office Desk','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(29,'2026-07-18','FA-DEP-20260718152612-37LD','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10)','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(30,'2026-07-18','FA-DEP-20260718152612-HU0M','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(31,'2026-07-18','FA-DEP-20260718152612-SY1T','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(32,'2026-07-18','FA-DEP-20260718152612-M3HP','Depreciation expense for asset FA-2018-009 - Main Office Building','Posted','2026-07-18 07:26:12','2026-07-18 07:26:12'),(33,'2026-07-07','FA-ACQ-20260718153054-IADW','Acquisition of asset FA-2026-010 - Gold ni Harvie','Posted','2026-07-18 07:30:54','2026-07-18 07:30:54'),(34,'2023-01-15','FA-ACQ-FA-2023-001','Acquisition of asset FA-2023-001 - Dell OptiPlex Desktop PC','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(35,'2023-03-10','FA-ACQ-FA-2023-002','Acquisition of asset FA-2023-002 - HP LaserJet Printer','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(36,'2022-06-20','FA-ACQ-FA-2022-003','Acquisition of asset FA-2022-003 - Split-Type Air Conditioner','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(37,'2021-05-05','FA-ACQ-FA-2021-004','Acquisition of asset FA-2021-004 - Executive Office Desk','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(38,'2021-05-05','FA-ACQ-FA-2021-005','Acquisition of asset FA-2021-005 - Ergonomic Office Chairs (Set of 10)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(39,'2020-09-12','FA-ACQ-FA-2020-006','Acquisition of asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(40,'2022-02-18','FA-ACQ-FA-2022-007','Acquisition of asset FA-2022-007 - Toyota Hiace Delivery Van','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(41,'2019-11-30','FA-ACQ-FA-2019-008','Acquisition of asset FA-2019-008 - Mitsubishi Mirage Company Car','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(42,'2018-01-01','FA-ACQ-FA-2018-009','Acquisition of asset FA-2018-009 - Main Office Building','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(43,'2026-07-07','FA-ACQ-FA-2026-010','Acquisition of asset FA-2026-010 - Gold ni Harvie','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(49,'2024-01-15','FA-DEP-1','Depreciation expense for asset FA-2023-001 - Dell OptiPlex Desktop PC (period 2024-01-15)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(50,'2025-01-15','FA-DEP-2','Depreciation expense for asset FA-2023-001 - Dell OptiPlex Desktop PC (period 2025-01-15)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(51,'2026-01-15','FA-DEP-3','Depreciation expense for asset FA-2023-001 - Dell OptiPlex Desktop PC (period 2026-01-15)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(52,'2024-03-10','FA-DEP-4','Depreciation expense for asset FA-2023-002 - HP LaserJet Printer (period 2024-03-10)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(53,'2025-03-10','FA-DEP-5','Depreciation expense for asset FA-2023-002 - HP LaserJet Printer (period 2025-03-10)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(54,'2026-03-10','FA-DEP-6','Depreciation expense for asset FA-2023-002 - HP LaserJet Printer (period 2026-03-10)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(55,'2023-06-20','FA-DEP-7','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner (period 2023-06-20)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(56,'2024-06-20','FA-DEP-8','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner (period 2024-06-20)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(57,'2025-06-20','FA-DEP-9','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner (period 2025-06-20)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(58,'2026-06-20','FA-DEP-10','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner (period 2026-06-20)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(59,'2022-05-05','FA-DEP-11','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2022-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(60,'2023-05-05','FA-DEP-12','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2023-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(61,'2024-05-05','FA-DEP-13','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2024-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(62,'2025-05-05','FA-DEP-14','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2025-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(63,'2026-05-05','FA-DEP-15','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2026-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(64,'2022-05-05','FA-DEP-16','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2022-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(65,'2023-05-05','FA-DEP-17','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2023-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(66,'2024-05-05','FA-DEP-18','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2024-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(67,'2025-05-05','FA-DEP-19','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2025-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(68,'2026-05-05','FA-DEP-20','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2026-05-05)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(69,'2021-09-12','FA-DEP-21','Depreciation expense for asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer) (period 2021-09-12)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(70,'2022-09-12','FA-DEP-22','Depreciation expense for asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer) (period 2022-09-12)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(71,'2023-09-12','FA-DEP-23','Depreciation expense for asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer) (period 2023-09-12)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(72,'2024-09-12','FA-DEP-24','Depreciation expense for asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer) (period 2024-09-12)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(73,'2025-09-12','FA-DEP-25','Depreciation expense for asset FA-2020-006 - Filing Cabinet (Steel, 4-Drawer) (period 2025-09-12)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(74,'2023-02-18','FA-DEP-26','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van (period 2023-02-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(75,'2024-02-18','FA-DEP-27','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van (period 2024-02-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(76,'2025-02-18','FA-DEP-28','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van (period 2025-02-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(77,'2026-02-18','FA-DEP-29','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van (period 2026-02-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(78,'2020-11-30','FA-DEP-30','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2020-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(79,'2021-11-30','FA-DEP-31','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2021-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(80,'2022-11-30','FA-DEP-32','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2022-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(81,'2023-11-30','FA-DEP-33','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2023-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(82,'2024-11-30','FA-DEP-34','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2024-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(83,'2025-11-30','FA-DEP-35','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2025-11-30)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(84,'2019-01-01','FA-DEP-36','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2019-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(85,'2020-01-01','FA-DEP-37','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2020-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(86,'2021-01-01','FA-DEP-38','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2021-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(87,'2022-01-01','FA-DEP-39','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2022-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(88,'2023-01-01','FA-DEP-40','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2023-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(89,'2024-01-01','FA-DEP-41','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2024-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(90,'2025-01-01','FA-DEP-42','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2025-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(91,'2026-01-01','FA-DEP-43','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2026-01-01)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(92,'2026-07-18','FA-DEP-46','Depreciation expense for asset FA-2022-003 - Split-Type Air Conditioner (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(93,'2026-07-18','FA-DEP-47','Depreciation expense for asset FA-2021-004 - Executive Office Desk (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(94,'2026-07-18','FA-DEP-48','Depreciation expense for asset FA-2021-005 - Ergonomic Office Chairs (Set of 10) (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(95,'2026-07-18','FA-DEP-49','Depreciation expense for asset FA-2022-007 - Toyota Hiace Delivery Van (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(96,'2026-07-18','FA-DEP-50','Depreciation expense for asset FA-2019-008 - Mitsubishi Mirage Company Car (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(97,'2026-07-18','FA-DEP-51','Depreciation expense for asset FA-2018-009 - Main Office Building (period 2026-07-18)','Posted','2026-07-18 15:44:27','2026-07-18 15:44:27'),(117,'2026-04-30','AR-INV-INV-20260718181101','AR Invoice INV-20260718181101 - ABC Corporation','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(118,'2026-07-19','AR-INV-INV-20260718181614','AR Invoice INV-20260718181614 - XYZ Enterprises','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(119,'2026-04-15','AR-INV-INV-20260718181921','AR Invoice INV-20260718181921 - Juan Dela Cruz','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(120,'2026-06-18','AR-INV-INV-20260718182017','AR Invoice INV-20260718182017 - Pedro Reyes','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(121,'2026-07-19','AR-INV-INV-20260718182212','AR Invoice INV-20260718182212 - Maria Santos','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(122,'2026-07-19','AR-INV-INV-20260718182324','AR Invoice INV-20260718182324 - Sofia Navarro','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(123,'2026-05-24','AR-INV-INV-20260718183332','AR Invoice INV-20260718183332 - Angela Cruz','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(124,'2026-05-20','AR-INV-INV-20260718183552','AR Invoice INV-20260718183552 - Kristine Ramos','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(132,'2026-07-19','AR-PAY-PAY-20260718-0001','AR Payment PAY-20260718-0001 - ABC Corporation','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(133,'2026-07-18','AR-PAY-PAY-20260718-0002','AR Payment PAY-20260718-0002 - ABC Corporation','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(134,'2026-07-19','AR-PAY-PAY-20260718-0003','AR Payment PAY-20260718-0003 - ABC Corporation','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(135,'2026-07-19','AR-PAY-PAY-20260718-0004','AR Payment PAY-20260718-0004 - ABC Corporation','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(136,'2026-07-19','AR-PAY-PAY-20260718-0005','AR Payment PAY-20260718-0005 - XYZ Enterprises','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(137,'2026-07-19','AR-PAY-PAY-20260718-0006','AR Payment PAY-20260718-0006 - Maria Santos','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(138,'2026-07-19','AR-PAY-PAY-20260718-0007','AR Payment PAY-20260718-0007 - Sofia Navarro','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(139,'2026-07-19','AR-PAY-PAY-20260718-0008','AR Payment PAY-20260718-0008 - Ana Garcia','Posted','2026-07-18 19:09:34','2026-07-18 19:09:34'),(147,'2026-07-18','AR-INV-INV-20260718191840','AR Invoice INV-20260718191840 - Daniel Aquino','Posted','2026-07-18 11:18:40','2026-07-18 11:18:40'),(149,'2026-07-24','AP-INV-INV-2026-1111','AP Invoice INV-2026-1111 — Tech Solutions Inc.','Posted','2026-07-18 19:44:50','2026-07-18 19:44:50'),(150,'2026-07-19','AR-INV-INV-20260719053303','AR Invoice INV-20260719053303 - Miguel Torres','Posted','2026-07-18 21:33:03','2026-07-18 21:33:03'),(151,'2026-07-19','AR-PAY-PAY-20260719-0009','AR Payment PAY-20260719-0009 - Ana Garcia','Posted','2026-07-19 03:42:08','2026-07-19 03:42:08'),(152,'2026-07-19','AR-PAY-PAY-20260719-0010','AR Payment PAY-20260719-0010 - Miguel Torres','Posted','2026-07-19 03:49:56','2026-07-19 03:49:56'),(153,'2026-01-19','AR-PAY-PAY-20260719-0011','AR Payment PAY-20260719-0011 - Jerome Villanueva','Posted','2026-07-19 04:05:57','2026-07-19 04:05:57'),(154,'2026-01-01','AR-INV-INV-20260719120830','AR Invoice INV-20260719120830 - Juan Dela Cruz','Posted','2026-07-19 04:08:30','2026-07-19 04:08:30'),(156,'2026-07-19','AP-INV-INV-2026-4444','AP Invoice INV-2026-4444 — Prime Industrial','Posted','2026-07-19 04:12:06','2026-07-19 04:12:06'),(158,'2026-07-19','AP-PAY-PAY-2026-010','Payment PAY-2026-010 — Invoice INV-2026-4444 (Prime Industrial)','Posted','2026-07-19 04:12:53','2026-07-19 04:12:53'),(160,'2026-07-19','AP-INV-INV-2026-0002','AP Invoice INV-2026-0002 — Prime Industrial','Posted','2026-07-19 04:31:37','2026-07-19 04:31:37'),(161,'2026-07-19','AR-INV-INV-20260719120833','AR Invoice INV-20260719120833 - Kristine Ramos','Posted','2026-07-19 04:46:07','2026-07-19 04:46:07'),(162,'2026-07-01','FA-ACQ-20260719125453-KEEF','Acquisition of asset FA-2026-011 - DDR5 RAM Stick','Posted','2026-07-19 04:54:53','2026-07-19 04:54:53'),(163,'2026-07-01','FA-ACQ-20260719125455-WDSE','Acquisition of asset FA-2026-012 - DDR5 RAM Stick','Posted','2026-07-19 04:54:55','2026-07-19 04:54:55'),(164,'2026-07-01','FA-ACQ-20260719125455-JMCQ','Acquisition of asset FA-2026-013 - DDR5 RAM Stick','Posted','2026-07-19 04:54:55','2026-07-19 04:54:55'),(165,'2026-07-01','FA-ACQ-20260719125513-QJG5','Acquisition of asset FA-2026-014 - DDR5 RAM Stick','Posted','2026-07-19 04:55:13','2026-07-19 04:55:13'),(172,'2026-02-19','AR-INV-INV-20260719141848','AR Invoice INV-20260719141848 - ABC Corporation','Posted','2026-07-19 06:18:48','2026-07-19 06:18:48'),(173,'2026-07-19','AR-PAY-PAY-20260719-0012','AR Payment PAY-20260719-0012 - ABC Corporation','Posted','2026-07-19 06:24:22','2026-07-19 06:24:22'),(175,'2026-07-19','AR-PAY-PAY-20260719-0013','AR Payment PAY-20260719-0013 - Roberto Lim','Posted','2026-07-19 06:30:35','2026-07-19 06:30:35'),(176,'2026-07-19','AR-INV-INV-20260719143544','AR Invoice INV-20260719143544 - XYZ Enterprises','Posted','2026-07-19 06:35:44','2026-07-19 06:35:44'),(177,'2026-03-19','AR-INV-INV-20260719144348','AR Invoice INV-20260719144348 - Miguel Torres','Posted','2026-07-19 06:43:48','2026-07-19 06:43:48');
/*!40000 ALTER TABLE `gl_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gl_entry_lines`
--

DROP TABLE IF EXISTS `gl_entry_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gl_entry_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gl_entry_id` bigint unsigned NOT NULL,
  `gl_account_id` bigint unsigned NOT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `credit` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `gl_entry_id` (`gl_entry_id`),
  KEY `gl_account_id` (`gl_account_id`),
  CONSTRAINT `gl_entry_lines_ibfk_1` FOREIGN KEY (`gl_entry_id`) REFERENCES `gl_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gl_entry_lines_ibfk_2` FOREIGN KEY (`gl_account_id`) REFERENCES `gl_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gl_entry_lines`
--

LOCK TABLES `gl_entry_lines` WRITE;
/*!40000 ALTER TABLE `gl_entry_lines` DISABLE KEYS */;
INSERT INTO `gl_entry_lines` VALUES (1,1,1,50000.00,0.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(2,1,4,0.00,50000.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(3,2,2,15000.00,0.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(4,2,5,0.00,15000.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(5,3,6,2500.00,0.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(6,3,3,0.00,2500.00,'2026-07-18 11:30:18','2026-07-18 11:30:18'),(7,4,7,44.80,0.00,'2026-07-18 03:35:22','2026-07-18 03:35:22'),(8,4,3,0.00,44.80,'2026-07-18 03:35:22','2026-07-18 03:35:22'),(9,5,7,75000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(10,6,7,42500.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(11,7,7,38200.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(12,8,7,85000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(13,9,7,97000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(14,10,7,73500.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(15,11,7,45000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(16,12,7,22.40,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(17,13,7,5600.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(18,14,7,8960.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(19,15,7,36064.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(20,16,7,62720.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(21,17,7,36064.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(22,18,7,36064.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(23,19,7,36064.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(24,5,3,0.00,75000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(25,6,3,0.00,42500.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(26,7,3,0.00,38200.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(27,8,3,0.00,85000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(28,9,3,0.00,97000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(29,10,3,0.00,73500.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(30,11,3,0.00,45000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(31,12,3,0.00,22.40,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(32,13,3,0.00,5600.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(33,14,3,0.00,8960.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(34,15,3,0.00,36064.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(35,16,3,0.00,62720.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(36,17,3,0.00,36064.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(37,18,3,0.00,36064.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(38,19,3,0.00,36064.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(39,20,3,85000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(40,21,3,73500.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(41,22,3,45000.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(42,23,3,42500.00,0.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(46,20,1,0.00,85000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(47,21,1,0.00,73500.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(48,22,1,0.00,45000.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(49,23,1,0.00,42500.00,'2026-07-18 11:36:00','2026-07-18 11:36:00'),(53,27,30,600.00,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(54,27,29,0.00,600.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(55,28,30,170.83,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(56,28,29,0.00,170.83,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(57,29,30,433.33,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(58,29,29,0.00,433.33,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(59,30,30,13541.67,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(60,30,29,0.00,13541.67,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(61,31,30,6666.67,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(62,31,29,0.00,6666.67,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(63,32,30,25000.00,0.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(64,32,29,0.00,25000.00,'2026-07-18 07:26:12','2026-07-18 07:26:12'),(65,33,28,500000.00,0.00,'2026-07-18 07:30:54','2026-07-18 07:30:54'),(66,33,1,0.00,500000.00,'2026-07-18 07:30:54','2026-07-18 07:30:54'),(67,34,28,45000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(68,35,28,15000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(69,36,28,38000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(70,37,28,22000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(71,38,28,55000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(72,39,28,10000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(73,40,28,1450000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(74,41,28,720000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(75,42,28,8500000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(76,43,28,500000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(82,34,1,0.00,45000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(83,35,1,0.00,15000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(84,36,1,0.00,38000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(85,37,1,0.00,22000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(86,38,1,0.00,55000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(87,39,1,0.00,10000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(88,40,1,0.00,1450000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(89,41,1,0.00,720000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(90,42,1,0.00,8500000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(91,43,1,0.00,500000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(97,49,30,8400.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(98,50,30,8400.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(99,51,30,8400.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(100,52,30,2800.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(101,53,30,2800.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(102,54,30,2800.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(103,55,30,7200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(104,56,30,7200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(105,57,30,7200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(106,58,30,7200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(107,59,30,2050.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(108,60,30,2050.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(109,61,30,2050.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(110,62,30,2050.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(111,63,30,2050.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(112,64,30,5200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(113,65,30,5200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(114,66,30,5200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(115,67,30,5200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(116,68,30,5200.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(117,69,30,1150.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(118,70,30,1150.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(119,71,30,1150.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(120,72,30,1150.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(121,73,30,1150.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(122,74,30,162500.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(123,75,30,162500.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(124,76,30,162500.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(125,77,30,162500.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(126,78,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(127,79,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(128,80,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(129,81,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(130,82,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(131,83,30,80000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(132,84,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(133,85,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(134,86,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(135,87,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(136,88,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(137,89,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(138,90,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(139,91,30,300000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(140,92,30,600.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(141,93,30,170.83,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(142,94,30,433.33,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(143,95,30,13541.67,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(144,96,30,6666.67,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(145,97,30,25000.00,0.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(160,49,29,0.00,8400.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(161,50,29,0.00,8400.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(162,51,29,0.00,8400.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(163,52,29,0.00,2800.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(164,53,29,0.00,2800.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(165,54,29,0.00,2800.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(166,55,29,0.00,7200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(167,56,29,0.00,7200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(168,57,29,0.00,7200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(169,58,29,0.00,7200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(170,59,29,0.00,2050.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(171,60,29,0.00,2050.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(172,61,29,0.00,2050.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(173,62,29,0.00,2050.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(174,63,29,0.00,2050.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(175,64,29,0.00,5200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(176,65,29,0.00,5200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(177,66,29,0.00,5200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(178,67,29,0.00,5200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(179,68,29,0.00,5200.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(180,69,29,0.00,1150.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(181,70,29,0.00,1150.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(182,71,29,0.00,1150.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(183,72,29,0.00,1150.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(184,73,29,0.00,1150.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(185,74,29,0.00,162500.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(186,75,29,0.00,162500.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(187,76,29,0.00,162500.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(188,77,29,0.00,162500.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(189,78,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(190,79,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(191,80,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(192,81,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(193,82,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(194,83,29,0.00,80000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(195,84,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(196,85,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(197,86,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(198,87,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(199,88,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(200,89,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(201,90,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(202,91,29,0.00,300000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(203,92,29,0.00,600.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(204,93,29,0.00,170.83,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(205,94,29,0.00,433.33,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(206,95,29,0.00,13541.67,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(207,96,29,0.00,6666.67,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(208,97,29,0.00,25000.00,'2026-07-18 15:44:27','2026-07-18 15:44:27'),(239,15,1,9000000.00,0.00,'2026-07-18 16:04:33','2026-07-18 16:06:08'),(240,15,4,0.00,9000000.00,'2026-07-18 16:04:33','2026-07-18 16:06:08'),(243,117,2,308000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(244,118,2,246400.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(245,119,2,711200.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(246,120,2,190400.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(247,121,2,285.60,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(248,122,2,252000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(249,123,2,285600.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(250,124,2,123200.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(258,117,5,0.00,308000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(259,118,5,0.00,246400.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(260,119,5,0.00,711200.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(261,120,5,0.00,190400.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(262,121,5,0.00,285.60,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(263,122,5,0.00,252000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(264,123,5,0.00,285600.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(265,124,5,0.00,123200.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(273,132,1,20000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(274,133,1,200000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(275,134,1,5000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(276,135,1,83000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(277,136,1,100000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(278,137,1,285.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(279,138,1,200000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(280,139,1,100000.00,0.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(288,132,2,0.00,20000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(289,133,2,0.00,200000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(290,134,2,0.00,5000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(291,135,2,0.00,83000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(292,136,2,0.00,100000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(293,137,2,0.00,285.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(294,138,2,0.00,200000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(295,139,2,0.00,100000.00,'2026-07-18 19:09:34','2026-07-18 19:09:34'),(303,147,2,224.00,0.00,'2026-07-18 11:18:40','2026-07-18 11:18:40'),(304,147,5,0.00,224.00,'2026-07-18 11:18:40','2026-07-18 11:18:40'),(307,149,7,11200.00,0.00,'2026-07-18 19:44:50','2026-07-18 19:44:50'),(308,149,3,0.00,11200.00,'2026-07-18 19:44:50','2026-07-18 19:44:50'),(309,150,2,112000.00,0.00,'2026-07-18 21:33:03','2026-07-18 21:33:03'),(310,150,5,0.00,112000.00,'2026-07-18 21:33:03','2026-07-18 21:33:03'),(311,151,1,100000.00,0.00,'2026-07-19 03:42:08','2026-07-19 03:42:08'),(312,151,2,0.00,100000.00,'2026-07-19 03:42:08','2026-07-19 03:42:08'),(313,152,1,50000.00,0.00,'2026-07-19 03:49:56','2026-07-19 03:49:56'),(314,152,2,0.00,50000.00,'2026-07-19 03:49:56','2026-07-19 03:49:56'),(315,153,1,250000.00,0.00,'2026-07-19 04:05:57','2026-07-19 04:05:57'),(316,153,2,0.00,250000.00,'2026-07-19 04:05:57','2026-07-19 04:05:57'),(317,154,2,448000.00,0.00,'2026-07-19 04:08:30','2026-07-19 04:08:30'),(318,154,5,0.00,448000.00,'2026-07-19 04:08:30','2026-07-19 04:08:30'),(321,156,7,1568000.00,0.00,'2026-07-19 04:12:06','2026-07-19 04:12:06'),(322,156,3,0.00,1568000.00,'2026-07-19 04:12:06','2026-07-19 04:12:06'),(325,158,3,1568000.00,0.00,'2026-07-19 04:12:53','2026-07-19 04:12:53'),(326,158,1,0.00,1568000.00,'2026-07-19 04:12:53','2026-07-19 04:12:53'),(329,160,7,44800.00,0.00,'2026-07-19 04:31:37','2026-07-19 04:31:37'),(330,160,3,0.00,44800.00,'2026-07-19 04:31:37','2026-07-19 04:31:37'),(331,161,2,190400.00,0.00,'2026-07-19 04:46:07','2026-07-19 04:46:07'),(332,161,5,0.00,190400.00,'2026-07-19 04:46:07','2026-07-19 04:46:07'),(333,162,28,15000.00,0.00,'2026-07-19 04:54:53','2026-07-19 04:54:53'),(334,162,1,0.00,15000.00,'2026-07-19 04:54:53','2026-07-19 04:54:53'),(335,163,28,15000.00,0.00,'2026-07-19 04:54:55','2026-07-19 04:54:55'),(336,163,1,0.00,15000.00,'2026-07-19 04:54:55','2026-07-19 04:54:55'),(337,164,28,15000.00,0.00,'2026-07-19 04:54:55','2026-07-19 04:54:55'),(338,164,1,0.00,15000.00,'2026-07-19 04:54:55','2026-07-19 04:54:55'),(339,165,28,15000.00,0.00,'2026-07-19 04:55:13','2026-07-19 04:55:13'),(340,165,1,0.00,15000.00,'2026-07-19 04:55:13','2026-07-19 04:55:13'),(343,172,2,504000.00,0.00,'2026-07-19 06:18:48','2026-07-19 06:18:48'),(344,172,5,0.00,504000.00,'2026-07-19 06:18:48','2026-07-19 06:18:48'),(345,173,1,100000.00,0.00,'2026-07-19 06:24:22','2026-07-19 06:24:22'),(346,173,2,0.00,100000.00,'2026-07-19 06:24:22','2026-07-19 06:24:22'),(349,175,1,1000000.00,0.00,'2026-07-19 06:30:35','2026-07-19 06:30:35'),(350,175,2,0.00,1000000.00,'2026-07-19 06:30:35','2026-07-19 06:30:35'),(351,176,2,448000.00,0.00,'2026-07-19 06:35:44','2026-07-19 06:35:44'),(352,176,5,0.00,448000.00,'2026-07-19 06:35:44','2026-07-19 06:35:44'),(353,177,2,715400.00,0.00,'2026-07-19 06:43:48','2026-07-19 06:43:48'),(354,177,5,0.00,715400.00,'2026-07-19 06:43:48','2026-07-19 06:43:48');
/*!40000 ALTER TABLE `gl_entry_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_18_000000_create_ap_purchase_order_items_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0ijnxPf2MtswQVmQ2znUPQfEwtBqldPEQXDZYfLM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJXUFpIdW15cXFLdXZ3Z0tHN2c5SEE5UHVoN2NOWVNScmJkcElsYTNGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9idWRnZXRzIiwicm91dGUiOiJidWRnZXQuZGF0YSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImFwIjp7Imxhc3RfbWF0Y2hfaW52b2ljZV9pZCI6MjYsImxhc3RfcmV2aWV3X2ludm9pY2VfaWQiOjI2fX0=',1784467949),('8O1OhdYmjD2FaoadIV6k70zPeYcazk6aw4ZLtobw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJHbjRRelIwbEZWNGV4WmlxNEJQRmZqc3hXOXh4TFVzRUUyZUdoaTVBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9idWRnZXRzIiwicm91dGUiOiJidWRnZXQuZGF0YSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1784465755),('dqxgKAArElXQq15zeHier5UgaJOswVMqoJcdslwY',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJCQWVxNHVVdXVzaXpvUTFmRXBUWU9kYXZSMEhObTFhQVZZSXV1a1Q2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hY2NvdW50cy1yZWNlaXZhYmxlIiwicm91dGUiOiJyZWNlaXZhYmxlLmRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1784472189),('u6fNlqyjLfw0GKBqt1eglzj432kxyf0EyBNcRQSo',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiIwdFdGMkhIZ1lTOHVEY3FYV0MyaW5oM2M4dzBTQnc0YmgxdGZMenVzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ZpbmFuY2UtYW5kLWFjY291bnRpbmcudGVzdFwvYWNjb3VudHMtcmVjZWl2YWJsZSIsInJvdXRlIjoicmVjZWl2YWJsZS5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1784472300),('uWkAmeidaxFgRr5lx26Ip6vIPHl0c0STAf9ETJVF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ3VFBuMUZyUWVYUzBHQ0JDb0pEcFBmajVYc09zdnJjM3RDelppeFA0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9maXhlZC1hc3NldHMiLCJyb3V0ZSI6ImZpeGVkLWFzc2V0cy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1784466815);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-20 14:03:17
