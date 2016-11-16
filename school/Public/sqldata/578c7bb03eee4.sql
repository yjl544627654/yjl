/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : school

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-18 14:47:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_log`;
CREATE TABLE `tp_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin` varchar(255) NOT NULL,
  `operate` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `mark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_log
-- ----------------------------
INSERT INTO `tp_log` VALUES ('1', 'admin', '修改账户资料', '1468807692', null);
INSERT INTO `tp_log` VALUES ('2', 'admin', '禁用用户，id:2', '1468807965', null);
INSERT INTO `tp_log` VALUES ('3', 'admin', '禁用用户，id:4', '1468807975', null);
INSERT INTO `tp_log` VALUES ('4', 'admin', '启用用户，id:2', '1468807980', null);
INSERT INTO `tp_log` VALUES ('5', 'admin', '启用用户，id:4', '1468807981', null);
INSERT INTO `tp_log` VALUES ('6', 'admin', '设置锁屏时间为：1小时', '1468808400', null);
INSERT INTO `tp_log` VALUES ('7', 'admin', '解锁登录', '1468812128', null);
INSERT INTO `tp_log` VALUES ('8', 'admin', '解锁登录', '1468824343', null);
