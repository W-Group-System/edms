/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : edms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-07-28 09:09:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audits`
-- ----------------------------
DROP TABLE IF EXISTS `audits`;
CREATE TABLE `audits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of audits
-- ----------------------------
INSERT INTO `audits` VALUES ('1', null, null, 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$G9OPSMDrtn1IUK\\/eCM.uX.zUOsnBRJAn\\/ia82z3SfEShC8bAj5MKm\",\"remember_token\":\"xj3gkmWiy47jsqsgu24uSU859vJoEj3LIiJ5Q6sHRylxP4gXR6d6N15FcVya\"}', '{\"password\":\"$2y$10$5emgCCxK\\/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC\",\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', 'http://localhost/edms/public/password/reset?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-26 06:29:20', '2023-07-26 06:29:20');
INSERT INTO `audits` VALUES ('2', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', '{\"remember_token\":\"VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-27 04:48:22', '2023-07-27 04:48:22');

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('3', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('4', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('5', '2023_07_26_055300_create_audits_table', '1');

-- ----------------------------
-- Table structure for `password_resets`
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin', 'admin@admin.com', '0000-00-00 00:00:00', '$2y$10$5emgCCxK/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC', 'VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI', '0000-00-00 00:00:00', '2023-07-26 06:29:20');
