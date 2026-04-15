/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `api_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` json DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `ip_whitelist` json DEFAULT NULL,
  `rate_limit` int NOT NULL DEFAULT '60',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_tokens_token_unique` (`token`),
  UNIQUE KEY `api_tokens_token_hash_unique` (`token_hash`),
  KEY `api_tokens_user_id_is_active_index` (`user_id`,`is_active`),
  KEY `api_tokens_token_hash_index` (`token_hash`),
  CONSTRAINT `api_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `app_installations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_installations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `version_id` bigint unsigned DEFAULT NULL,
  `device_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_manufacturer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `android_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_build` int DEFAULT NULL,
  `installed_at` timestamp NULL DEFAULT NULL,
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_installations_user_id_device_id_unique` (`user_id`,`device_id`),
  KEY `app_installations_version_id_foreign` (`version_id`),
  KEY `app_installations_device_id_index` (`device_id`),
  CONSTRAINT `app_installations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_installations_version_id_foreign` FOREIGN KEY (`version_id`) REFERENCES `app_versions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `app_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `build_number` int NOT NULL,
  `apk_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apk_size` bigint DEFAULT NULL,
  `changelog` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `min_android_version` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_versions_version_unique` (`version`),
  UNIQUE KEY `app_versions_build_number_unique` (`build_number`),
  KEY `app_versions_created_by_foreign` (`created_by`),
  CONSTRAINT `app_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `audit_logs_module_created_at_index` (`module`,`created_at`),
  KEY `audit_logs_action_index` (`action`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `avisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avisos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `conteudo` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('info','success','warning','danger','promocao','novidade','anuncio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `posicao` enum('topo','meio','rodape','flutuante') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'topo',
  `estilo` enum('banner','announcement','cta','modal','toast') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'banner',
  `cor_primaria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cor_secundaria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_acao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `texto_botao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `botao_exibir` tinyint(1) NOT NULL DEFAULT '1',
  `dismissivel` tinyint(1) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `destacar` tinyint(1) NOT NULL DEFAULT '0',
  `data_inicio` timestamp NULL DEFAULT NULL,
  `data_fim` timestamp NULL DEFAULT NULL,
  `ordem` int NOT NULL DEFAULT '0',
  `visualizacoes` int NOT NULL DEFAULT '0',
  `cliques` int NOT NULL DEFAULT '0',
  `configuracoes` json DEFAULT NULL,
  `church_ids` json DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `avisos_ativos_index` (`ativo`,`data_inicio`,`data_fim`),
  KEY `avisos_tipo_ativo_index` (`tipo`,`ativo`),
  KEY `avisos_posicao_ativo_index` (`posicao`,`ativo`),
  KEY `avisos_ordem_index` (`ordem`),
  KEY `avisos_created_at_index` (`created_at`),
  KEY `avisos_user_id_foreign` (`user_id`),
  CONSTRAINT `avisos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_book_panoramas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_book_panoramas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_number` tinyint unsigned NOT NULL COMMENT '1-66 canônico',
  `testament` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'old',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_written` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_central` text COLLATE utf8mb4_unicode_ci,
  `recipients` text COLLATE utf8mb4_unicode_ci,
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_book_panoramas_book_number_language_unique` (`book_number`,`language`),
  KEY `bible_book_panoramas_book_number_index` (`book_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_chapter_audio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_chapter_audio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bible_version_id` bigint unsigned NOT NULL,
  `book_number` smallint unsigned NOT NULL,
  `chapter_number` smallint unsigned NOT NULL,
  `audio_url` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_chapter_audio_version_book_ch_unique` (`bible_version_id`,`book_number`,`chapter_number`),
  CONSTRAINT `bible_chapter_audio_bible_version_id_foreign` FOREIGN KEY (`bible_version_id`) REFERENCES `bible_versions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_commentary_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_commentary_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `source_id` bigint unsigned NOT NULL,
  `book_number` tinyint unsigned NOT NULL,
  `chapter_from` smallint unsigned NOT NULL,
  `verse_from` smallint unsigned NOT NULL,
  `chapter_to` smallint unsigned NOT NULL,
  `verse_to` smallint unsigned NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_commentary_entries_source_id_foreign` (`source_id`),
  KEY `bible_commentary_entries_ref_idx` (`book_number`,`chapter_from`,`verse_from`),
  CONSTRAINT `bible_commentary_entries_source_id_foreign` FOREIGN KEY (`source_id`) REFERENCES `bible_commentary_sources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_commentary_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_commentary_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pt',
  `license_note` text COLLATE utf8mb4_unicode_ci,
  `url_template` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_commentary_sources_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_cross_references`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_cross_references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `testament` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_book_number` tinyint unsigned NOT NULL,
  `from_chapter` smallint unsigned NOT NULL,
  `from_verse` smallint unsigned NOT NULL,
  `to_book_number` tinyint unsigned NOT NULL,
  `to_chapter` smallint unsigned NOT NULL,
  `to_verse` smallint unsigned NOT NULL,
  `kind` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` smallint unsigned NOT NULL DEFAULT '0',
  `source_slug` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_pt` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_cross_references_from_idx` (`from_book_number`,`from_chapter`,`from_verse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `verse_id` bigint unsigned NOT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_favorites_user_id_verse_id_unique` (`user_id`,`verse_id`),
  KEY `bible_favorites_verse_id_foreign` (`verse_id`),
  KEY `bible_favorites_user_id_index` (`user_id`),
  CONSTRAINT `bible_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_favorites_verse_id_foreign` FOREIGN KEY (`verse_id`) REFERENCES `verses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_interlinear_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_interlinear_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `testament` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_number` tinyint unsigned NOT NULL,
  `chapter_number` smallint unsigned NOT NULL,
  `verse_number` smallint unsigned NOT NULL,
  `token_index` smallint unsigned NOT NULL,
  `surface_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `strongs_key` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strongs_raw` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morphology` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_interlinear_tokens_verse_token_unique` (`testament`,`book_number`,`chapter_number`,`verse_number`,`token_index`),
  KEY `bible_interlinear_tokens_book_chapter_idx` (`book_number`,`chapter_number`),
  KEY `bible_interlinear_tokens_strongs_key_index` (`strongs_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_metadata` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bible_version_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `chapter_number` int NOT NULL,
  `verse_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_metadata_bible_version_id_book_id_chapter_number_unique` (`bible_version_id`,`book_id`,`chapter_number`),
  KEY `bible_metadata_book_id_foreign` (`book_id`),
  CONSTRAINT `bible_metadata_bible_version_id_foreign` FOREIGN KEY (`bible_version_id`) REFERENCES `bible_versions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_metadata_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_plan_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_plan_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_day_id` bigint unsigned NOT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `type` enum('scripture','devotional','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scripture',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `book_id` bigint unsigned DEFAULT NULL,
  `chapter_start` int DEFAULT NULL,
  `chapter_end` int DEFAULT NULL,
  `verse_start` int DEFAULT NULL,
  `verse_end` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_plan_contents_plan_day_id_foreign` (`plan_day_id`),
  CONSTRAINT `bible_plan_contents_plan_day_id_foreign` FOREIGN KEY (`plan_day_id`) REFERENCES `bible_plan_days` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_plan_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_plan_days` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint unsigned NOT NULL,
  `day_number` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_plan_days_plan_id_day_number_unique` (`plan_id`,`day_number`),
  CONSTRAINT `bible_plan_days_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `bible_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_plan_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_plan_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `plan_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `projected_end_date` date DEFAULT NULL,
  `current_day_number` int NOT NULL DEFAULT '1',
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `current_streak` int NOT NULL DEFAULT '0',
  `longest_streak` int NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `notification_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_plan_subscriptions_user_id_foreign` (`user_id`),
  KEY `bible_plan_subscriptions_plan_id_foreign` (`plan_id`),
  CONSTRAINT `bible_plan_subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `bible_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_plan_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_plan_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_plan_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `complexity` enum('iniciante','standard','exegetical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `order_type` enum('canonical','chronological','doctrinal','christ_centered','nt_psalms') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'canonical',
  `options` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_plan_templates_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('sequential','chronological','thematic','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `duration_days` int NOT NULL DEFAULT '0',
  `reading_mode` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'digital',
  `allow_back_tracking` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_church_plan` tinyint(1) NOT NULL DEFAULT '0',
  `complexity` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_plans_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_reading_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_reading_audit_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `subscription_id` bigint unsigned NOT NULL,
  `action` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_reading_audit_log_user_id_foreign` (`user_id`),
  KEY `bible_reading_audit_log_subscription_id_action_index` (`subscription_id`,`action`),
  KEY `bible_reading_audit_log_created_at_index` (`created_at`),
  CONSTRAINT `bible_reading_audit_log_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `bible_plan_subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_reading_audit_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_strongs_lexicon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_strongs_lexicon` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `strong_number` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lemma` text COLLATE utf8mb4_unicode_ci,
  `xlit` text COLLATE utf8mb4_unicode_ci,
  `pronounce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `description_original` longtext COLLATE utf8mb4_unicode_ci,
  `lemma_br` text COLLATE utf8mb4_unicode_ci,
  `traduzido_pt` tinyint(1) NOT NULL DEFAULT '0',
  `semantic_equivalent_pt` text COLLATE utf8mb4_unicode_ci,
  `meaning_usage_pt` text COLLATE utf8mb4_unicode_ci,
  `admin_locked` tinyint(1) NOT NULL DEFAULT '0',
  `description_frozen` tinyint(1) NOT NULL DEFAULT '0',
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_strongs_lexicon_strong_number_unique` (`strong_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_user_badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_user_badges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `badge_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_id` bigint unsigned DEFAULT NULL,
  `awarded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_user_badges_user_id_foreign` (`user_id`),
  KEY `bible_user_badges_subscription_id_foreign` (`subscription_id`),
  CONSTRAINT `bible_user_badges_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `bible_plan_subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_user_badges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_user_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_user_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `plan_day_id` bigint unsigned DEFAULT NULL,
  `book_id` bigint unsigned DEFAULT NULL,
  `chapter` int DEFAULT NULL,
  `verse` int DEFAULT NULL,
  `note_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#ffee00',
  `is_private` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bible_user_notes_user_id_foreign` (`user_id`),
  KEY `bible_user_notes_plan_day_id_foreign` (`plan_day_id`),
  CONSTRAINT `bible_user_notes_plan_day_id_foreign` FOREIGN KEY (`plan_day_id`) REFERENCES `bible_plan_days` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bible_user_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_user_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_user_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint unsigned NOT NULL,
  `plan_day_id` bigint unsigned NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_user_progress_subscription_id_plan_day_id_unique` (`subscription_id`,`plan_day_id`),
  KEY `bible_user_progress_plan_day_id_foreign` (`plan_day_id`),
  CONSTRAINT `bible_user_progress_plan_day_id_foreign` FOREIGN KEY (`plan_day_id`) REFERENCES `bible_plan_days` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bible_user_progress_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `bible_plan_subscriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bible_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bible_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pt-BR',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `total_books` int NOT NULL DEFAULT '66',
  `total_chapters` int NOT NULL DEFAULT '0',
  `total_verses` int NOT NULL DEFAULT '0',
  `imported_at` timestamp NULL DEFAULT NULL,
  `audio_url_template` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bible_versions_abbreviation_unique` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`),
  KEY `blog_categories_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_user_id_foreign` (`user_id`),
  KEY `blog_comments_approved_by_foreign` (`approved_by`),
  KEY `blog_comments_post_id_status_index` (`post_id`,`status`),
  KEY `blog_comments_parent_id_index` (`parent_id`),
  CONSTRAINT `blog_comments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_post_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_post_tags_post_id_tag_id_unique` (`post_id`,`tag_id`),
  KEY `blog_post_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `blog_post_tags_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_post_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `related_demand_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` json DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` text COLLATE utf8mb4_unicode_ci,
  `views_count` int unsigned NOT NULL DEFAULT '0',
  `likes_count` int unsigned NOT NULL DEFAULT '0',
  `shares_count` int unsigned NOT NULL DEFAULT '0',
  `module_data` json DEFAULT NULL,
  `auto_generated_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_status_published_at_index` (`status`,`published_at`),
  KEY `blog_posts_category_id_status_index` (`category_id`,`status`),
  KEY `blog_posts_is_featured_published_at_index` (`is_featured`,`published_at`),
  KEY `blog_posts_author_id_index` (`author_id`),
  KEY `blog_posts_related_demand_id_index` (`related_demand_id`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6B7280',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `blog_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `viewed_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_views_user_id_foreign` (`user_id`),
  KEY `blog_views_post_id_viewed_at_index` (`post_id`,`viewed_at`),
  KEY `blog_views_ip_address_post_id_index` (`ip_address`,`post_id`),
  CONSTRAINT `blog_views_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `board_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio_short` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `mandate_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mandate_end` date DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_members_user_id_foreign` (`user_id`),
  CONSTRAINT `board_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bible_version_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_number` int NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testament` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'old',
  `total_chapters` int NOT NULL DEFAULT '0',
  `total_verses` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_bible_version_id_book_number_unique` (`bible_version_id`,`book_number`),
  KEY `books_bible_version_id_testament_index` (`bible_version_id`,`testament`),
  CONSTRAINT `books_bible_version_id_foreign` FOREIGN KEY (`bible_version_id`) REFERENCES `bible_versions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_event_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_event_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sale_starts_at` datetime DEFAULT NULL,
  `sale_ends_at` datetime DEFAULT NULL,
  `capacity` int unsigned DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_event_batches_event_id_sort_order_index` (`event_id`,`sort_order`),
  CONSTRAINT `calendar_event_batches_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_config` json DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `starts_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `all_day` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'evento',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `church_id` bigint unsigned DEFAULT NULL,
  `registration_open` tinyint(1) NOT NULL DEFAULT '0',
  `registration_deadline` datetime DEFAULT NULL,
  `form_fields` json DEFAULT NULL,
  `schedule` json DEFAULT NULL,
  `requires_council_approval` tinyint(1) NOT NULL DEFAULT '0',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_whatsapp` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_age` tinyint unsigned DEFAULT NULL,
  `max_age` tinyint unsigned DEFAULT NULL,
  `max_per_registration` tinyint unsigned DEFAULT '1',
  `metadata` json DEFAULT NULL,
  `preview_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_post_id` bigint unsigned DEFAULT NULL,
  `aviso_id` bigint unsigned DEFAULT NULL,
  `max_participants` int unsigned DEFAULT NULL,
  `registration_fee` decimal(15,2) DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendar_events_slug_unique` (`slug`),
  UNIQUE KEY `calendar_events_preview_token_unique` (`preview_token`),
  KEY `calendar_events_church_id_foreign` (`church_id`),
  KEY `calendar_events_created_by_foreign` (`created_by`),
  KEY `calendar_events_starts_at_visibility_index` (`starts_at`,`visibility`),
  KEY `calendar_events_blog_post_id_foreign` (`blog_post_id`),
  KEY `calendar_events_aviso_id_foreign` (`aviso_id`),
  KEY `calendar_events_status_index` (`status`),
  KEY `calendar_events_is_featured_index` (`is_featured`),
  CONSTRAINT `calendar_events_aviso_id_foreign` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `calendar_events_blog_post_id_foreign` FOREIGN KEY (`blog_post_id`) REFERENCES `blog_posts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `calendar_events_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `calendar_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_price_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_price_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `event_batch_id` bigint unsigned DEFAULT NULL,
  `rule_type` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` smallint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `config` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_price_rules_event_batch_id_foreign` (`event_batch_id`),
  KEY `calendar_price_rules_event_id_priority_index` (`event_id`,`priority`),
  CONSTRAINT `calendar_price_rules_event_batch_id_foreign` FOREIGN KEY (`event_batch_id`) REFERENCES `calendar_event_batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `calendar_price_rules_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `event_batch_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `payment_status` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_payment_id` bigint unsigned DEFAULT NULL,
  `discount_code` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_charged` decimal(15,2) DEFAULT NULL,
  `custom_responses` json DEFAULT NULL,
  `checkin_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendar_registrations_event_id_user_id_unique` (`event_id`,`user_id`),
  UNIQUE KEY `calendar_registrations_checkin_token_unique` (`checkin_token`),
  KEY `calendar_registrations_user_id_foreign` (`user_id`),
  KEY `calendar_registrations_gateway_payment_id_foreign` (`gateway_payment_id`),
  KEY `calendar_registrations_payment_status_index` (`payment_status`),
  KEY `calendar_registrations_event_batch_id_foreign` (`event_batch_id`),
  CONSTRAINT `calendar_registrations_event_batch_id_foreign` FOREIGN KEY (`event_batch_id`) REFERENCES `calendar_event_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `calendar_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `calendar_registrations_gateway_payment_id_foreign` FOREIGN KEY (`gateway_payment_id`) REFERENCES `gateway_payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `calendar_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `carousel_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carousel_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carousel_slides_is_active_order_index` (`is_active`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `chapter_number` int NOT NULL,
  `total_verses` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapters_book_id_chapter_number_unique` (`book_id`,`chapter_number`),
  KEY `chapters_book_id_index` (`book_id`),
  CONSTRAINT `chapters_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chat_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_configs_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chat_session_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `sender_type` enum('user','visitor','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visitor',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` enum('text','image','file','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_chat_session_id_index` (`chat_session_id`),
  KEY `chat_messages_user_id_index` (`user_id`),
  KEY `chat_messages_is_read_index` (`is_read`),
  KEY `chat_messages_created_at_index` (`created_at`),
  CONSTRAINT `chat_messages_chat_session_id_foreign` FOREIGN KEY (`chat_session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chat_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('public','internal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `visitor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_cpf` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `status` enum('waiting','active','closed','transferred') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `unread_count_user` int NOT NULL DEFAULT '0',
  `unread_count_visitor` int NOT NULL DEFAULT '0',
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_sessions_session_id_unique` (`session_id`),
  KEY `chat_sessions_user_id_foreign` (`user_id`),
  KEY `chat_sessions_status_index` (`status`),
  KEY `chat_sessions_assigned_to_index` (`assigned_to`),
  KEY `chat_sessions_type_index` (`type`),
  KEY `chat_sessions_visitor_cpf_index` (`visitor_cpf`),
  CONSTRAINT `chat_sessions_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chat_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `devotionals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devotionals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `devotional_date` date DEFAULT NULL,
  `scripture_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scripture_text` text COLLATE utf8mb4_unicode_ci,
  `bible_version_id` bigint unsigned DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `author_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `board_member_id` bigint unsigned DEFAULT NULL,
  `guest_author_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_author_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `devotionals_slug_unique` (`slug`),
  KEY `devotionals_user_id_foreign` (`user_id`),
  KEY `devotionals_board_member_id_foreign` (`board_member_id`),
  CONSTRAINT `devotionals_board_member_id_foreign` FOREIGN KEY (`board_member_id`) REFERENCES `board_members` (`id`) ON DELETE SET NULL,
  CONSTRAINT `devotionals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fin_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `direction` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fin_categories_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fin_expense_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_expense_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(15,2) NOT NULL,
  `justification` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `requested_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `paid_transaction_id` bigint unsigned DEFAULT NULL,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fin_expense_requests_requested_by_foreign` (`requested_by`),
  KEY `fin_expense_requests_approved_by_foreign` (`approved_by`),
  KEY `fin_expense_requests_paid_transaction_id_foreign` (`paid_transaction_id`),
  KEY `fin_expense_requests_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `fin_expense_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fin_expense_requests_paid_transaction_id_foreign` FOREIGN KEY (`paid_transaction_id`) REFERENCES `fin_transactions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fin_expense_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fin_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fin_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `occurred_on` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `direction` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `church_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `document_ref` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calendar_event_id` bigint unsigned DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fin_transactions_category_id_foreign` (`category_id`),
  KEY `fin_transactions_created_by_foreign` (`created_by`),
  KEY `fin_transactions_occurred_on_direction_index` (`occurred_on`,`direction`),
  KEY `fin_transactions_church_id_occurred_on_index` (`church_id`,`occurred_on`),
  KEY `fin_transactions_calendar_event_id_index` (`calendar_event_id`),
  KEY `fin_transactions_source_index` (`source`),
  CONSTRAINT `fin_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `fin_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fin_transactions_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fin_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gateway_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateway_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `gateway_payment_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_audit_logs_user_id_foreign` (`user_id`),
  KEY `gateway_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `gateway_audit_logs_gateway_payment_id_foreign` (`gateway_payment_id`),
  KEY `gateway_audit_logs_action_created_at_index` (`action`,`created_at`),
  CONSTRAINT `gateway_audit_logs_gateway_payment_id_foreign` FOREIGN KEY (`gateway_payment_id`) REFERENCES `gateway_payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gateway_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gateway_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateway_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_provider_account_id` bigint unsigned DEFAULT NULL,
  `driver` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BRL',
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payable_id` bigint unsigned DEFAULT NULL,
  `idempotency_key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkout_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_secret` text COLLATE utf8mb4_unicode_ci,
  `raw_last_payload` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `failure_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fin_transaction_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gateway_payments_uuid_unique` (`uuid`),
  UNIQUE KEY `gateway_payments_idempotency_key_unique` (`idempotency_key`),
  KEY `gateway_payments_gateway_provider_account_id_foreign` (`gateway_provider_account_id`),
  KEY `gateway_payments_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  KEY `gateway_payments_fin_transaction_id_foreign` (`fin_transaction_id`),
  KEY `gateway_payments_user_id_foreign` (`user_id`),
  KEY `gateway_payments_status_created_at_index` (`status`,`created_at`),
  KEY `gateway_payments_provider_reference_index` (`provider_reference`),
  KEY `gateway_payments_status_index` (`status`),
  CONSTRAINT `gateway_payments_fin_transaction_id_foreign` FOREIGN KEY (`fin_transaction_id`) REFERENCES `fin_transactions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gateway_payments_gateway_provider_account_id_foreign` FOREIGN KEY (`gateway_provider_account_id`) REFERENCES `gateway_provider_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gateway_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gateway_provider_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateway_provider_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `credentials` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_provider_accounts_driver_is_enabled_index` (`driver`,`is_enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gateway_webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateway_webhook_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gateway_provider_account_id` bigint unsigned DEFAULT NULL,
  `driver` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` json DEFAULT NULL,
  `signature_valid` tinyint(1) NOT NULL DEFAULT '0',
  `processing_status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `gateway_payment_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_webhook_events_gateway_provider_account_id_foreign` (`gateway_provider_account_id`),
  KEY `gateway_webhook_events_gateway_payment_id_foreign` (`gateway_payment_id`),
  KEY `gateway_webhook_events_driver_created_at_index` (`driver`,`created_at`),
  KEY `gateway_webhook_events_processing_status_index` (`processing_status`),
  CONSTRAINT `gateway_webhook_events_gateway_payment_id_foreign` FOREIGN KEY (`gateway_payment_id`) REFERENCES `gateway_payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gateway_webhook_events_gateway_provider_account_id_foreign` FOREIGN KEY (`gateway_provider_account_id`) REFERENCES `gateway_provider_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `homepage_contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `homepage_contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `homepage_newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `homepage_newsletter_subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `confirmation_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `homepage_newsletter_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `igrejas_church_change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `igrejas_church_change_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_id` bigint unsigned DEFAULT NULL,
  `type` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `payload` json NOT NULL,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `igrejas_church_change_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `igrejas_church_change_requests_status_created_at_index` (`status`,`created_at`),
  KEY `igrejas_church_change_requests_church_id_status_index` (`church_id`,`status`),
  KEY `igrejas_church_change_requests_submitted_by_status_index` (`submitted_by`,`status`),
  CONSTRAINT `igrejas_church_change_requests_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `igrejas_church_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `igrejas_church_change_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `igrejas_churches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `igrejas_churches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'church',
  `parent_church_id` bigint unsigned DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_path` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sector` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foundation_date` date DEFAULT NULL,
  `cooperation_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativa',
  `pastor_user_id` bigint unsigned DEFAULT NULL,
  `unijovem_leader_user_id` bigint unsigned DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asbaf_notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `joined_at` date DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `igrejas_churches_slug_unique` (`slug`),
  UNIQUE KEY `igrejas_churches_cnpj_unique` (`cnpj`),
  KEY `igrejas_churches_is_active_name_index` (`is_active`,`name`),
  KEY `igrejas_churches_pastor_user_id_foreign` (`pastor_user_id`),
  KEY `igrejas_churches_unijovem_leader_user_id_foreign` (`unijovem_leader_user_id`),
  KEY `igrejas_churches_sector_is_active_index` (`sector`,`is_active`),
  KEY `igrejas_churches_cooperation_status_index` (`cooperation_status`),
  KEY `igrejas_churches_parent_church_id_foreign` (`parent_church_id`),
  KEY `igrejas_churches_kind_index` (`kind`),
  CONSTRAINT `igrejas_churches_parent_church_id_foreign` FOREIGN KEY (`parent_church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `igrejas_churches_pastor_user_id_foreign` FOREIGN KEY (`pastor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `igrejas_churches_unijovem_leader_user_id_foreign` FOREIGN KEY (`unijovem_leader_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `panel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `action_url` text COLLATE utf8mb4_unicode_ci,
  `data` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `notifications_module_source_is_read_index` (`module_source`,`is_read`),
  KEY `notifications_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `notifications_created_at_index` (`created_at`),
  KEY `notifications_type_index` (`type`),
  KEY `notifications_panel_index` (`panel`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `offline_sync_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offline_sync_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `payload_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','processing','completed','failed','duplicate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `result_data` json DEFAULT NULL,
  `client_timestamp` timestamp NULL DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `device_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offline_sync_logs_client_uuid_unique` (`client_uuid`),
  KEY `offline_sync_logs_user_id_action_type_index` (`user_id`,`action_type`),
  KEY `offline_sync_logs_status_created_at_index` (`status`,`created_at`),
  KEY `offline_sync_logs_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `offline_sync_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profile_sensitive_data_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_sensitive_data_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `field` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_value` text COLLATE utf8mb4_unicode_ci,
  `requested_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by_user_id` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewer_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_sensitive_data_requests_reviewed_by_user_id_foreign` (`reviewed_by_user_id`),
  KEY `profile_sensitive_data_requests_status_created_at_index` (`status`,`created_at`),
  KEY `profile_sensitive_data_requests_user_id_field_status_index` (`user_id`,`field`,`status`),
  CONSTRAINT `profile_sensitive_data_requests_reviewed_by_user_id_foreign` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `profile_sensitive_data_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `endpoint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` text COLLATE utf8mb4_unicode_ci,
  `auth_token` text COLLATE utf8mb4_unicode_ci,
  `device_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_subscriptions_user_id_foreign` (`user_id`),
  CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_convocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_convocations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assembly_at` datetime NOT NULL,
  `notice_days` smallint unsigned NOT NULL DEFAULT '30',
  `body` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `meeting_id` bigint unsigned DEFAULT NULL,
  `created_by_id` bigint unsigned DEFAULT NULL,
  `approved_by_id` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secretaria_convocations_meeting_id_foreign` (`meeting_id`),
  KEY `secretaria_convocations_created_by_id_foreign` (`created_by_id`),
  KEY `secretaria_convocations_approved_by_id_foreign` (`approved_by_id`),
  KEY `secretaria_convocations_assembly_at_status_index` (`assembly_at`,`status`),
  CONSTRAINT `secretaria_convocations_approved_by_id_foreign` FOREIGN KEY (`approved_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_convocations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_convocations_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `secretaria_meetings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `visibility` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'directorate',
  `church_id` bigint unsigned DEFAULT NULL,
  `uploaded_by_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secretaria_documents_church_id_foreign` (`church_id`),
  KEY `secretaria_documents_uploaded_by_id_foreign` (`uploaded_by_id`),
  KEY `secretaria_documents_visibility_index` (`visibility`),
  CONSTRAINT `secretaria_documents_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_documents_uploaded_by_id_foreign` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_meetings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by_id` bigint unsigned DEFAULT NULL,
  `calendar_event_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secretaria_meetings_created_by_id_foreign` (`created_by_id`),
  KEY `secretaria_meetings_starts_at_status_index` (`starts_at`,`status`),
  KEY `secretaria_meetings_calendar_event_id_foreign` (`calendar_event_id`),
  CONSTRAINT `secretaria_meetings_calendar_event_id_foreign` FOREIGN KEY (`calendar_event_id`) REFERENCES `calendar_events` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_meetings_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_minute_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_minute_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `minute_id` bigint unsigned NOT NULL,
  `related_minute_id` bigint unsigned DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `kind` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'attachment',
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secretaria_minute_attachments_related_minute_id_foreign` (`related_minute_id`),
  KEY `secretaria_minute_attachments_minute_id_sort_order_index` (`minute_id`,`sort_order`),
  CONSTRAINT `secretaria_minute_attachments_minute_id_foreign` FOREIGN KEY (`minute_id`) REFERENCES `secretaria_minutes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `secretaria_minute_attachments_related_minute_id_foreign` FOREIGN KEY (`related_minute_id`) REFERENCES `secretaria_minutes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_minute_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_minute_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `secretaria_minute_templates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `secretaria_minutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretaria_minutes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meeting_id` bigint unsigned DEFAULT NULL,
  `church_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by_id` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_by_id` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secretaria_minutes_meeting_id_foreign` (`meeting_id`),
  KEY `secretaria_minutes_church_id_foreign` (`church_id`),
  KEY `secretaria_minutes_created_by_id_foreign` (`created_by_id`),
  KEY `secretaria_minutes_approved_by_id_foreign` (`approved_by_id`),
  KEY `secretaria_minutes_status_published_at_index` (`status`,`published_at`),
  CONSTRAINT `secretaria_minutes_approved_by_id_foreign` FOREIGN KEY (`approved_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_minutes_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_minutes_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `secretaria_minutes_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `secretaria_meetings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `sync_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_queue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sync_queue_user_id_foreign` (`user_id`),
  CONSTRAINT `sync_queue_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `system_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_configs_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talent_areas_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `calendar_event_id` bigint unsigned DEFAULT NULL,
  `role_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invited',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `talent_assignments_calendar_event_id_foreign` (`calendar_event_id`),
  KEY `talent_assignments_created_by_foreign` (`created_by`),
  KEY `talent_assignments_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `talent_assignments_calendar_event_id_foreign` FOREIGN KEY (`calendar_event_id`) REFERENCES `calendar_events` (`id`) ON DELETE SET NULL,
  CONSTRAINT `talent_assignments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `talent_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_profile_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_profile_area` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `talent_profile_id` bigint unsigned NOT NULL,
  `talent_area_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talent_profile_area_talent_profile_id_talent_area_id_unique` (`talent_profile_id`,`talent_area_id`),
  KEY `talent_profile_area_talent_area_id_foreign` (`talent_area_id`),
  CONSTRAINT `talent_profile_area_talent_area_id_foreign` FOREIGN KEY (`talent_area_id`) REFERENCES `talent_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `talent_profile_area_talent_profile_id_foreign` FOREIGN KEY (`talent_profile_id`) REFERENCES `talent_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_profile_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_profile_skill` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `talent_profile_id` bigint unsigned NOT NULL,
  `talent_skill_id` bigint unsigned NOT NULL,
  `level` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talent_profile_skill_talent_profile_id_talent_skill_id_unique` (`talent_profile_id`,`talent_skill_id`),
  KEY `talent_profile_skill_talent_skill_id_foreign` (`talent_skill_id`),
  CONSTRAINT `talent_profile_skill_talent_profile_id_foreign` FOREIGN KEY (`talent_profile_id`) REFERENCES `talent_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `talent_profile_skill_talent_skill_id_foreign` FOREIGN KEY (`talent_skill_id`) REFERENCES `talent_skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `availability_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_searchable` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talent_profiles_user_id_unique` (`user_id`),
  CONSTRAINT `talent_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `talent_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `talent_skills_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_churches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_churches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `church_id` bigint unsigned NOT NULL,
  `role_on_church` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_churches_user_id_church_id_unique` (`user_id`,`church_id`),
  KEY `user_churches_church_id_foreign` (`church_id`),
  CONSTRAINT `user_churches_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_churches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_profile_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profile_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profile_photos_user_id_is_active_index` (`user_id`,`is_active`),
  CONSTRAINT `user_profile_photos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_reading_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_reading_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `subscription_id` bigint unsigned NOT NULL,
  `plan_day_id` bigint unsigned NOT NULL,
  `day_number` int NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_reading_logs_subscription_id_plan_day_id_unique` (`subscription_id`,`plan_day_id`),
  KEY `user_reading_logs_user_id_foreign` (`user_id`),
  KEY `user_reading_logs_plan_day_id_foreign` (`plan_day_id`),
  CONSTRAINT `user_reading_logs_plan_day_id_foreign` FOREIGN KEY (`plan_day_id`) REFERENCES `bible_plan_days` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_reading_logs_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `bible_plan_subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_reading_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `church_phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_relationship` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `church_id` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_position_x` tinyint unsigned NOT NULL DEFAULT '50',
  `cover_position_y` tinyint unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_cpf_unique` (`cpf`),
  KEY `users_church_id_foreign` (`church_id`),
  CONSTRAINT `users_church_id_foreign` FOREIGN KEY (`church_id`) REFERENCES `igrejas_churches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `verses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `verse_number` int NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_verse_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verses_chapter_id_verse_number_unique` (`chapter_id`,`verse_number`),
  KEY `verses_chapter_id_index` (`chapter_id`),
  FULLTEXT KEY `verses_text_fulltext` (`text`),
  CONSTRAINT `verses_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2023_01_01_000000_create_app_versions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2023_01_01_000001_create_app_installations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_01_01_000002_create_push_subscriptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_01_01_000003_create_sync_queue_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_12_26_000001_create_blog_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_12_26_000002_create_blog_posts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_12_26_000003_create_blog_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_12_26_000004_create_blog_post_tags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2024_12_26_000005_create_blog_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_12_26_000006_create_blog_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_01_20_000000_create_notifications_table_complete',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_01_21_000001_create_bible_versions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_01_21_000002_create_books_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_01_21_000003_create_chapters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_01_21_000004_create_verses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_01_21_000005_create_bible_favorites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_01_28_000001_create_chat_configs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_01_28_000002_create_chat_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_01_28_000003_create_chat_messages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_01_28_000004_add_cpf_to_chat_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_01_29_000000_create_avisos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_11_18_021706_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_11_18_030221_create_system_configs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_11_18_030227_create_audit_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_11_18_030233_create_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_11_19_205911_add_module_fields_to_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_11_27_022404_create_carousel_slides_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_11_27_201121_update_carousel_slides_columns_to_text',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_11_27_211123_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_11_28_210000_create_offline_sync_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_12_03_145426_create_api_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_12_27_011916_add_metadata_to_blog_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_01_24_050041_create_bible_plans_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_01_24_053943_upgrade_bible_v2_schema',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_01_26_000001_remove_note_from_bible_favorites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_02_09_000001_standardize_system_columns',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_02_11_205323_add_panel_to_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_02_22_100000_add_audio_url_template_to_bible_versions',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_02_22_200000_create_bible_chapter_audio_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_03_05_100000_create_bible_metadata_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_03_05_100001_create_bible_plan_templates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_03_05_100002_create_user_reading_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2026_03_05_100003_add_cbav2026_fields_to_bible_plans',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_03_05_120000_create_bible_reading_audit_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_03_06_100000_create_bible_user_badges_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_03_06_100001_add_prayer_request_id_to_bible_plan_subscriptions',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_03_07_100000_create_bible_book_panoramas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_03_28_120000_drop_prayer_request_id_from_bible_plan_subscriptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_03_30_200000_create_bible_strongs_lexicon_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_03_30_200001_create_bible_interlinear_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_04_02_100000_create_bible_commentary_sources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_04_02_100001_create_bible_commentary_entries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_04_02_100002_create_bible_cross_references_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_04_02_100003_add_lexicon_original_columns_to_bible_strongs_lexicon_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_04_03_000000_add_color_to_bible_favorites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_04_03_120000_remove_pix_system_configs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_04_04_100000_create_board_members_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_04_04_100000_create_igrejas_churches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_04_04_100001_add_church_id_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_04_04_100010_create_secretaria_meetings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2026_04_04_100011_create_secretaria_minutes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_04_04_100012_create_secretaria_convocations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2026_04_04_100013_create_secretaria_documents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_04_04_120000_create_devotionals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_04_04_120000_create_homepage_contact_messages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_04_04_120000_migrate_jubaf_directorate_roles_estatuto',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2026_04_04_120000_rename_consulta_role_to_jovens',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_04_04_120001_create_homepage_newsletter_subscribers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_04_04_200000_migrate_campo_role_to_lider',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_04_05_100000_create_fin_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_04_05_100001_create_fin_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2026_04_05_100002_create_fin_expense_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2026_04_05_110000_create_calendar_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2026_04_05_110001_create_calendar_registrations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2026_04_05_120000_create_talent_skills_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2026_04_05_120001_create_talent_areas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2026_04_05_120002_create_talent_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2026_04_05_120003_create_talent_profile_skill_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2026_04_05_120004_create_talent_profile_area_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_04_05_120005_create_talent_assignments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2026_04_05_130000_create_user_churches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2026_04_05_130010_create_secretaria_minute_attachments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2026_04_05_130011_create_secretaria_minute_templates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2026_04_05_130012_add_calendar_event_id_to_secretaria_meetings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2026_04_06_100000_add_institutional_fields_to_igrejas_churches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2026_04_06_100001_create_igrejas_church_change_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2026_04_06_100003_add_church_ids_to_avisos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2026_04_07_120000_add_is_system_to_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2026_04_07_100000_add_kind_cnpj_media_parent_to_igrejas_churches_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2026_04_07_120000_add_emergency_contact_to_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2026_04_05_120000_create_profile_sensitive_data_requests_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2026_04_09_140000_create_user_profile_photos_and_cover',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2026_04_09_200000_add_cover_position_to_users_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2026_04_09_120000_add_attachments_and_og_to_blog_posts_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2026_04_09_120000_fin_transactions_scope_regional',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2026_04_11_150000_create_gateway_provider_accounts_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2026_04_11_150001_create_gateway_payments_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2026_04_11_150002_create_gateway_webhook_events_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2026_04_11_150003_create_gateway_audit_logs_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2026_04_11_150004_add_payment_columns_to_calendar_registrations_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2026_04_11_120000_financeiro_category_taxonomy_and_transaction_source',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2026_04_11_210000_add_institutional_columns_to_calendar_events_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2026_04_11_210001_create_calendar_event_batches_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2026_04_11_210002_create_calendar_price_rules_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2026_04_11_210003_extend_calendar_registrations_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2026_04_14_120000_add_author_metadata_to_blog_comments_table',11);
