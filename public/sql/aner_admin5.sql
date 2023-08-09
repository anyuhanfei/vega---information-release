/*
 Navicat MySQL Data Transfer

 Source Server         : 本地-aner_admin5
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : localhost:3306
 Source Schema         : aner_admin5

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 06/06/2023 09:55:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_extension_histories
-- ----------------------------
DROP TABLE IF EXISTS `admin_extension_histories`;
CREATE TABLE `admin_extension_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1',
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_extension_histories_name_index` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_extension_histories
-- ----------------------------
BEGIN;
INSERT INTO `admin_extension_histories` VALUES (1, 'jatdung.media-manager', 1, '1.0.0', 'Initialize extension.', '2022-05-31 03:20:20', '2022-05-31 03:20:20');
INSERT INTO `admin_extension_histories` VALUES (2, 'jatdung.media-manager', 1, '1.0.2-dev', '使用 Storage 成员方法替代 file 函数', '2022-05-31 03:20:20', '2022-05-31 03:20:20');
INSERT INTO `admin_extension_histories` VALUES (3, 'jatdung.media-manager', 1, '1.0.3', '增加多 disk 支持', '2022-05-31 03:20:20', '2022-05-31 03:20:20');
INSERT INTO `admin_extension_histories` VALUES (4, 'lty5240.dcat-easy-sku', 1, '1.0.0', 'Initialize extension.', '2022-06-06 08:47:00', '2022-06-06 08:47:00');
INSERT INTO `admin_extension_histories` VALUES (5, 'lty5240.dcat-easy-sku', 1, '1.0.1', '修复attrs超过两个会不显示的问题', '2022-06-06 08:47:00', '2022-06-06 08:47:00');
INSERT INTO `admin_extension_histories` VALUES (6, 'lty5240.dcat-easy-sku', 1, '1.0.1', '更新了上传图片样式', '2022-06-06 08:47:00', '2022-06-06 08:47:00');
INSERT INTO `admin_extension_histories` VALUES (7, 'lty5240.dcat-easy-sku', 1, '1.1.0', '新增快速批量插入输入框的数值', '2022-06-06 08:47:00', '2022-06-06 08:47:00');
COMMIT;

-- ----------------------------
-- Table structure for admin_extensions
-- ----------------------------
DROP TABLE IF EXISTS `admin_extensions`;
CREATE TABLE `admin_extensions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_enabled` tinyint NOT NULL DEFAULT '0',
  `options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_extensions_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_extensions
-- ----------------------------
BEGIN;
INSERT INTO `admin_extensions` VALUES (1, 'jatdung.media-manager', '1.0.3', 0, NULL, '2022-05-31 03:20:20', '2022-06-01 08:02:36');
INSERT INTO `admin_extensions` VALUES (2, 'lty5240.dcat-easy-sku', '1.1.0', 1, NULL, '2022-06-06 08:47:00', '2022-06-06 08:50:01');
COMMIT;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `show` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_menu` VALUES (1, 0, 1, '主页', 'feather icon-bar-chart-2', '/', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:36:32');
INSERT INTO `admin_menu` VALUES (2, 0, 2, '系统管理', 'feather icon-settings', NULL, '', 1, '2022-05-31 01:15:50', '2022-05-31 01:38:15');
INSERT INTO `admin_menu` VALUES (3, 2, 3, '管理员管理', NULL, 'auth/users', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:38:30');
INSERT INTO `admin_menu` VALUES (4, 2, 4, '角色管理', NULL, 'auth/roles', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:38:39');
INSERT INTO `admin_menu` VALUES (5, 2, 5, '权限管理', NULL, 'auth/permissions', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:38:53');
INSERT INTO `admin_menu` VALUES (6, 2, 6, '目录管理', NULL, 'auth/menu', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:39:00');
INSERT INTO `admin_menu` VALUES (7, 2, 7, '扩展管理', NULL, 'auth/extensions', '', 1, '2022-05-31 01:15:50', '2022-05-31 01:39:12');
INSERT INTO `admin_menu` VALUES (8, 19, 16, '会员列表', NULL, '/users', '', 1, '2022-05-31 02:25:03', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (9, 21, 19, '文章分类', NULL, '/article/category', '', 1, '2022-05-31 03:02:21', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (10, 0, 8, 'Media Manager', 'fa-folder-open', 'media', 'jatdung.media-manager', 0, '2022-05-31 03:20:20', '2022-06-06 03:06:14');
INSERT INTO `admin_menu` VALUES (11, 21, 20, '文章标签', NULL, '/article/tag', '', 1, '2022-05-31 06:14:30', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (12, 21, 21, '文章管理', NULL, '/article', '', 1, '2022-05-31 06:22:57', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (13, 20, 10, '轮播图管理', NULL, '/sys/banner', '', 1, '2022-05-31 07:53:38', '2022-06-01 09:50:26');
INSERT INTO `admin_menu` VALUES (14, 20, 11, '广告管理', NULL, '/sys/sysad', '', 1, '2022-05-31 08:05:28', '2023-02-25 15:08:34');
INSERT INTO `admin_menu` VALUES (15, 20, 13, '系统设置', NULL, '/sys/setting', '', 1, '2022-05-31 09:44:41', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (16, 19, 17, '会员资产记录', NULL, '/log/userfund', '', 1, '2022-06-01 06:13:12', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (17, 20, 12, '公告管理', NULL, '/sys/notice', '', 1, '2022-06-01 06:36:44', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (18, 20, 14, '系统消息', NULL, '/log/sysmessage', '', 1, '2022-06-01 07:30:41', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (19, 0, 15, '会员管理', 'fa-address-book', NULL, '', 1, '2022-06-01 07:58:58', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (20, 0, 9, '应用管理', 'fa-cogs', NULL, '', 1, '2022-06-01 08:00:22', '2022-06-01 09:50:26');
INSERT INTO `admin_menu` VALUES (21, 0, 18, '文章管理', 'fa-paste', NULL, '', 1, '2022-06-01 08:01:58', '2023-02-27 15:53:46');
INSERT INTO `admin_menu` VALUES (22, 0, 22, '项目配置', 'fa-cogs', NULL, '', 1, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_menu` VALUES (23, 22, 23, '自定义配置测试', NULL, 'setting/test', '', 1, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_menu` VALUES (24, 20, 24, '图床管理', 'fa-image', 'sys/img', '', 1, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
COMMIT;

-- ----------------------------
-- Table structure for admin_permission_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission_menu`;
CREATE TABLE `admin_permission_menu` (
  `permission_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_permission_menu_permission_id_menu_id_unique` (`permission_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_permission_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_permission_menu` VALUES (1, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (1, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_permission_menu` VALUES (1, 12, '2022-05-31 06:22:57', '2022-05-31 06:22:57');
INSERT INTO `admin_permission_menu` VALUES (1, 13, '2022-05-31 07:53:38', '2022-05-31 07:53:38');
INSERT INTO `admin_permission_menu` VALUES (1, 14, '2022-05-31 08:05:28', '2022-05-31 08:05:28');
INSERT INTO `admin_permission_menu` VALUES (1, 15, '2022-05-31 09:44:41', '2022-05-31 09:44:41');
INSERT INTO `admin_permission_menu` VALUES (1, 16, '2022-06-01 06:13:12', '2022-06-01 06:13:12');
INSERT INTO `admin_permission_menu` VALUES (1, 17, '2022-06-01 06:36:44', '2022-06-01 06:36:44');
INSERT INTO `admin_permission_menu` VALUES (1, 18, '2022-06-01 07:30:41', '2022-06-01 07:30:41');
INSERT INTO `admin_permission_menu` VALUES (1, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (1, 20, '2022-06-01 08:00:22', '2022-06-01 08:00:22');
INSERT INTO `admin_permission_menu` VALUES (1, 21, '2022-06-01 08:01:58', '2022-06-01 08:01:58');
INSERT INTO `admin_permission_menu` VALUES (1, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (1, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (1, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (2, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (2, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (2, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (2, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (2, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (3, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (3, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_permission_menu` VALUES (3, 12, '2022-05-31 06:22:57', '2022-05-31 06:22:57');
INSERT INTO `admin_permission_menu` VALUES (3, 13, '2022-05-31 07:53:38', '2022-05-31 07:53:38');
INSERT INTO `admin_permission_menu` VALUES (3, 14, '2022-05-31 08:05:28', '2022-05-31 08:05:28');
INSERT INTO `admin_permission_menu` VALUES (3, 15, '2022-05-31 09:44:41', '2022-05-31 09:44:41');
INSERT INTO `admin_permission_menu` VALUES (3, 16, '2022-06-01 06:13:12', '2022-06-01 06:13:12');
INSERT INTO `admin_permission_menu` VALUES (3, 17, '2022-06-01 06:36:44', '2022-06-01 06:36:44');
INSERT INTO `admin_permission_menu` VALUES (3, 18, '2022-06-01 07:30:41', '2022-06-01 07:30:41');
INSERT INTO `admin_permission_menu` VALUES (3, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (3, 20, '2022-06-01 08:00:22', '2022-06-01 08:00:22');
INSERT INTO `admin_permission_menu` VALUES (3, 21, '2022-06-01 08:01:58', '2022-06-01 08:01:58');
INSERT INTO `admin_permission_menu` VALUES (3, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (3, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (3, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (4, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (4, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_permission_menu` VALUES (4, 12, '2022-05-31 06:22:57', '2022-05-31 06:22:57');
INSERT INTO `admin_permission_menu` VALUES (4, 13, '2022-05-31 07:53:38', '2022-05-31 07:53:38');
INSERT INTO `admin_permission_menu` VALUES (4, 14, '2022-05-31 08:05:28', '2022-05-31 08:05:28');
INSERT INTO `admin_permission_menu` VALUES (4, 15, '2022-05-31 09:44:41', '2022-05-31 09:44:41');
INSERT INTO `admin_permission_menu` VALUES (4, 16, '2022-06-01 06:13:12', '2022-06-01 06:13:12');
INSERT INTO `admin_permission_menu` VALUES (4, 17, '2022-06-01 06:36:44', '2022-06-01 06:36:44');
INSERT INTO `admin_permission_menu` VALUES (4, 18, '2022-06-01 07:30:41', '2022-06-01 07:30:41');
INSERT INTO `admin_permission_menu` VALUES (4, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (4, 20, '2022-06-01 08:00:22', '2022-06-01 08:00:22');
INSERT INTO `admin_permission_menu` VALUES (4, 21, '2022-06-01 08:01:58', '2022-06-01 08:01:58');
INSERT INTO `admin_permission_menu` VALUES (4, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (4, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (4, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (5, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (5, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_permission_menu` VALUES (5, 12, '2022-05-31 06:22:57', '2022-05-31 06:22:57');
INSERT INTO `admin_permission_menu` VALUES (5, 13, '2022-05-31 07:53:38', '2022-05-31 07:53:38');
INSERT INTO `admin_permission_menu` VALUES (5, 14, '2022-05-31 08:05:28', '2022-05-31 08:05:28');
INSERT INTO `admin_permission_menu` VALUES (5, 15, '2022-05-31 09:44:41', '2022-05-31 09:44:41');
INSERT INTO `admin_permission_menu` VALUES (5, 16, '2022-06-01 06:13:12', '2022-06-01 06:13:12');
INSERT INTO `admin_permission_menu` VALUES (5, 17, '2022-06-01 06:36:44', '2022-06-01 06:36:44');
INSERT INTO `admin_permission_menu` VALUES (5, 18, '2022-06-01 07:30:41', '2022-06-01 07:30:41');
INSERT INTO `admin_permission_menu` VALUES (5, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (5, 20, '2022-06-01 08:00:22', '2022-06-01 08:00:22');
INSERT INTO `admin_permission_menu` VALUES (5, 21, '2022-06-01 08:01:58', '2022-06-01 08:01:58');
INSERT INTO `admin_permission_menu` VALUES (5, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (5, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (5, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (6, 8, '2022-05-31 02:25:03', '2022-05-31 02:25:03');
INSERT INTO `admin_permission_menu` VALUES (6, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_permission_menu` VALUES (6, 12, '2022-05-31 06:22:57', '2022-05-31 06:22:57');
INSERT INTO `admin_permission_menu` VALUES (6, 13, '2022-05-31 07:53:38', '2022-05-31 07:53:38');
INSERT INTO `admin_permission_menu` VALUES (6, 14, '2022-05-31 08:05:28', '2022-05-31 08:05:28');
INSERT INTO `admin_permission_menu` VALUES (6, 15, '2022-05-31 09:44:41', '2022-05-31 09:44:41');
INSERT INTO `admin_permission_menu` VALUES (6, 16, '2022-06-01 06:13:12', '2022-06-01 06:13:12');
INSERT INTO `admin_permission_menu` VALUES (6, 17, '2022-06-01 06:36:44', '2022-06-01 06:36:44');
INSERT INTO `admin_permission_menu` VALUES (6, 18, '2022-06-01 07:30:41', '2022-06-01 07:30:41');
INSERT INTO `admin_permission_menu` VALUES (6, 19, '2022-06-01 07:58:58', '2022-06-01 07:58:58');
INSERT INTO `admin_permission_menu` VALUES (6, 20, '2022-06-01 08:00:22', '2022-06-01 08:00:22');
INSERT INTO `admin_permission_menu` VALUES (6, 21, '2022-06-01 08:01:58', '2022-06-01 08:01:58');
INSERT INTO `admin_permission_menu` VALUES (6, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (6, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (6, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (7, 20, '2022-06-06 03:10:21', '2022-06-06 03:10:21');
INSERT INTO `admin_permission_menu` VALUES (7, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (7, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (7, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (8, 13, '2022-06-06 03:11:02', '2022-06-06 03:11:02');
INSERT INTO `admin_permission_menu` VALUES (8, 20, '2022-06-06 03:11:02', '2022-06-06 03:11:02');
INSERT INTO `admin_permission_menu` VALUES (8, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (8, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (8, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (9, 14, '2022-06-06 03:11:26', '2022-06-06 03:11:26');
INSERT INTO `admin_permission_menu` VALUES (9, 20, '2022-06-06 03:11:26', '2022-06-06 03:11:26');
INSERT INTO `admin_permission_menu` VALUES (9, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (9, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (9, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (10, 15, '2022-06-06 03:12:00', '2022-06-06 03:12:00');
INSERT INTO `admin_permission_menu` VALUES (10, 20, '2022-06-06 03:12:00', '2022-06-06 03:12:00');
INSERT INTO `admin_permission_menu` VALUES (10, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (10, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (10, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (11, 18, '2022-06-06 03:12:33', '2022-06-06 03:12:33');
INSERT INTO `admin_permission_menu` VALUES (11, 20, '2022-06-06 03:12:33', '2022-06-06 03:12:33');
INSERT INTO `admin_permission_menu` VALUES (11, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (11, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (11, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (12, 19, '2022-06-06 03:15:16', '2022-06-06 03:15:16');
INSERT INTO `admin_permission_menu` VALUES (12, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (12, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (12, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (13, 8, '2022-06-06 03:17:06', '2022-06-06 03:17:06');
INSERT INTO `admin_permission_menu` VALUES (13, 19, '2022-06-06 03:17:06', '2022-06-06 03:17:06');
INSERT INTO `admin_permission_menu` VALUES (13, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (13, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (13, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (14, 16, '2022-06-06 03:17:31', '2022-06-06 03:17:31');
INSERT INTO `admin_permission_menu` VALUES (14, 19, '2022-06-06 03:17:31', '2022-06-06 03:17:31');
INSERT INTO `admin_permission_menu` VALUES (14, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (14, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (14, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (15, 21, '2022-06-06 03:17:49', '2022-06-06 03:17:49');
INSERT INTO `admin_permission_menu` VALUES (15, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (15, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (15, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (16, 9, '2022-06-06 03:18:19', '2022-06-06 03:18:19');
INSERT INTO `admin_permission_menu` VALUES (16, 21, '2022-06-06 03:18:19', '2022-06-06 03:18:19');
INSERT INTO `admin_permission_menu` VALUES (16, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (16, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (16, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (17, 11, '2022-06-06 03:18:41', '2022-06-06 03:18:41');
INSERT INTO `admin_permission_menu` VALUES (17, 21, '2022-06-06 03:18:41', '2022-06-06 03:18:41');
INSERT INTO `admin_permission_menu` VALUES (17, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (17, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (17, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (18, 12, '2022-06-06 03:19:45', '2022-06-06 03:19:45');
INSERT INTO `admin_permission_menu` VALUES (18, 21, '2022-06-06 03:19:45', '2022-06-06 03:19:45');
INSERT INTO `admin_permission_menu` VALUES (18, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (18, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (18, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_permission_menu` VALUES (19, 17, '2022-06-06 03:20:11', '2022-06-06 03:20:11');
INSERT INTO `admin_permission_menu` VALUES (19, 21, '2022-06-06 03:20:11', '2022-06-06 03:20:11');
INSERT INTO `admin_permission_menu` VALUES (19, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_permission_menu` VALUES (19, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_permission_menu` VALUES (19, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
COMMIT;

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `parent_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_permissions_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
BEGIN;
INSERT INTO `admin_permissions` VALUES (1, '系统管理', '系统管理', '', '', 1, 0, '2022-05-31 01:15:50', '2022-06-06 03:25:02');
INSERT INTO `admin_permissions` VALUES (2, '管理员管理', '管理员管理', '', '/users/*', 2, 1, '2022-05-31 01:15:50', '2022-06-06 03:25:56');
INSERT INTO `admin_permissions` VALUES (3, '角色管理', '角色管理', '', '/auth/roles*', 3, 1, '2022-05-31 01:15:50', '2022-06-06 03:25:16');
INSERT INTO `admin_permissions` VALUES (4, '权限管理', '权限管理', '', '/auth/permissions*', 4, 1, '2022-05-31 01:15:50', '2022-06-06 03:25:45');
INSERT INTO `admin_permissions` VALUES (5, '目录管理', '目录管理', '', '/auth/menu*', 5, 1, '2022-05-31 01:15:50', '2022-06-06 03:25:26');
INSERT INTO `admin_permissions` VALUES (6, '扩展管理', '扩展管理', '', '/auth/extensions*', 6, 1, '2022-05-31 01:15:50', '2022-06-06 03:25:36');
INSERT INTO `admin_permissions` VALUES (7, '应用管理', '应用管理', '', '', 7, 0, '2022-06-06 03:10:21', '2022-06-06 03:10:21');
INSERT INTO `admin_permissions` VALUES (8, '轮播图管理', '轮播图管理', '', '/sys/banner*', 8, 7, '2022-06-06 03:11:02', '2022-06-06 03:11:02');
INSERT INTO `admin_permissions` VALUES (9, '广告管理', '广告管理', '', '/sys/ad*', 9, 7, '2022-06-06 03:11:26', '2022-06-06 03:11:26');
INSERT INTO `admin_permissions` VALUES (10, '系统设置', '系统设置', '', '/sys/setting*', 10, 7, '2022-06-06 03:12:00', '2022-06-06 03:22:06');
INSERT INTO `admin_permissions` VALUES (11, '系统消息', '系统消息', '', '/log/sysmessage*', 11, 7, '2022-06-06 03:12:33', '2022-06-06 03:22:57');
INSERT INTO `admin_permissions` VALUES (12, '会员管理', '会员管理', '', '', 12, 0, '2022-06-06 03:15:16', '2022-06-06 03:15:16');
INSERT INTO `admin_permissions` VALUES (13, '会员列表', '会员列表', '', '/users*', 13, 12, '2022-06-06 03:17:06', '2022-06-06 03:23:36');
INSERT INTO `admin_permissions` VALUES (14, '会员资产记录', '会员资产记录', '', '/log/userfund*', 14, 12, '2022-06-06 03:17:31', '2022-06-06 03:23:48');
INSERT INTO `admin_permissions` VALUES (15, '文章管理', '文章管理', '', '', 15, 0, '2022-06-06 03:17:49', '2022-06-06 03:17:49');
INSERT INTO `admin_permissions` VALUES (16, '文章分类', '文章分类', '', '/article/category*', 16, 15, '2022-06-06 03:18:19', '2022-06-06 03:23:56');
INSERT INTO `admin_permissions` VALUES (17, '文章标签', '文章标签', '', '/article/tag*', 17, 15, '2022-06-06 03:18:41', '2022-06-06 03:24:02');
INSERT INTO `admin_permissions` VALUES (18, '文章列表', '文章列表', '', '/article*', 18, 15, '2022-06-06 03:19:45', '2022-06-06 03:24:11');
INSERT INTO `admin_permissions` VALUES (19, '公告管理', '公告管理', '', '/sys/notice*', 19, 15, '2022-06-06 03:20:11', '2022-06-06 03:24:20');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu` (
  `role_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_menu_role_id_menu_id_unique` (`role_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_menu` VALUES (1, 1, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 2, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 3, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 4, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 5, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 6, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 7, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 8, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 9, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 10, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 11, '2022-05-31 06:14:30', '2022-05-31 06:14:30');
INSERT INTO `admin_role_menu` VALUES (1, 12, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 13, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 14, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 15, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 16, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 17, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 18, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 19, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 20, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 21, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_menu` VALUES (1, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_role_menu` VALUES (1, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_role_menu` VALUES (1, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
INSERT INTO `admin_role_menu` VALUES (2, 1, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 8, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 9, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 12, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 13, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 14, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 16, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 17, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 18, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 19, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 20, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 21, '2022-06-06 03:03:02', '2022-06-06 03:03:02');
INSERT INTO `admin_role_menu` VALUES (2, 22, '2023-05-25 11:18:14', '2023-05-25 11:18:14');
INSERT INTO `admin_role_menu` VALUES (2, 23, '2023-05-25 11:18:46', '2023-05-25 11:18:46');
INSERT INTO `admin_role_menu` VALUES (2, 24, '2023-06-05 17:44:05', '2023-06-05 17:44:05');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permissions`;
CREATE TABLE `admin_role_permissions` (
  `role_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_permissions
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_permissions` VALUES (1, 2, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_permissions` VALUES (1, 3, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_permissions` VALUES (1, 4, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_permissions` VALUES (1, 5, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_permissions` VALUES (1, 6, '2022-06-06 03:02:04', '2022-06-06 03:02:04');
INSERT INTO `admin_role_permissions` VALUES (2, 8, '2022-06-06 03:13:03', '2022-06-06 03:13:03');
INSERT INTO `admin_role_permissions` VALUES (2, 9, '2022-06-06 03:13:03', '2022-06-06 03:13:03');
INSERT INTO `admin_role_permissions` VALUES (2, 10, '2022-06-06 03:13:03', '2022-06-06 03:13:03');
INSERT INTO `admin_role_permissions` VALUES (2, 11, '2022-06-06 03:13:03', '2022-06-06 03:13:03');
INSERT INTO `admin_role_permissions` VALUES (2, 13, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
INSERT INTO `admin_role_permissions` VALUES (2, 14, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
INSERT INTO `admin_role_permissions` VALUES (2, 16, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
INSERT INTO `admin_role_permissions` VALUES (2, 17, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
INSERT INTO `admin_role_permissions` VALUES (2, 18, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
INSERT INTO `admin_role_permissions` VALUES (2, 19, '2022-06-06 03:20:32', '2022-06-06 03:20:32');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_users`;
CREATE TABLE `admin_role_users` (
  `role_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_users_role_id_user_id_unique` (`role_id`,`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_users` VALUES (1, 1, '2022-05-31 01:15:50', '2022-05-31 01:15:50');
INSERT INTO `admin_role_users` VALUES (1, 3, '2023-02-27 09:49:21', '2023-02-27 09:49:21');
COMMIT;

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_roles_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
BEGIN;
INSERT INTO `admin_roles` VALUES (1, '超级管理员', 'administrator', '2022-05-31 01:15:50', '2022-06-06 03:07:28');
INSERT INTO `admin_roles` VALUES (2, '应用管理员', '应用管理员', '2022-06-06 03:03:02', '2022-06-06 03:03:02');
COMMIT;

-- ----------------------------
-- Table structure for admin_settings
-- ----------------------------
DROP TABLE IF EXISTS `admin_settings`;
CREATE TABLE `admin_settings` (
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`slug`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_settings
-- ----------------------------
BEGIN;
INSERT INTO `admin_settings` VALUES ('lty5240:dcat-easy-sku', '{\"key1\":\"\\u963f\\u65af\\u8fbe\",\"key2\":\"\\u963f\\u65af\\u8fbe\\u5c81\\u7684\"}', '2022-06-06 08:47:25', '2022-06-06 08:47:25');
COMMIT;

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_users_username_unique` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
BEGIN;
INSERT INTO `admin_users` VALUES (1, 'root', '$2y$10$BuDuOLpZn1cDgzrfUB1yU.TusOKaxE9LrkNE5.0C0cPy.0KQZ1hoa', '超级管理员', NULL, 'qMlqFaFsjjBFGEMj5l7AVoXYDWnLe8n3ly9I7NPZkxsLPJouSoK7i1VlywYr', '2022-05-31 01:15:50', '2022-06-09 01:48:17');
INSERT INTO `admin_users` VALUES (3, 'admin', '$2y$10$fPiKmwNBsMFZObDBXAvj9erUsYl2uNM32UxAenIZoZ341UcLjtMl.', 'admin', 'images/m_1.png', NULL, '2023-02-27 09:49:11', '2023-02-27 09:49:11');
COMMIT;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `tag_ids` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `category_id` int NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '作者',
  `intro` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '简介',
  `keyword` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `update_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `delete_allowed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章表';

-- ----------------------------
-- Table structure for article_category
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '文章分类id',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类图片',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  `delete_allowed` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可删除',
  `update_allowed` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可修改',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章分类表';

-- ----------------------------
-- Table structure for article_tag
-- ----------------------------
DROP TABLE IF EXISTS `article_tag`;
CREATE TABLE `article_tag` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '标签id',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签名称',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签图片',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章标签表';

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for idx_setting
-- ----------------------------
DROP TABLE IF EXISTS `idx_setting`;
CREATE TABLE `idx_setting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '设置类型',
  `value0` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value11` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value12` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value13` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value14` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value15` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value16` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value17` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value18` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value19` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `value20` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `update_allowed` tinyint NOT NULL DEFAULT '1',
  `delete_allowed` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='项目相关的设置';

-- ----------------------------
-- Records of idx_setting
-- ----------------------------
BEGIN;
INSERT INTO `idx_setting` VALUES (4, 'test', '测试1', 'http://192.168.0.77/uploads/admin/images/e47b4da1e5c566a9a7876b0c07389130.jpg,http://192.168.0.77/uploads/admin/images/0d1da5e26d50abe795a556d5409ba4c6.jpg', '1', NULL, 'aaaa', 'bbb', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2023-05-25 11:23:39', '2023-05-25 14:38:46', NULL, 1, 1);
INSERT INTO `idx_setting` VALUES (5, 'test', '测试2', 'http://192.168.0.77/uploads/admin/images/25ae8889711d0161cdecce8c2d4d1fa1.jpg', '1', '4', 'ccc', 'ddd', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2023-05-25 11:26:56', '2023-05-25 11:26:56', NULL, 1, 1);
INSERT INTO `idx_setting` VALUES (6, 'test', '测试3', '', '1', '5', 'eeee', 'fff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2023-05-25 11:42:58', '2023-05-25 14:55:46', NULL, 1, 1);
COMMIT;

-- ----------------------------
-- Table structure for log_sys_message
-- ----------------------------
DROP TABLE IF EXISTS `log_sys_message`;
CREATE TABLE `log_sys_message` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '系统消息id',
  `uids` varchar(2550) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系统消息表';

-- ----------------------------
-- Table structure for log_user_fund
-- ----------------------------
DROP TABLE IF EXISTS `log_user_fund`;
CREATE TABLE `log_user_fund` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '资金流水记录',
  `uid` int NOT NULL DEFAULT '0' COMMENT '会员id',
  `number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '金额',
  `coin_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '币种',
  `fund_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作类型',
  `content` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '说明',
  `remark` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '记录时间',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员资产流水表';

-- ----------------------------
-- Table structure for log_user_operation
-- ----------------------------
DROP TABLE IF EXISTS `log_user_operation`;
CREATE TABLE `log_user_operation` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '会员操作表',
  `uid` int NOT NULL DEFAULT '0' COMMENT '会员id',
  `user_identity` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员标识',
  `content` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作内容',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员操作记录';

-- ----------------------------
-- Table structure for log_user_pay
-- ----------------------------
DROP TABLE IF EXISTS `log_user_pay`;
CREATE TABLE `log_user_pay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL COMMENT '用户id',
  `order_no` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '支付单号',
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0' COMMENT '支付方式',
  `order_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '订单类型',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `platform` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '来源端',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态{radio}(1:待支付,2:支付成功,3:订单取消)',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` int NOT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='会员支付记录';

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2016_01_04_173148_create_admin_tables', 1);
INSERT INTO `migrations` VALUES (4, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (5, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (6, '2020_09_07_090635_create_admin_settings_table', 1);
INSERT INTO `migrations` VALUES (7, '2020_09_22_015815_create_admin_extensions_table', 1);
INSERT INTO `migrations` VALUES (8, '2020_11_01_083237_update_admin_menu_table', 1);
COMMIT;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`) USING BTREE,
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_ad
-- ----------------------------
DROP TABLE IF EXISTS `sys_ad`;
CREATE TABLE `sys_ad` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '广告位id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `type` enum('文字','图片','富文本') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '文字' COMMENT '广告内容类型',
  `value` varchar(2550) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '值',
  `content` text COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `update_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `delete_allowed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='广告表';

-- ----------------------------
-- Records of sys_ad
-- ----------------------------
BEGIN;
INSERT INTO `sys_ad` VALUES (15, 0, 'LOGO', NULL, '', '', '', '2023-03-06 13:42:10', '2023-03-06 13:42:10', NULL, 0, 0);
INSERT INTO `sys_ad` VALUES (16, 15, '应用LOGO', '图片', '', '', 'http://192.168.0.77/uploads/admin/images/2d6ff36251875d78c6d0e9198d3e1dcf.png', '2023-03-06 13:44:10', '2023-03-06 13:44:10', NULL, 1, 0);
INSERT INTO `sys_ad` VALUES (17, 15, '默认头像', '图片', '', '', 'http://192.168.0.77/uploads/admin/images/b89325eb253ef7f2068867770bece6c3.jpg', '2023-03-06 13:45:36', '2023-03-06 13:45:36', NULL, 1, 0);
COMMIT;

-- ----------------------------
-- Table structure for sys_banner
-- ----------------------------
DROP TABLE IF EXISTS `sys_banner`;
CREATE TABLE `sys_banner` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'banner id',
  `site` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链接',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='轮播图表';

-- ----------------------------
-- Table structure for sys_img
-- ----------------------------
DROP TABLE IF EXISTS `sys_img`;
CREATE TABLE `sys_img` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片路径',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for sys_notice
-- ----------------------------
DROP TABLE IF EXISTS `sys_notice`;
CREATE TABLE `sys_notice` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '公告id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '内容',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公告表';

-- ----------------------------
-- Table structure for sys_setting
-- ----------------------------
DROP TABLE IF EXISTS `sys_setting`;
CREATE TABLE `sys_setting` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '设置id',
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `input_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表单类型',
  `value` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '值',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `update_allowed` tinyint NOT NULL DEFAULT '1',
  `delete_allowed` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系统设置表';

-- ----------------------------
-- Table structure for user_detail
-- ----------------------------
DROP TABLE IF EXISTS `user_detail`;
CREATE TABLE `user_detail` (
  `id` int NOT NULL COMMENT '会员详细信息（预置）',
  `id_card_username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证--姓名',
  `id_card_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证--身份证号',
  `id_card_front_img` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证--身份证正面照',
  `id_card_verso_img` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证--身份证背面照',
  `id_card_hand_img` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证--手持身份证照',
  `bank_username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行卡--开户人姓名',
  `bank_phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行卡--预留手机号',
  `bank_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行卡--银行卡号',
  `bank_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行卡--开户行',
  `site_username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--姓名',
  `site_phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--手机号',
  `site_province` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--省',
  `site_city` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--市',
  `site_district` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--区/县',
  `site_address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址--详细地址',
  `wx_account` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信--微信账号',
  `wx_nickname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信--微信昵称',
  `wx_qrcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信--收款二维码',
  `alipay_account` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝--支付宝账号',
  `alipay_username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝--支付宝实名认证姓名',
  `alipay_qrcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝--收款二维码',
  `qq` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'qq',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员详情表';

-- ----------------------------
-- Table structure for user_funds
-- ----------------------------
DROP TABLE IF EXISTS `user_funds`;
CREATE TABLE `user_funds` (
  `id` int NOT NULL COMMENT '会员id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员资产表';

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '头像',
  `account` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员账号',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员邮箱',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员昵称',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员登录密码',
  `level_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '会员二级密码',
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '上级会员id',
  `sex` enum('保密','男','女') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '保密' COMMENT '性别',
  `is_login` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以登录，1是0否',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `login_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录平台',
  `unionid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方登录标识',
  `openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '单平台内唯一标识',
  `third_party` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方平台',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员表';

SET FOREIGN_KEY_CHECKS = 1;
