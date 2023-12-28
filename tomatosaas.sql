/*
 Navicat Premium Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : localhost:3306
 Source Schema         : tomatosaas

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 01/11/2023 23:44:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tomato_tenancys
-- ----------------------------
DROP TABLE IF EXISTS `tomato_tenancys`;
CREATE TABLE `tomato_tenancys`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '租户名称',
  `sub_domain` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '子域名',
  `root_domain` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '客户根域名',
  `expired_at` int(11) NOT NULL COMMENT '到期时间',
  `db_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '数据库地址',
  `db_port` int(6) NOT NULL COMMENT '数据库端口',
  `db_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '租户数据库名称',
  `db_username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '租户数据库用户名',
  `db_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '租户数据库密码',
  `status` tinyint(1) NOT NULL COMMENT '租户状态',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`, `name`, `sub_domain`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '租户数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tomato_tenancys
-- ----------------------------
INSERT INTO `tomato_tenancys` VALUES (1, '测试', 'admin', NULL, 1698854400, '127.0.0.1', 3306, 'ceshi', 'ceshi', '51151sdasdsd', 1, '2023-10-31 22:47:53', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
