/*
Navicat MySQL Data Transfer

Source Server         : local3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : db_niming

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-01-13 11:45:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tb_txt
-- ----------------------------
DROP TABLE IF EXISTS `tb_txt`;
CREATE TABLE `tb_txt` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `txt` varchar(50) NOT NULL,
  `tid` int(9) NOT NULL,
  `fid` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nickname` char(20) NOT NULL,
  `imgurl` varchar(225) NOT NULL,
  `openid` char(30) NOT NULL,
  `book` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tb_zan
-- ----------------------------
DROP TABLE IF EXISTS `tb_zan`;
CREATE TABLE `tb_zan` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `txtid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `flag` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
