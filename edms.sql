/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : edms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-08-02 09:45:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `archives`
-- ----------------------------
DROP TABLE IF EXISTS `archives`;
CREATE TABLE `archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permit_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `accountable_person` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT '',
  `expiration_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of archives
-- ----------------------------
INSERT INTO `archives` VALUES ('1', '2', 'Permit', 'Permit', '1', '1', null, '/permits_attachments/1690883630_bg.jpg', '2024-04-30', '2', '2023-08-01 10:24:01', '2023-08-01 10:24:01', 'Permit');
INSERT INTO `archives` VALUES ('2', '1', 'Title', 'Sample Only', '1', '1', null, '/permits_attachments/1690881906_bg.jpg', '2023-08-31', null, '2023-08-01 10:24:19', '2023-08-01 10:24:19', 'License');

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of audits
-- ----------------------------
INSERT INTO `audits` VALUES ('1', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$G9OPSMDrtn1IUK\\/eCM.uX.zUOsnBRJAn\\/ia82z3SfEShC8bAj5MKm\",\"remember_token\":\"xj3gkmWiy47jsqsgu24uSU859vJoEj3LIiJ5Q6sHRylxP4gXR6d6N15FcVya\"}', '{\"password\":\"$2y$10$5emgCCxK\\/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC\",\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', 'http://localhost/edms/public/password/reset?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-26 06:29:20', '2023-07-26 06:29:20');
INSERT INTO `audits` VALUES ('2', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"9plJCj2IvvNQzyENv9uVXmEfixMkkRiCgOPAg0CYTryZQ198RlXAgZkidmQ4\"}', '{\"remember_token\":\"VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-27 04:48:22', '2023-07-27 04:48:22');
INSERT INTO `audits` VALUES ('3', 'App\\User', '1', 'created', 'App\\User', '2', '[]', '{\"name\":\"Renz Christian Cabato\",\"email\":\"cabato.renz.renz@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Administrator\",\"password\":\"$2y$10$N63xmV\\/1JnPBML7c2trO6.a7QPd5CzfqoDi.LXDQCqXq56C1OC8za\",\"id\":2}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-30 05:27:56', '2023-07-30 05:27:56');
INSERT INTO `audits` VALUES ('4', 'App\\User', '1', 'created', 'App\\User', '3', '[]', '{\"name\":\"Filomena Agnes Cabulong\",\"email\":\"f.cabulong@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Business Process Manager\",\"password\":\"$2y$10$eB.PWNqyUG7jDZjsVPyjxO7DMskPYzi78CESFeaADl\\/XMKGtqCScG\",\"id\":3}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-07-30 05:44:22', '2023-07-30 05:44:22');
INSERT INTO `audits` VALUES ('5', 'App\\User', '1', 'created', 'App\\User', '4', '[]', '{\"name\":\"Department head\",\"email\":\"dep_head@gmail.com\",\"company_id\":\"1\",\"department_id\":\"1\",\"role\":\"Department Head\",\"password\":\"$2y$10$B6\\/3as\\/8CO5gyX6UUecCXOnimsDmKEwBPzwvQhDsygg.PT4Ezle\\/2\",\"id\":4}', 'http://localhost/edms/public/new-account?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:36:01', '2023-08-01 05:36:01');
INSERT INTO `audits` VALUES ('6', 'App\\User', '1', 'created', 'App\\Department', '2', '[]', '{\"code\":\"0001\",\"name\":\"crm-vortex\",\"user_id\":\"4\",\"id\":2}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:41:14', '2023-08-01 05:41:14');
INSERT INTO `audits` VALUES ('7', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:50:45', '2023-08-01 05:50:45');
INSERT INTO `audits` VALUES ('8', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:52:46', '2023-08-01 05:52:46');
INSERT INTO `audits` VALUES ('9', 'App\\User', '1', 'created', 'App\\Department', '3', '[]', '{\"code\":\"SAMPLE\",\"name\":\"SAMPLE DEPARTMENT\",\"user_id\":\"4\",\"id\":3}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:55:26', '2023-08-01 05:55:26');
INSERT INTO `audits` VALUES ('10', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:57:40', '2023-08-01 05:57:40');
INSERT INTO `audits` VALUES ('11', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 05:57:43', '2023-08-01 05:57:43');
INSERT INTO `audits` VALUES ('12', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:02:36', '2023-08-01 06:02:36');
INSERT INTO `audits` VALUES ('13', 'App\\User', '1', 'updated', 'App\\Department', '2', '{\"status\":\"deactivated\"}', '{\"status\":null}', 'http://localhost/edms/public/activate-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:03:03', '2023-08-01 06:03:03');
INSERT INTO `audits` VALUES ('14', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$5emgCCxK\\/9TF.iDUYP4KyOGqlIA2U31NH9cX3jPLmWBnA0jMJqcHC\"}', '{\"password\":\"$2y$10$z6sSNR\\/Nvz33kfvKWbJlq.Ce\\/TKtEEca3AWj.GeLloUXv7Z9fZRhy\"}', 'http://localhost/edms/public/change-password/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:03:58', '2023-08-01 06:03:58');
INSERT INTO `audits` VALUES ('15', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"password\":\"$2y$10$z6sSNR\\/Nvz33kfvKWbJlq.Ce\\/TKtEEca3AWj.GeLloUXv7Z9fZRhy\"}', '{\"password\":\"$2y$10$PbGAH7o7P6JM9V22Erv0NuORq18yRmz58IL7x9YdFKauS6UmBes7W\"}', 'http://localhost/edms/public/change-password/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:04:07', '2023-08-01 06:04:07');
INSERT INTO `audits` VALUES ('16', 'App\\User', '1', 'updated', 'App\\User', '3', '{\"password\":\"$2y$10$eB.PWNqyUG7jDZjsVPyjxO7DMskPYzi78CESFeaADl\\/XMKGtqCScG\"}', '{\"password\":\"$2y$10$kpSHGrSFdn4f3F.Qs4d\\/5u5XV0Y8UaDz4KQGkfq251mbF8M0k4Z82\"}', 'http://localhost/edms/public/change-password/3?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:04:16', '2023-08-01 06:04:16');
INSERT INTO `audits` VALUES ('17', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"name\":\"Admin\"}', '{\"name\":\"Admin1\"}', 'http://localhost/edms/public/edit-user/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:08:41', '2023-08-01 06:08:41');
INSERT INTO `audits` VALUES ('18', 'App\\User', '1', 'updated', 'App\\User', '4', '{\"password\":\"$2y$10$B6\\/3as\\/8CO5gyX6UUecCXOnimsDmKEwBPzwvQhDsygg.PT4Ezle\\/2\",\"status\":null}', '{\"password\":\"\",\"status\":1}', 'http://localhost/edms/public/deactivate-user?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:13:00', '2023-08-01 06:13:00');
INSERT INTO `audits` VALUES ('19', 'App\\User', '1', 'updated', 'App\\User', '4', '{\"status\":\"1\"}', '{\"status\":\"\"}', 'http://localhost/edms/public/activate-user?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:13:03', '2023-08-01 06:13:03');
INSERT INTO `audits` VALUES ('20', 'App\\User', '1', 'created', 'App\\Company', '2', '[]', '{\"code\":\"sasd\",\"name\":\"123\",\"id\":2}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:59:10', '2023-08-01 06:59:10');
INSERT INTO `audits` VALUES ('21', 'App\\User', '1', 'created', 'App\\Company', '3', '[]', '{\"code\":\"sasd\",\"name\":\"123\",\"id\":3}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:59:21', '2023-08-01 06:59:21');
INSERT INTO `audits` VALUES ('22', 'App\\User', '1', 'created', 'App\\Company', '4', '[]', '{\"code\":\"0002\",\"name\":\"asda 123\",\"id\":4}', 'http://localhost/edms/public/new-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 06:59:44', '2023-08-01 06:59:44');
INSERT INTO `audits` VALUES ('23', 'App\\User', '1', 'updated', 'App\\User', '1', '{\"remember_token\":\"VD6jJAgf5RcEAmjkL4pAgPUdQ9WNwYpFu09se0jYKV4lJr93DR8pyDkBKHuI\"}', '{\"remember_token\":\"xfr6WFmk8Pp4ghqNqZDlu8ZtVnJKdl76IFCqzFObFCVg396n2A8cvjOJfH3E\"}', 'http://localhost/edms/public/logout?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 07:18:06', '2023-08-01 07:18:06');
INSERT INTO `audits` VALUES ('24', 'App\\User', '2', 'updated', 'App\\Company', '2', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 08:07:15', '2023-08-01 08:07:15');
INSERT INTO `audits` VALUES ('25', 'App\\User', '2', 'updated', 'App\\Company', '3', '{\"status\":null}', '{\"status\":\"deactivated\"}', 'http://localhost/edms/public/deactivate-company?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 08:07:18', '2023-08-01 08:07:18');
INSERT INTO `audits` VALUES ('26', 'App\\User', '2', 'created', 'App\\Permit', '1', '[]', '{\"title\":\"Title\",\"description\":\"Sample Only\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"License\",\"file\":\"\\/permits\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"id\":1}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 09:25:06', '2023-08-01 09:25:06');
INSERT INTO `audits` VALUES ('27', 'App\\User', '2', 'created', 'App\\Permit', '2', '[]', '{\"title\":\"Permit\",\"description\":\"Permit\",\"company_id\":\"1\",\"department_id\":\"1\",\"type\":\"Permit\",\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\",\"user_id\":2,\"id\":2}', 'http://localhost/edms/public/new-permit?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 09:53:50', '2023-08-01 09:53:50');
INSERT INTO `audits` VALUES ('28', 'App\\User', '2', 'created', 'App\\Archive', '1', '[]', '{\"permit_id\":\"2\",\"title\":\"Permit\",\"description\":\"Permit\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\",\"user_id\":2,\"type\":\"Permit\",\"id\":1}', 'http://localhost/edms/public/upload-permit/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:24:01', '2023-08-01 10:24:01');
INSERT INTO `audits` VALUES ('29', 'App\\User', '2', 'updated', 'App\\Permit', '2', '{\"file\":\"\\/permits_attachments\\/1690883630_bg.jpg\",\"expiration_date\":\"2024-04-30\"}', '{\"file\":\"\\/permits_attachments\\/1690885441_bg.jpg\",\"expiration_date\":\"2024-04-24\"}', 'http://localhost/edms/public/upload-permit/2?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:24:01', '2023-08-01 10:24:01');
INSERT INTO `audits` VALUES ('30', 'App\\User', '2', 'created', 'App\\Archive', '2', '[]', '{\"permit_id\":\"1\",\"title\":\"Title\",\"description\":\"Sample Only\",\"company_id\":1,\"department_id\":1,\"file\":\"\\/permits_attachments\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"user_id\":null,\"type\":\"License\",\"id\":2}', 'http://localhost/edms/public/upload-permit/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:24:19', '2023-08-01 10:24:19');
INSERT INTO `audits` VALUES ('31', 'App\\User', '2', 'updated', 'App\\Permit', '1', '{\"file\":\"\\/permits_attachments\\/1690881906_bg.jpg\",\"expiration_date\":\"2023-08-31\",\"user_id\":null}', '{\"file\":\"\\/permits_attachments\\/1690885459_bg.jpg\",\"expiration_date\":\"2024-06-12\",\"user_id\":2}', 'http://localhost/edms/public/upload-permit/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:24:19', '2023-08-01 10:24:19');
INSERT INTO `audits` VALUES ('32', 'App\\User', '2', 'created', 'App\\Department', '4', '[]', '{\"code\":\"HRD\",\"name\":\"Human Resource Department\",\"user_id\":\"4\",\"permit_accountable\":\"3\",\"id\":4}', 'http://localhost/edms/public/new-department?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:34:13', '2023-08-01 10:34:13');
INSERT INTO `audits` VALUES ('33', 'App\\User', '2', 'updated', 'App\\Permit', '1', '{\"department_id\":1}', '{\"department_id\":\"4\"}', 'http://localhost/edms/public/change-department/1?', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', null, '2023-08-01 10:36:07', '2023-08-01 10:36:07');

-- ----------------------------
-- Table structure for `companies`
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES ('1', 'W GROUP INC', 'WGI', 'company_images/wgroup.png', 'company_images/wgroup_icon.png', '2023-07-21 09:05:47', '2023-07-21 09:05:47', null);
INSERT INTO `companies` VALUES ('2', '123', 'sasd', null, null, '2023-08-01 06:59:10', '2023-08-01 08:07:15', 'deactivated');
INSERT INTO `companies` VALUES ('3', '123', 'sasd', null, null, '2023-08-01 06:59:21', '2023-08-01 08:07:18', 'deactivated');
INSERT INTO `companies` VALUES ('4', 'asda 123', '0002', null, null, '2023-08-01 06:59:44', '2023-08-01 06:59:44', null);

-- ----------------------------
-- Table structure for `departments`
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `permit_accountable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES ('1', 'ITD', 'Information Technology Department', null, null, null, '1', '1');
INSERT INTO `departments` VALUES ('2', '0001', 'crm-vortex', '2023-08-01 05:41:14', '2023-08-01 06:03:03', null, '4', null);
INSERT INTO `departments` VALUES ('3', 'SAMPLE', 'SAMPLE DEPARTMENT', '2023-08-01 05:55:26', '2023-08-01 05:55:26', null, '4', null);
INSERT INTO `departments` VALUES ('4', 'HRD', 'Human Resource Department', '2023-08-01 10:34:13', '2023-08-01 10:34:13', null, '4', '3');

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
INSERT INTO `password_resets` VALUES ('cabato.renz.renz@gmail.com', '$2y$10$vuHdKsP9HMPxCsv9B5xltOZmaSwxc/wiepDVwISeS53i4gx/1nJyi', '2023-08-01 07:19:22');

-- ----------------------------
-- Table structure for `permits`
-- ----------------------------
DROP TABLE IF EXISTS `permits`;
CREATE TABLE `permits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `accountable_person` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of permits
-- ----------------------------
INSERT INTO `permits` VALUES ('1', 'Title', 'Sample Only', '1', '4', null, '/permits_attachments/1690885459_bg.jpg', '2024-06-12', '2', '2023-08-01 09:25:06', '2023-08-01 10:36:07', 'License');
INSERT INTO `permits` VALUES ('2', 'Permit', 'Permit', '1', '1', null, '/permits_attachments/1690885441_bg.jpg', '2024-04-24', '2', '2023-08-01 09:53:50', '2023-08-01 10:24:01', 'Permit');

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
  `department_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin1', 'admin@admin.com', '0000-00-00 00:00:00', '$2y$10$PbGAH7o7P6JM9V22Erv0NuORq18yRmz58IL7x9YdFKauS6UmBes7W', 'xfr6WFmk8Pp4ghqNqZDlu8ZtVnJKdl76IFCqzFObFCVg396n2A8cvjOJfH3E', '0000-00-00 00:00:00', '2023-08-01 06:08:41', '1', '1', 'Administrator', null);
INSERT INTO `users` VALUES ('2', 'Renz Christian Cabato', 'cabato.renz.renz@gmail.com', null, '$2y$10$N63xmV/1JnPBML7c2trO6.a7QPd5CzfqoDi.LXDQCqXq56C1OC8za', null, '2023-07-30 05:27:56', '2023-07-30 05:27:56', '1', '1', 'Administrator', null);
INSERT INTO `users` VALUES ('3', 'Filomena Agnes Cabulong', 'f.cabulong@gmail.com', null, '$2y$10$kpSHGrSFdn4f3F.Qs4d/5u5XV0Y8UaDz4KQGkfq251mbF8M0k4Z82', null, '2023-07-30 05:44:22', '2023-08-01 06:04:16', '1', '1', 'Business Process Manager', null);
INSERT INTO `users` VALUES ('4', 'Department head', 'dep_head@gmail.com', null, '', null, '2023-08-01 05:36:01', '2023-08-01 06:13:03', '1', '1', 'Department Head', null);
