# xiaocms bakfile
# version:xiaocms x1 
# time:2019-08-03 09:45:24
# 
# ----------------------------------------


DROP TABLE IF EXISTS `cn_admin`;
CREATE TABLE `cn_admin` (
  `userid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `roleid` smallint(5) DEFAULT '0',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `auth` text NOT NULL,
  `list_size` smallint(5) NOT NULL,
  `left_width` smallint(5) NOT NULL DEFAULT '150',
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `cn_admin` VALUES('1','wnkj','31487fa9529826da6237f09f6f916b3b','1','超级管理员','','10','150');
INSERT INTO `cn_admin` VALUES('2','admin','e89760b59a93602dd9b7a6175169334a','0','','a:23:{s:12:\"index-config\";s:1:\"1\";s:14:\"category-index\";s:1:\"1\";s:12:\"category-add\";s:1:\"1\";s:13:\"category-edit\";s:1:\"1\";s:12:\"category-del\";s:1:\"1\";s:13:\"content-index\";s:1:\"1\";s:11:\"content-add\";s:1:\"1\";s:12:\"content-edit\";s:1:\"1\";s:11:\"content-del\";s:1:\"1\";s:14:\"diytable-index\";s:1:\"1\";s:12:\"diytable-add\";s:1:\"1\";s:13:\"diytable-edit\";s:1:\"1\";s:12:\"diytable-del\";s:1:\"1\";s:10:\"form-index\";s:1:\"1\";s:9:\"form-edit\";s:1:\"1\";s:8:\"form-del\";s:1:\"1\";s:14:\"database-index\";s:1:\"1\";s:15:\"database-import\";s:1:\"1\";s:12:\"models-index\";s:1:\"1\";s:12:\"models-field\";s:1:\"1\";s:16:\"models-editfield\";s:1:\"1\";s:15:\"models-delfield\";s:1:\"1\";s:14:\"models-disable\";s:1:\"1\";}','0','150');

DROP TABLE IF EXISTS `cn_block`;
CREATE TABLE `cn_block` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_category`;
CREATE TABLE `cn_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(1) NOT NULL,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `childids` varchar(255) NOT NULL,
  `catname` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `catdir` varchar(30) NOT NULL,
  `http` varchar(255) NOT NULL,
  `items` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ispost` smallint(2) NOT NULL,
  `verify` smallint(2) NOT NULL DEFAULT '0',
  `islook` smallint(2) NOT NULL,
  `listtpl` varchar(50) NOT NULL,
  `showtpl` varchar(50) NOT NULL,
  `pagetpl` varchar(50) NOT NULL,
  `pagesize` smallint(5) NOT NULL,
  `yw` varchar(255) NOT NULL,
  `xin` varchar(255) NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `listorder` (`listorder`,`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

INSERT INTO `cn_category` VALUES('1','3','0','0','1','7,8,9,10,11,','关于法拉古特','','','','','','AFA','/index.php?catid=7','0','0','1','0','0','0','list_pic.html','show_pic.html','page.html','10','About AFA','');
INSERT INTO `cn_category` VALUES('2','3','0','0','1','19,20,25,26,','学校介绍','','','','','','About','/index.php?catid=19','0','0','1','0','0','0','','','','10','About Us','');
INSERT INTO `cn_category` VALUES('3','3','0','0','1','21,22,','校园生活','','','','','','Life','http://www.baidu.com','0','0','1','0','0','0','','','','10','Campus Life','1');
INSERT INTO `cn_category` VALUES('4','3','0','0','1','12,23,24,','教育及教学','','','','','','Teaching','/index.php?catid=12','6','0','1','0','0','0','','','','10','Education and Teaching','');
INSERT INTO `cn_category` VALUES('5','3','0','0','1','13,16,17,18,','东莞校区动态','','','','','','Dynamics','/index.php?catid=13','8','0','1','0','0','0','','','','10','Campus Dynamics','');
INSERT INTO `cn_category` VALUES('6','2','0','0','0','','联系我们','','&lt;p&gt;\r\n								我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;\r\n								而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','','','','Contact','###','0','0','1','0','0','0','','','page.html','10','Contact Us','');
INSERT INTO `cn_category` VALUES('7','2','0','1','0','','美国佛州百年名校','','&lt;p&gt;&lt;img src=&quot;/data/upload/image/20190614/1560519532133411.jpg&quot; title=&quot;1560519532133411.jpg&quot; alt=&quot;ty1.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;color: rgb(0, 50, 91); font-family: 微软雅黑; font-size: 20px; background-color: rgb(255, 255, 255);&quot;&gt;一&amp;nbsp; &amp;nbsp;学术背书不同&lt;/span&gt;&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;http://www.fala.com/data/upload/image/20190614/1560519532133411.jpg&quot; title=&quot;1560519532133411.jpg&quot; alt=&quot;ty1.jpg&quot; style=&quot;white-space: normal;&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal;&quot;&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p style=&quot;white-space: normal;&quot;&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal;&quot;&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p style=&quot;white-space: normal;&quot;&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal;&quot;&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','ming','','0','0','1','0','0','0','','','page.html','10','Century- Elite School in the Florida US','');
INSERT INTO `cn_category` VALUES('8','2','0','1','0','','总校长致辞','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','zongz','','0','0','1','0','0','0','','','page.html','10','Message from Florida Headmaster','');
INSERT INTO `cn_category` VALUES('9','2','0','1','0','','法拉古特是谁','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','whos','','0','0','1','0','0','0','','','page.html','10','Who is Admiral Farragut Academy?','');
INSERT INTO `cn_category` VALUES('10','2','0','1','0','','两名登月宇航员','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','liangming','','0','0','1','0','0','0','','','page.html','10','The Two Astronauts','');
INSERT INTO `cn_category` VALUES('11','2','0','1','0','','法拉古特在中国','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','falagute','','0','0','1','0','0','0','','','page.html','10','AFA China','');
INSERT INTO `cn_category` VALUES('12','1','7','4','0','','教师团队','','','','','','Teacher','','6','0','1','0','0','0','list_pic.html','show_pic.html','','10','Teacher Team','');
INSERT INTO `cn_category` VALUES('13','1','1','5','0','','校区动态','','','','','','News','','8','0','1','0','0','0','list_news.html','show_news.html','','10','School news','');
INSERT INTO `cn_category` VALUES('25','2','0','2','0','','学校内景','','&lt;p&gt;&lt;img src=&quot;/data/upload/image/20190619/1560909710946225.jpg&quot; title=&quot;1560909710946225.jpg&quot; alt=&quot;丰泰书院文字.jpg&quot; width=&quot;536&quot; height=&quot;147&quot; style=&quot;width: 536px; height: 147px;&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;法拉古特学校&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;法拉古特学校&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;法拉古特学校啊&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;&lt;img src=&quot;/data/upload/image/20190619/1560909801213910.jpg&quot; title=&quot;1560909801213910.jpg&quot; alt=&quot;丰泰印章.jpg&quot; width=&quot;137&quot; height=&quot;108&quot; style=&quot;width: 137px; height: 108px;&quot;/&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;&amp;nbsp;&lt;img src=&quot;http://fala.wennakeji.com/data/upload/image/20190619/1560909801213910.jpg&quot; title=&quot;1560909801213910.jpg&quot; alt=&quot;丰泰印章.jpg&quot; width=&quot;137&quot; height=&quot;108&quot; style=&quot;white-space: normal; width: 137px; height: 108px;&quot;/&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;&lt;img src=&quot;http://fala.wennakeji.com/data/upload/image/20190619/1560909801213910.jpg&quot; title=&quot;1560909801213910.jpg&quot; alt=&quot;丰泰印章.jpg&quot; width=&quot;137&quot; height=&quot;108&quot; style=&quot;white-space: normal; width: 137px; height: 108px;&quot;/&gt;&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; 123&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;456&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;789&lt;/p&gt;','','','','InsideView','','0','0','1','0','0','0','','','page.html','10','Inside View','');
INSERT INTO `cn_category` VALUES('15','4','10','0','0','','报名管理','','','','','','baomingguanli','','0','0','1','0','0','0','','','page1.html','10','','');
INSERT INTO `cn_category` VALUES('16','2','0','5','0','','三年制高中班','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','sannian','','0','0','1','0','0','0','','','page.html','10','Three-year High School Curriculum Program','');
INSERT INTO `cn_category` VALUES('17','2','0','5','0','','国际大学升学指导','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','guoji','','0','0','1','0','0','0','','','page.html','10','University Application Guidance','');
INSERT INTO `cn_category` VALUES('18','2','0','5','0','','校内心理咨询','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','xiaonei','','0','0','1','0','0','0','','','page.html','10','Psychological Consulting','');
INSERT INTO `cn_category` VALUES('19','2','0','2','0','','美国佛州百年名校','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','meiguof','','0','0','1','0','0','0','','','page.html','10','Century- Elite School in the','');
INSERT INTO `cn_category` VALUES('20','2','0','2','0','','总校长致辞','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','zongxiao','','0','0','1','0','0','0','','','page.html','10','Message from Florida','');
INSERT INTO `cn_category` VALUES('21','1','9','3','0','','两名登月宇航员','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;','','','','liangng','','0','0','1','0','0','0','list_qita.html','show_qita.html','page.html','10','Efficiency For A Better World','');
INSERT INTO `cn_category` VALUES('22','1','9','3','0','','校法拉古特在中国','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','falagutezaizhongguo','','0','0','1','0','0','0','list_qita.html','show_qita.html','page.html','10','AFA China','');
INSERT INTO `cn_category` VALUES('23','2','0','4','0','','法拉古特是谁','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','falagutesh','','0','0','1','0','0','0','','','page.html','10','Who is Admiral Farragut Academy?','1');
INSERT INTO `cn_category` VALUES('24','2','0','4','0','','两名登月宇航员','','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','','','dengyueyuhangyuan','','0','0','1','0','0','0','','','page.html','10','The Two Astronauts','');
INSERT INTO `cn_category` VALUES('26','1','1','2','0','','测试页','','&lt;p&gt;表单模型&lt;/p&gt;','','','','ceshiye','','0','0','1','0','0','0','list_news.html','show_news.html','page.html','10','Page for Test','');

DROP TABLE IF EXISTS `cn_content`;
CREATE TABLE `cn_content` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) NOT NULL,
  `title` varchar(80) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `hits` smallint(5) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`,`listorder`,`time`),
  KEY `time` (`catid`,`time`),
  KEY `status` (`catid`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

INSERT INTO `cn_content` VALUES('1','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503052');
INSERT INTO `cn_content` VALUES('2','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503242');
INSERT INTO `cn_content` VALUES('3','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503255');
INSERT INTO `cn_content` VALUES('4','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503272');
INSERT INTO `cn_content` VALUES('5','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503311');
INSERT INTO `cn_content` VALUES('6','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503330');
INSERT INTO `cn_content` VALUES('7','13','1','5月9日入学测试结果公布','','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503345');
INSERT INTO `cn_content` VALUES('20','13','1','6.30发布会','','发布会','发布会','0','1','0','admin','1560910839');
INSERT INTO `cn_content` VALUES('14','12','7','自然科学老师','/data/upload/image/20190614/1560503830107261.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503807');
INSERT INTO `cn_content` VALUES('15','12','7','自然科学老师','/data/upload/image/20190614/1560503830107261.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503868');
INSERT INTO `cn_content` VALUES('16','12','7','自然科学老师','/data/upload/image/20190614/1560503830107261.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503885');
INSERT INTO `cn_content` VALUES('17','12','7','自然科学老师','/data/upload/image/20190614/1560503830107261.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503911');
INSERT INTO `cn_content` VALUES('18','12','7','数学老师','/data/upload/image/20190619/1560909195110768.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503934');
INSERT INTO `cn_content` VALUES('19','12','7','自然科学老师','/data/upload/image/20190614/1560503830107261.jpg','','我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来','0','1','0','wnkj','1560503951');

DROP TABLE IF EXISTS `cn_content_news`;
CREATE TABLE `cn_content_news` (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext NOT NULL,
  `yingwen` varchar(255) NOT NULL,
  `wb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cn_content_news` VALUES('1','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('2','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('3','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('4','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('5','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('6','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','Title Mean','');
INSERT INTO `cn_content_news` VALUES('7','13','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','Title Mean','http://www.baidu.com');
INSERT INTO `cn_content_news` VALUES('20','13','&lt;p&gt;发布会&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','','');

DROP TABLE IF EXISTS `cn_content_pic`;
CREATE TABLE `cn_content_pic` (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext NOT NULL,
  `yingwen` varchar(255) NOT NULL,
  `wb` varchar(255) NOT NULL,
  `suolvtuchicun` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cn_content_pic` VALUES('14','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Alex','','');
INSERT INTO `cn_content_pic` VALUES('15','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;','Alex','','');
INSERT INTO `cn_content_pic` VALUES('16','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Alex','','');
INSERT INTO `cn_content_pic` VALUES('17','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Alex','','');
INSERT INTO `cn_content_pic` VALUES('18','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Kang','','');
INSERT INTO `cn_content_pic` VALUES('19','12','&lt;p&gt;我们是美国法拉古特学校直接授权的官方分校，因此学生通过考试被录取后注册的是法拉古特学校在美国的学籍，成绩合格毕业之后将以和美国学生同等级别的成绩单去申请美国大学，所展示的GPA和学校表现在大学招生官看来更加具有说服力，因为美国高中的成绩单造假后果是非常非常严重的，要承担极其严重的法律责任。&lt;/p&gt;&lt;p&gt;而同时法拉古特作为全美前5%的私立高中，已经办学86年，在大学招生记录里具有良好的信誉。&lt;/p&gt;','Alex','','');

DROP TABLE IF EXISTS `cn_content_product`;
CREATE TABLE `cn_content_product` (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_content_qita`;
CREATE TABLE `cn_content_qita` (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext NOT NULL,
  `yingwen` varchar(255) NOT NULL,
  `suolvtuchicun` varchar(255) NOT NULL,
  `wb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_diy_bt1`;
CREATE TABLE `cn_diy_bt1` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL,
  `dh` varchar(255) NOT NULL,
  `dz` mediumtext NOT NULL,
  `ewm1` varchar(255) NOT NULL,
  `wz1` varchar(255) NOT NULL,
  `ewm2` varchar(255) NOT NULL,
  `wz2` varchar(255) NOT NULL,
  `guanyu` mediumtext NOT NULL,
  `tup` varchar(255) NOT NULL,
  `jiaoshituanduituan` mediumtext NOT NULL,
  `bjt` varchar(255) NOT NULL,
  `wangzhan` varchar(255) NOT NULL,
  `yue` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `cn_diy_bt1` VALUES('1','/data/upload/image/20190614/1560492573129801.png','07693353-8877','东莞市东城街道东城光明路6号','/data/upload/image/20190614/1560492576126074.jpg','法拉古特东莞校区','/data/upload/image/20190614/1560492578300189.jpg','法拉古特东莞校区招生主任：Kevin','&lt;p&gt;美国法拉古特学校东莞校区，是美国法拉古特学校的中国区旗舰校，是一所以培养学生进入世界一流大学为目标的全日制国际学校。&lt;/p&gt;\r\n\r\n&lt;p&gt;学校开设“三年制高中班”，为就读学生注册美国法拉古特学校学籍，所修读课程及学分均被美国学校认可，达到毕业要求者授予世界承认的美国中学毕业证。学校引进美国原版教材，同步美国中学课程，涵盖语言、数学、自然科学、社会科学、艺术体育及兴趣选修类课程6大类别，完美接轨美国高中与世界一流大学。&lt;/p&gt;\r\n\r\n&lt;p&gt;Admiral Farragut Academy Dongguan Campus is the flagship school of Admiral Farragut Academy in China. It is a full-time International School aiming at preparing students to enter world-class universities. &lt;/p&gt;\r\n\r\n&lt;p&gt;The school offers a “three-year high school classes” track. It also offers students to register to Admiral Farragut Academy. Our courses and awarded credits are recognized by American schools. Those who meet the graduation requirements are awarded a globally recognized American high school diploma. The school introduced the original American textbooks to harmonize the American secondary school curriculum, covering six major categories of language, mathematics, natural sciences, social sciences, art, sports and interest elective courses, perfectly bridging American high schools to world-class universities.&lt;/p&gt;','/data/upload/image/20190619/1560909516666863.jpg','&lt;p&gt;美国法拉古特学校东莞校区拥有一支年轻热情且高素质的教师团队。他们在其各自出生地国家均拥有当地教师职业资格教师，而且曾任职于当地学校教授相应学科，并拥有丰富的教学经验。&lt;/p&gt;\r\n\r\n\r\n&lt;p&gt;Admiral Farragut Academy Dongguan Campus has a team of young, enthusiastic and high-quality teachers. They have local teachers’ professional qualifications in their motherland, have worked in local schools to teach related subjects and have rich teaching experience.&lt;/p&gt;\r\n\r\n&lt;p&gt;All the teachers in the school not only devote themselves to the daily teaching work, but also take an active part in the activities of students after-class clubs, and take the initiative to undertake the care and guidance work of students in their study and life.&lt;/p&gt;','/data/upload/image/20190619/1560909260422184.png','/data/upload/image/20190614/1560517316638204.png','&lt;option value=&quot;1年级&quot;&gt;1年级&lt;/option&gt;\r\n&lt;option value=&quot;2年级&quot;&gt;2年级&lt;/option&gt;\r\n&lt;option value=&quot;3年级&quot;&gt;3年级&lt;/option&gt;\r\n&lt;option value=&quot;初一&quot;&gt;初一&lt;/option&gt;');

DROP TABLE IF EXISTS `cn_diy_huan`;
CREATE TABLE `cn_diy_huan` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `tp` varchar(255) NOT NULL,
  `wenzi` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `cn_diy_huan` VALUES('1','/data/upload/image/20190614/1560479128852072.jpg','4.21东莞国际教育讲座活动完美谢幕');
INSERT INTO `cn_diy_huan` VALUES('2','/data/upload/image/20190614/1560479128852072.jpg','4.21东莞国际教育讲座活动完美谢幕');
INSERT INTO `cn_diy_huan` VALUES('3','/data/upload/image/20190614/1560479128852072.jpg','4.21东莞国际教育讲座活动完美谢幕');
INSERT INTO `cn_diy_huan` VALUES('4','/data/upload/image/20190614/1560479128852072.jpg','4.21东莞国际教育讲座活动完美谢幕');
INSERT INTO `cn_diy_huan` VALUES('5','/data/upload/image/20190619/1560908782543190.jpg','国际学校');

DROP TABLE IF EXISTS `cn_form_bao`;
CREATE TABLE `cn_form_bao` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` char(20) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(20) DEFAULT NULL,
  `xm` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `xue` varchar(255) NOT NULL,
  `nianji` varchar(255) NOT NULL,
  `zt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `time` (`time`),
  KEY `userid` (`userid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `cn_form_bao` VALUES('10','0','0','','1','1561166557','27.44.217.37','test','男','12345678901','法拉古特','1年级','测试数据');
INSERT INTO `cn_form_bao` VALUES('13','0','0','','1','1561167402','27.44.217.37','test2','女','13800000000','','','');

DROP TABLE IF EXISTS `cn_form_comment`;
CREATE TABLE `cn_form_comment` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` char(20) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(20) DEFAULT NULL,
  `pinglunneirong` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`),
  KEY `status` (`status`),
  KEY `time` (`time`),
  KEY `userid` (`userid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_form_gestbook`;
CREATE TABLE `cn_form_gestbook` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` char(20) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(20) DEFAULT NULL,
  `nindexingming` varchar(255) DEFAULT NULL,
  `lianxiQQ` varchar(255) DEFAULT NULL,
  `liuyanneirong` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`),
  KEY `status` (`status`),
  KEY `time` (`time`),
  KEY `userid` (`userid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_keylink`;
CREATE TABLE `cn_keylink` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `link` varchar(100) NOT NULL,
  `weight` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_member`;
CREATE TABLE `cn_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `modelid` smallint(5) NOT NULL,
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_member_connect`;
CREATE TABLE `cn_member_connect` (
  `uid` mediumint(9) NOT NULL,
  `openid` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_member_geren`;
CREATE TABLE `cn_member_geren` (
  `id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cn_model`;
CREATE TABLE `cn_model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(3) NOT NULL,
  `modelname` char(30) NOT NULL,
  `tablename` char(20) NOT NULL,
  `listtpl` varchar(30) NOT NULL,
  `showtpl` varchar(30) NOT NULL,
  `joinid` smallint(5) DEFAULT NULL,
  `setting` text,
  PRIMARY KEY (`modelid`),
  KEY `typeid` (`typeid`),
  KEY `joinid` (`joinid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO `cn_model` VALUES('1','1','文章模型','content_news','list_news.html','show_news.html','0','a:1:{s:7:\"default\";a:6:{s:5:\"title\";a:2:{s:4:\"name\";s:6:\"标题\";s:4:\"show\";s:1:\"1\";}s:8:\"keywords\";a:2:{s:4:\"name\";s:9:\"关键字\";s:4:\"show\";s:1:\"1\";}s:5:\"thumb\";a:2:{s:4:\"name\";s:9:\"缩略图\";s:4:\"show\";s:1:\"0\";}s:11:\"description\";a:2:{s:4:\"name\";s:6:\"描述\";s:4:\"show\";s:1:\"1\";}s:4:\"time\";a:2:{s:4:\"name\";s:12:\"发布时间\";s:4:\"show\";s:1:\"1\";}s:4:\"hits\";a:2:{s:4:\"name\";s:9:\"阅读数\";s:4:\"show\";s:1:\"1\";}}}');
INSERT INTO `cn_model` VALUES('5','2','个人','member_geren','list_geren.html','show_geren.html','0','');
INSERT INTO `cn_model` VALUES('6','4','幻灯片','diy_huan','list_huan.html','show_huan.html','0','');
INSERT INTO `cn_model` VALUES('7','1','图片模型','content_pic','list_pic.html','show_pic.html','0','a:1:{s:7:\"default\";a:6:{s:5:\"title\";a:2:{s:4:\"name\";s:6:\"标题\";s:4:\"show\";s:1:\"1\";}s:8:\"keywords\";a:2:{s:4:\"name\";s:9:\"关键字\";s:4:\"show\";s:1:\"1\";}s:5:\"thumb\";a:2:{s:4:\"name\";s:9:\"缩略图\";s:4:\"show\";s:1:\"1\";}s:11:\"description\";a:2:{s:4:\"name\";s:6:\"描述\";s:4:\"show\";s:1:\"1\";}s:4:\"time\";a:2:{s:4:\"name\";s:12:\"发布时间\";s:4:\"show\";s:1:\"1\";}s:4:\"hits\";a:2:{s:4:\"name\";s:9:\"阅读数\";s:4:\"show\";s:1:\"1\";}}}');
INSERT INTO `cn_model` VALUES('8','4','首页设置','diy_bt1','list_bt1.html','show_bt1.html','0','');
INSERT INTO `cn_model` VALUES('9','1','其他模型','content_qita','list_qita.html','show_qita.html','0','a:1:{s:7:\"default\";a:6:{s:5:\"title\";a:2:{s:4:\"name\";s:6:\"标题\";s:4:\"show\";s:1:\"1\";}s:8:\"keywords\";a:2:{s:4:\"name\";s:9:\"关键字\";s:4:\"show\";s:1:\"1\";}s:5:\"thumb\";a:2:{s:4:\"name\";s:9:\"缩略图\";s:4:\"show\";s:1:\"1\";}s:11:\"description\";a:2:{s:4:\"name\";s:6:\"描述\";s:4:\"show\";s:1:\"1\";}s:4:\"time\";a:2:{s:4:\"name\";s:12:\"发布时间\";s:4:\"show\";s:1:\"1\";}s:4:\"hits\";a:2:{s:4:\"name\";s:9:\"阅读数\";s:4:\"show\";s:1:\"1\";}}}');
INSERT INTO `cn_model` VALUES('10','3','报名管理','form_bao','list_bao.html','form.html','0','a:1:{s:4:\"form\";a:10:{s:4:\"post\";s:1:\"0\";s:3:\"num\";s:1:\"0\";s:4:\"time\";s:0:\"\";s:5:\"check\";s:1:\"0\";s:4:\"code\";s:1:\"0\";s:6:\"member\";s:1:\"0\";s:5:\"email\";s:1:\"0\";s:11:\"smtpemailto\";s:0:\"\";s:11:\"mailsubject\";s:0:\"\";s:4:\"show\";a:6:{i:0;s:2:\"xm\";i:1;s:3:\"sex\";i:2;s:3:\"tel\";i:3;s:3:\"xue\";i:4;s:6:\"nianji\";i:5;s:2:\"zt\";}}}');

DROP TABLE IF EXISTS `cn_model_field`;
CREATE TABLE `cn_model_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `tips` text NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `errortips` varchar(255) NOT NULL,
  `formtype` varchar(20) NOT NULL,
  `setting` mediumtext NOT NULL,
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

INSERT INTO `cn_model_field` VALUES('1','1','content','内容','1','','','','editor','a:4:{s:7:\"toolbar\";s:1:\"1\";s:5:\"width\";s:3:\"700\";s:6:\"height\";s:3:\"450\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('7','0','yw','英文','1','','','','input','a:2:{s:4:\"size\";s:3:\"350\";s:12:\"defaultvalue\";s:0:\"\";}','5','0');
INSERT INTO `cn_model_field` VALUES('8','6','tp','图片','1','尺寸：1920*800','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','0','0');
INSERT INTO `cn_model_field` VALUES('9','6','wenzi','文字','1','','','','input','a:2:{s:4:\"size\";s:3:\"300\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('10','7','content','内容','1','','','','editor','a:6:{s:4:\"type\";s:1:\"0\";s:7:\"toolbar\";s:1:\"1\";s:5:\"items\";s:0:\"\";s:5:\"width\";s:3:\"700\";s:6:\"height\";s:3:\"450\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('11','7','yingwen','英文','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','10','0');
INSERT INTO `cn_model_field` VALUES('16','8','logo','底部logo','1','尺寸：149*179 png','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','0','0');
INSERT INTO `cn_model_field` VALUES('17','8','dh','座机电话','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:13:\"07693353-8877\";}','0','0');
INSERT INTO `cn_model_field` VALUES('14','9','content','内容 ','1','','','','editor','','0','1');
INSERT INTO `cn_model_field` VALUES('15','9','yingwen','英文','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','10','0');
INSERT INTO `cn_model_field` VALUES('18','8','dz','地址','1','','','','textarea','a:3:{s:5:\"width\";s:3:\"300\";s:6:\"height\";s:2:\"40\";s:12:\"defaultvalue\";s:40:\"东莞市东城街道东城光明路6号\";}','0','0');
INSERT INTO `cn_model_field` VALUES('19','8','ewm1','二维码1','1','尺寸：189*189 jpg png gif','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','0','0');
INSERT INTO `cn_model_field` VALUES('20','8','wz1','二维码1文字','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:24:\"法拉古特东莞校区\";}','0','0');
INSERT INTO `cn_model_field` VALUES('21','8','ewm2','二维码2','1','尺寸：189*189 jpg png gif','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','0','0');
INSERT INTO `cn_model_field` VALUES('22','8','wz2','二维码2文字','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:44:\"法拉古特东莞校区招生主任：Kevin\";}','0','0');
INSERT INTO `cn_model_field` VALUES('23','10','xm','姓名','1','','1','请填写您的姓名','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('24','10','sex','性别','1','','','','radio','a:2:{s:7:\"content\";s:16:\"男|男\r\n女|女\";s:12:\"defaultvalue\";s:3:\"男\";}','0','0');
INSERT INTO `cn_model_field` VALUES('25','10','tel','电话','1','','1','请填写您的电话','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('26','10','xue','就读学校','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('27','10','nianji','就读年级','1','','','','select','a:2:{s:7:\"content\";s:49:\"1年级|1年级\r\n2年级|2年级\r\n3年级|3年级\";s:12:\"defaultvalue\";s:7:\"1年级\";}','0','0');
INSERT INTO `cn_model_field` VALUES('28','1','yingwen','标题英文','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','10','0');
INSERT INTO `cn_model_field` VALUES('29','1','wb','外链','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','8','0');
INSERT INTO `cn_model_field` VALUES('30','7','wb','外链','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','9','0');
INSERT INTO `cn_model_field` VALUES('31','10','zt','状态','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');
INSERT INTO `cn_model_field` VALUES('32','8','guanyu','关于法拉古特','1','','','','textarea','a:3:{s:5:\"width\";s:3:\"500\";s:6:\"height\";s:3:\"300\";s:12:\"defaultvalue\";s:0:\"\";}','10','0');
INSERT INTO `cn_model_field` VALUES('33','8','tup','关于图片','1','尺寸：1063*480 jpg png gif','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','9','0');
INSERT INTO `cn_model_field` VALUES('34','8','jiaoshituanduituan','教师团队','1','','','','textarea','a:3:{s:5:\"width\";s:3:\"500\";s:6:\"height\";s:3:\"300\";s:12:\"defaultvalue\";s:0:\"\";}','8','0');
INSERT INTO `cn_model_field` VALUES('35','8','bjt','教师团队背景图','1','尺寸：1902*1396  jpg png gif','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','7','0');
INSERT INTO `cn_model_field` VALUES('36','8','wangzhan','网站logo','1','尺寸：309*91 png','','','file','a:3:{s:4:\"type\";s:11:\"gif,png,jpg\";s:7:\"preview\";s:1:\"1\";s:4:\"size\";s:1:\"2\";}','11','0');
INSERT INTO `cn_model_field` VALUES('37','7','suolvtuchicun','缩率图尺寸','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:40:\"上方缩率尺寸：250*301 jpg png gif\";}','11','0');
INSERT INTO `cn_model_field` VALUES('38','9','suolvtuchicun','缩率图尺寸','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:40:\"上方缩率尺寸：972*446 jpg png gif\";}','11','0');
INSERT INTO `cn_model_field` VALUES('39','9','wb','外链','1','','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','9','0');
INSERT INTO `cn_model_field` VALUES('40','0','xin','新窗口','1','在新窗口打开的话写1即可','','','input','a:2:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";}','4','0');
INSERT INTO `cn_model_field` VALUES('42','8','yue','预约选项','1','','','','textarea','a:3:{s:5:\"width\";s:3:\"300\";s:6:\"height\";s:3:\"100\";s:12:\"defaultvalue\";s:0:\"\";}','0','0');

DROP TABLE IF EXISTS `cn_weixinmenu`;
CREATE TABLE `cn_weixinmenu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `childids` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


