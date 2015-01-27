/*
Navicat MySQL Data Transfer
Source Host     : localhost:3306
Source Database : supermarket
Target Host     : localhost:3306
Target Database : supermarket
Date: 2015-01-27 19:21:00
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for addresslist
-- ----------------------------
DROP TABLE IF EXISTS `addresslist`;
CREATE TABLE `addresslist` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `addressId` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `oUserId` varchar(255) DEFAULT NULL,
  `tUserId` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `addressId` (`addressId`),
  KEY `oUserId` (`oUserId`),
  KEY `tUserId` (`tUserId`),
  CONSTRAINT `addresslist_ibfk_1` FOREIGN KEY (`oUserId`) REFERENCES `officeconsumer` (`oUserId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `addresslist_ibfk_2` FOREIGN KEY (`tUserId`) REFERENCES `tmpconsumer` (`tUserId`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of addresslist
-- ----------------------------
INSERT INTO `addresslist` VALUES ('35', '1421845202', 'zcl', 'bupt402', '15112345678', null, 'testTmpId_1');
INSERT INTO `addresslist` VALUES ('36', '1421845652', 'zcl1', 'bupt401', '15112345671', null, 'testTmpId_1');

-- ----------------------------
-- Table structure for goodsdetail
-- ----------------------------
DROP TABLE IF EXISTS `goodsdetail`;
CREATE TABLE `goodsdetail` (
  `goodsId` int(11) NOT NULL AUTO_INCREMENT,
  `goodName` varchar(50) NOT NULL,
  `goodsDesc` varchar(200) NOT NULL,
  `price` varchar(20) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `sales` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `cTime` varchar(20) DEFAULT NULL,
  `reserve1` varchar(100) DEFAULT NULL,
  `reserve2` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`goodsId`)
) ENGINE=InnoDB AUTO_INCREMENT=1114 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goodsdetail
-- ----------------------------
INSERT INTO `goodsdetail` VALUES ('1111', '小黄人', '神偷奶爸抱枕小黄人公仔毛绒玩具大号布娃娃玩偶生日礼物女生 60厘米', '58.5', 'img/2.jpg', '1', '15', '3', '5', null, null, null);
INSERT INTO `goodsdetail` VALUES ('1112', '小黄人2', '神偷奶爸抱枕小黄人公仔毛绒玩具大号布娃娃玩偶生日礼物女生 70厘米', '66', 'img/3.jpg', '11', '50', '3', '5', null, null, null);
INSERT INTO `goodsdetail` VALUES ('1113', '小黄人3', '神偷奶爸抱枕小黄人公仔毛绒玩具大号布娃娃玩偶生日礼物女生 40厘米', '50', 'img/1.jpg', '10', '50', '3', '5', null, null, null);

-- ----------------------------
-- Table structure for officeconsumer
-- ----------------------------
DROP TABLE IF EXISTS `officeconsumer`;
CREATE TABLE `officeconsumer` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `oUserId` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `cTime` varchar(20) DEFAULT NULL,
  `reserve1` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `oUserId` (`oUserId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of officeconsumer
-- ----------------------------
INSERT INTO `officeconsumer` VALUES ('1', 'testOfficeId_1', 'abc@butp.edu.cn', 'zcl', '12345678', '12345678901', null, null);

-- ----------------------------
-- Table structure for orderdetail
-- ----------------------------
DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE `orderdetail` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `goodNum` int(11) DEFAULT NULL,
  `reserve1` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `orderId` (`orderId`),
  KEY `goodsId` (`goodsId`),
  CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `userorder` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`goodsId`) REFERENCES `goodsdetail` (`goodsId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of orderdetail
-- ----------------------------
INSERT INTO `orderdetail` VALUES ('18', '1421845202', '1112', '1', null);
INSERT INTO `orderdetail` VALUES ('19', '1421845305', '1113', '2', null);
INSERT INTO `orderdetail` VALUES ('20', '1421845652', '1111', '1', null);

-- ----------------------------
-- Table structure for shopcarts
-- ----------------------------
DROP TABLE IF EXISTS `shopcarts`;
CREATE TABLE `shopcarts` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `oUserId` varchar(20) DEFAULT NULL,
  `tUserId` varchar(20) DEFAULT NULL,
  `goodsId` int(11) NOT NULL,
  `goodsNum` int(11) NOT NULL,
  `reservel` varchar(20) DEFAULT NULL,
  `isChecked` smallint(6) DEFAULT '1',
  PRIMARY KEY (`index`),
  KEY `oUserId` (`oUserId`),
  KEY `tUserId` (`tUserId`),
  KEY `goodsId` (`goodsId`),
  CONSTRAINT `shopcarts_ibfk_1` FOREIGN KEY (`oUserId`) REFERENCES `officeconsumer` (`oUserId`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `shopcarts_ibfk_2` FOREIGN KEY (`tUserId`) REFERENCES `tmpconsumer` (`tUserId`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `shopcarts_ibfk_3` FOREIGN KEY (`goodsId`) REFERENCES `goodsdetail` (`goodsId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopcarts
-- ----------------------------
INSERT INTO `shopcarts` VALUES ('16', null, 'testTmpId_1', '1112', '3', null, '1');
INSERT INTO `shopcarts` VALUES ('17', null, 'testTmpId_1', '1113', '1', null, '1');
INSERT INTO `shopcarts` VALUES ('18', null, 'testTmpId_2', '1112', '2', null, '1');
INSERT INTO `shopcarts` VALUES ('19', null, '54c756f4695ac', '1111', '1', null, '1');
INSERT INTO `shopcarts` VALUES ('20', null, '54c756f4695ac', '1112', '1', null, '1');
INSERT INTO `shopcarts` VALUES ('21', null, '54c756f4695ac', '1113', '1', null, '1');

-- ----------------------------
-- Table structure for tmpconsumer
-- ----------------------------
DROP TABLE IF EXISTS `tmpconsumer`;
CREATE TABLE `tmpconsumer` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `tUserId` varchar(20) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `cTime` varchar(20) DEFAULT NULL,
  `reserve1` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `tUserId` (`tUserId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tmpconsumer
-- ----------------------------
INSERT INTO `tmpconsumer` VALUES ('1', 'testTmpId_1', 'bupt', '15112345678', 'tmpConsumer_1', '1421750097', null);
INSERT INTO `tmpconsumer` VALUES ('2', 'testTmpId_2', 'bupt402', '15112345678', 'tmpConsumer_2', '1421760097', null);
INSERT INTO `tmpconsumer` VALUES ('3', '54c756f4695ac', null, null, null, '2015-01-27 10:14:28a', null);

-- ----------------------------
-- Table structure for userorder
-- ----------------------------
DROP TABLE IF EXISTS `userorder`;
CREATE TABLE `userorder` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `oUserId` varchar(20) DEFAULT NULL,
  `tUserId` varchar(20) DEFAULT NULL,
  `orderId` int(11) NOT NULL,
  `orderTime` varchar(20) DEFAULT NULL,
  `addressId` int(11) NOT NULL,
  `amount` varchar(20) DEFAULT NULL,
  `reserve1` varchar(20) DEFAULT NULL,
  `sendTime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `orderId` (`orderId`),
  KEY `oUserId` (`oUserId`),
  KEY `tUserId` (`tUserId`),
  KEY `addressId` (`addressId`),
  CONSTRAINT `userorder_ibfk_4` FOREIGN KEY (`oUserId`) REFERENCES `officeconsumer` (`oUserId`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `userorder_ibfk_5` FOREIGN KEY (`tUserId`) REFERENCES `tmpconsumer` (`tUserId`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `userorder_ibfk_6` FOREIGN KEY (`addressId`) REFERENCES `addresslist` (`addressId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='用户订单表';

-- ----------------------------
-- Records of userorder
-- ----------------------------
INSERT INTO `userorder` VALUES ('35', null, 'testTmpId_1', '1421845202', '1421845202', '1421845202', '66.00', null, '立即送出');
INSERT INTO `userorder` VALUES ('36', null, 'testTmpId_1', '1421845305', '1421845305', '1421845652', '100.00', null, '立即送出');
INSERT INTO `userorder` VALUES ('37', null, 'testTmpId_1', '1421845652', '1421845652', '1421845652', '58.50', null, '立即送出');
