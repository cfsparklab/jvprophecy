-- Quick fix: Create security_logs table
-- Run this SQL directly in your production database (phpMyAdmin, MySQL Workbench, etc.)

CREATE TABLE IF NOT EXISTS `security_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_type` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'low',
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_logs_user_id_foreign` (`user_id`),
  KEY `security_logs_event_type_index` (`event_type`),
  KEY `security_logs_resource_type_index` (`resource_type`),
  KEY `security_logs_ip_address_index` (`ip_address`),
  KEY `security_logs_event_time_index` (`event_time`),
  KEY `security_logs_severity_index` (`severity`),
  CONSTRAINT `security_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

