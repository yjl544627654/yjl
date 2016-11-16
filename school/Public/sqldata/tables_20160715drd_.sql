# -----------------------------------------------------------
# Description:备份的数据表[结构]：tp_setting,tp_user,tp_user_group
# Description:备份的数据表[数据]：tp_setting# 表的结构 tp_setting 
DROP TABLE IF EXISTS `tp_setting`;
CREATE TABLE `tp_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `settime` int(10) unsigned zerofill NOT NULL,
  `updatetime` int(10) NOT NULL,
  `user_log` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ;

# 表的结构 tp_user 
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8 NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8 NOT NULL,
  `emali` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `truename` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '真实姓名',
  `state` int(2) NOT NULL COMMENT '状态',
  `addtime` int(10) NOT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `mark` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 ;

# 表的结构 tp_user_group 
DROP TABLE IF EXISTS `tp_user_group`;
CREATE TABLE `tp_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `addtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 ;

# -----------------------------------------------------------

# Time: 2016-07-15 17:48:26
# -----------------------------------------------------------
INSERT INTO `tp_setting` VALUES ('1','lock','0000000000','1468574697','admin');
INSERT INTO `tp_user` VALUES ('2','2','user1','b2f5838cfe1e10da11fca6f49f9199b5','-pAKU0','','','0','1468399208','1468399208','1111');
INSERT INTO `tp_user` VALUES ('3','2','user2','1b316878b95c3d5035813b32edf7b91c','!iBcjv','','','0','1468400367','1468400367','');
INSERT INTO `tp_user` VALUES ('4','3','user3','09de701d822589b4819fedf1ec62650e','uiDvJS','','','0','1468400454','1468400454','');
INSERT INTO `tp_user` VALUES ('7','2','admin','728be05df93e3d40dec4a9ee22a2d5cb','f~2sfR','','','0','1468545069','1468545069','');
INSERT INTO `tp_user_group` VALUES ('2','组一','1468395599','1468395599');
INSERT INTO `tp_user_group` VALUES ('3','组二','1468396005','1468396005');
INSERT INTO `tp_user_group` VALUES ('4','测试','1468478707','1468478707');
