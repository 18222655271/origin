
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 表的结构 `__PREFIX__wwh_about`
--

CREATE TABLE `__PREFIX__wwh_about` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `about_title` varchar(100) DEFAULT NULL COMMENT '关于标题',
  `about_description` text COMMENT '关于简述',
  `about_content` text COMMENT '关于内容',
  `culture_title1` varchar(100) DEFAULT NULL COMMENT '企业文化标题1',
  `culture_en1` varchar(100) DEFAULT NULL COMMENT '企业文化英文1',
  `culture_des1` varchar(500) DEFAULT NULL COMMENT '企业文化描述1',
  `culture_title2` varchar(100) DEFAULT NULL COMMENT '企业文化标题2',
  `culture_en2` varchar(500) DEFAULT NULL COMMENT '企业文化英文2',
  `culture_title3` varchar(100) DEFAULT NULL COMMENT '企业文化标题3',
  `culture_en3` varchar(500) DEFAULT NULL COMMENT '企业文化英文3',
  `culture_title4` varchar(100) DEFAULT NULL COMMENT '企业文化标题4',
  `culture_en4` varchar(500) DEFAULT NULL COMMENT '企业文化英文4',
  `culture_title5` varchar(100) DEFAULT NULL COMMENT '企业文化标题5',
  `culture_en5` varchar(500) DEFAULT NULL COMMENT '企业文化英文5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_banner`
--

CREATE TABLE `__PREFIX__wwh_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(1000) DEFAULT NULL COMMENT '标题',
  `pc_image` varchar(1000) DEFAULT NULL COMMENT 'PC图片',
  `phone_image` varchar(1000) DEFAULT NULL COMMENT '手机图片',
  `video_image` varchar(1000) DEFAULT NULL COMMENT '视频',
  `bigfont` varchar(1000) DEFAULT NULL COMMENT '大字',
  `font` varchar(1000) DEFAULT NULL COMMENT '小字',
  `url` varchar(1000) DEFAULT NULL COMMENT '链接',
  `sort` varchar(10) DEFAULT NULL COMMENT '排序',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_cases`
--

CREATE TABLE `__PREFIX__wwh_cases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `casescategoryid` int(11) DEFAULT NULL COMMENT '分类ID',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `casesname` varchar(100) DEFAULT NULL COMMENT '方案名称',
  `c_keywords` varchar(1000) DEFAULT NULL COMMENT '关键字',
  `c_description` varchar(1000) DEFAULT NULL COMMENT '说明',
  `indent_image` varchar(1000) DEFAULT NULL COMMENT '方案缩列图',
  `time` date DEFAULT NULL COMMENT '日期',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_casescategory`
--

CREATE TABLE `__PREFIX__wwh_casescategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT 'pid',
  `name` varchar(50) DEFAULT NULL COMMENT '方案分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_config`
--

CREATE TABLE `__PREFIX__wwh_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_name` varchar(100) DEFAULT NULL COMMENT '站点名称',
  `keywords` varchar(500) DEFAULT NULL COMMENT '关键字',
  `description` varchar(1000) DEFAULT NULL COMMENT '描述',
  `logo` varchar(1000) DEFAULT NULL COMMENT 'logo',
  `email` varchar(1000) DEFAULT NULL COMMENT '邮箱',
  `gongwang` varchar(1000) DEFAULT NULL COMMENT '公网安备号',
  `beian` varchar(1000) DEFAULT NULL COMMENT '网站备案号',
  `copyright` varchar(1000) DEFAULT NULL COMMENT '版权',
  `image` varchar(1000) DEFAULT NULL COMMENT '二维码图片',
  `banner1` varchar(500) DEFAULT NULL COMMENT '产品中心',
  `banner2` varchar(500) DEFAULT NULL COMMENT '解决方案',
  `banner3` varchar(500) DEFAULT NULL COMMENT '服务中心',
  `banner4` varchar(500) DEFAULT NULL COMMENT '新闻中心',
  `banner5` varchar(500) DEFAULT NULL COMMENT '关于我们',
  `content` text COMMENT '底部链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_contact`
--

CREATE TABLE `__PREFIX__wwh_contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tel` varchar(100) DEFAULT NULL COMMENT '总机',
  `fax` varchar(100) DEFAULT NULL COMMENT '传真',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `time` varchar(100) DEFAULT NULL COMMENT '工作时间',
  `address` varchar(500) DEFAULT NULL COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_development`
--

CREATE TABLE `__PREFIX__wwh_development` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `createtime` int(11) NOT NULL COMMENT '添加时间',
  `year` varchar(50) NOT NULL COMMENT '发展历程年份',
  `content` text NOT NULL COMMENT '发展历程介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_download`
--

CREATE TABLE `__PREFIX__wwh_download` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `downloadcategoryid` int(10) DEFAULT NULL COMMENT '分类ID',
  `createtime` int(11) NOT NULL COMMENT '添加时间',
  `downloadname` varchar(100) NOT NULL COMMENT '名称',
  `time` date NOT NULL COMMENT '发布日期',
  `weigh` int(11) NOT NULL COMMENT '排序',
  `attachfile` varchar(1000) NOT NULL COMMENT '文件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_downloadcategory`
--

CREATE TABLE `__PREFIX__wwh_downloadcategory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT 'PID',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_home`
--

CREATE TABLE `__PREFIX__wwh_home` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `about_title` varchar(100) DEFAULT NULL COMMENT '关于标题',
  `introduction` text COMMENT '关于介绍',
  `title1` varchar(100) DEFAULT NULL COMMENT '标题1',
  `description1` varchar(1000) DEFAULT NULL COMMENT '描述1',
  `title2` varchar(100) DEFAULT NULL COMMENT '标题2',
  `description2` varchar(1000) DEFAULT NULL COMMENT '描述2',
  `title3` varchar(100) DEFAULT NULL COMMENT '标题3',
  `description3` varchar(1000) DEFAULT NULL COMMENT '描述3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_honor`
--

CREATE TABLE `__PREFIX__wwh_honor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `name` varchar(100) DEFAULT NULL COMMENT '证书名称',
  `image` varchar(1000) DEFAULT NULL COMMENT '证书图片',
  `sort` int(11) NOT NULL COMMENT '排序',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_market`
--

CREATE TABLE `__PREFIX__wwh_market` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `citylist` enum('浙江','江苏','安徽','山东','福建','广东','江西','北京','陕西','河北','辽宁','湖南','河南','上海','云南','四川','湖北','吉林','山西','重庆','广西','天津','内蒙古','贵州','黑龙江','海南','台湾','香港','新疆','甘肃','宁夏','青海','澳门','西藏') NOT NULL COMMENT '城市',
  `name` varchar(50) DEFAULT NULL COMMENT '联系人',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `tel` varchar(255) DEFAULT NULL COMMENT '电话',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_message`
--

CREATE TABLE `__PREFIX__wwh_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `realname` varchar(50) DEFAULT NULL COMMENT '姓名',
  `company` varchar(50) DEFAULT NULL COMMENT '公司名称',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `content` text COMMENT '留言内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_news`
--

CREATE TABLE `__PREFIX__wwh_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `newscategoryid` int(10) DEFAULT NULL COMMENT '分类ID',
  `newsname` varchar(100) DEFAULT NULL COMMENT '新闻标题',
  `n_keywords` varchar(1000) DEFAULT NULL COMMENT '关键字',
  `n_description` varchar(1000) DEFAULT NULL COMMENT '说明',
  `summary` varchar(100) DEFAULT NULL COMMENT '摘要',
  `tjdata` enum('0','1') NOT NULL COMMENT '顶部推荐:0=不显示,1=显示',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `weigh` int(11) NOT NULL COMMENT '权重',
  `time` date NOT NULL COMMENT '日期',
  `content` text COMMENT '内容',
  `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_newscategory`
--

CREATE TABLE `__PREFIX__wwh_newscategory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT 'pid',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_position`
--

CREATE TABLE `__PREFIX__wwh_position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '职位名称',
  `dept` varchar(100) NOT NULL COMMENT '部门',
  `addr` varchar(100) NOT NULL COMMENT '工作地点',
  `xueli` varchar(100) NOT NULL COMMENT '学历',
  `num` varchar(10) NOT NULL COMMENT '招聘人数',
  `time` date DEFAULT NULL COMMENT '发布日期',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_product`
--

CREATE TABLE `__PREFIX__wwh_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `productcategoryid` int(11) DEFAULT NULL COMMENT '分类ID',
  `pids` varchar(255) NOT NULL DEFAULT '0' COMMENT 'pids',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `productname` varchar(100) DEFAULT NULL COMMENT '产品名称',
  `p_keywords` varchar(1000) DEFAULT NULL COMMENT '关键字',
  `p_description` varchar(1000) DEFAULT NULL COMMENT '说明',
  `tjdata` enum('0','1') NOT NULL COMMENT '首页推荐:0=不推荐,1=推荐',
  `model` varchar(1000) DEFAULT NULL COMMENT '产品型号',
  `description` varchar(500) DEFAULT NULL COMMENT '产品描述',
  `indent_image` varchar(1000) DEFAULT NULL COMMENT '产品缩列图',
  `banner_images` varchar(1000) DEFAULT NULL COMMENT '产品轮播图',
  `index_image` varchar(1000) DEFAULT NULL COMMENT '首页推荐图',
  `gn_content` text COMMENT '功能特点',
  `zb_content` text COMMENT '技术指标',
  `size_image` varchar(1000) DEFAULT NULL COMMENT '外形尺寸',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_productcategory`
--

CREATE TABLE `__PREFIX__wwh_productcategory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT 'pid',
  `pids` varchar(255) NOT NULL DEFAULT '0' COMMENT 'pids',
  `name` varchar(50) DEFAULT NULL COMMENT '产品分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 表的结构 `__PREFIX__wwh_service`
--

CREATE TABLE `__PREFIX__wwh_service` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` text COMMENT '服务策略',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
COMMIT;

--
-- 转存表中的数据 `__PREFIX__wwh_config`
--

INSERT INTO `__PREFIX__wwh_config` (`id`, `site_name`, `keywords`, `description`, `logo`, `email`, `gongwang`, `beian`, `copyright`, `image`, `banner1`, `banner2`, `banner3`, `banner4`, `banner5`, `content`) VALUES
(1, '站点名称', '企业站点关键字', '企业站点描述', 'http://demo.wuwenhui.cn/uploads/20200909/logo.png', 'wwh@admin.com', '浙公网安备XXXXXXXXXXXXXX号', '浙ICP备XXXXXXXX号', 'Copyright © 2001-2020 XXXXXXXX公司 版权所有', 'http://demo.wuwenhui.cn/uploads/20200909/01ffee79c617d9296476eb7119fae45f.jpg', 'http://demo.wuwenhui.cn/uploads/20200911/860ef63de4af1dad13dcdd031a7cd4d5.jpg', 'http://demo.wuwenhui.cn/uploads/20200911/e8361c327543f91628b22de0754c1c6d.jpg', 'http://demo.wuwenhui.cn/uploads/20200911/23ba732cd0974f4957a50d37b6345894.jpg', 'http://demo.wuwenhui.cn/uploads/20200911/574618243ffbcdb37fa699b9dbf448a8.jpg', 'http://demo.wuwenhui.cn/uploads/20200911/a2db50ac891ba18017285b12956fcda0.jpg', '<div class=\"items-box\">\r\n          <div class=\"tt\">关于我们<span></span></div>\r\n          <div class=\"items\">\r\n            <a href=\"#\" class=\"item\">公司概况</a>\r\n            <a href=\"#\" class=\"item\">荣誉资质</a>\r\n            <a href=\"#\" class=\"item\">加入我们</a>\r\n            <a href=\"#\" class=\"item\">联系我们</a>\r\n          </div>\r\n        </div>\r\n        <div class=\"items-box\">\r\n          <div class=\"tt\">新闻中心<span></span></div>\r\n          <div class=\"items\">\r\n            <a href=\"#\" class=\"item\">互联网</a>\r\n            <a href=\"#\" class=\"item\">行业资讯</a>\r\n            <a href=\"#\" class=\"item\">5G频道</a>\r\n          </div>\r\n        </div>\r\n        <div class=\"items-box\">\r\n          <div class=\"tt\">服务中心<span></span></div>\r\n          <div class=\"items\">\r\n            <a href=\"#\" class=\"item\">服务策略</a>\r\n            <a href=\"#\" class=\"item\">营销网络</a>\r\n          </div>\r\n        </div>\r\n        <div class=\"items-box\">\r\n          <div class=\"tt\">常用链接<span></span></div>\r\n          <div class=\"items\">\r\n            <a href=\"#\" class=\"item\">产品中心</a>\r\n            <a href=\"#\" class=\"item\">解决方案</a>\r\n            <a href=\"#\" class=\"item\">客户留言</a>\r\n          </div>\r\n        </div>\r\n        <div class=\"items-box\">\r\n          <div class=\"tt\">联系我们<span></span></div>\r\n          <div class=\"items\">\r\n            <a href=\"javascript:;\" class=\"item\">0571-88888888</a>\r\n            <a href=\"javascript:;\" class=\"item\">support@admin.com</a>\r\n            <a href=\"javascript:;\" class=\"item\">杭州市滨江区江汉路1515号</a>\r\n          </div>\r\n        </div>');

--
-- 转存表中的数据 `__PREFIX__wwh_home`
--

INSERT INTO `__PREFIX__wwh_home` (`id`, `about_title`, `introduction`, `title1`, `description1`, `title2`, `description2`, `title3`, `description3`) VALUES
(1, '关于我们', '这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字，这是一段演示文字！', '2006', '<p>公司正式成立</p><p>XXXXXXXX有限公司</p>', '50', '<p>全国行业50强企业</p><p>连续5年入选，2019年度排名26位</p>', '23', '<p>23个省级行政区</p><p>全国售后服务体系覆盖范围</p>');
COMMIT;

--
-- 1.0.3
-- 旧版本修复表结构，解决方案添加描述
--
ALTER TABLE `__PREFIX__wwh_cases` ADD COLUMN `description` varchar(255) DEFAULT NULL COMMENT '描述' AFTER `time`;

--
-- 1.0.5
-- 旧版本修复表结构，产品分类及产品内容增加pids
--
ALTER TABLE `__PREFIX__wwh_product` ADD `pids` VARCHAR(255) NOT NULL DEFAULT '0' COMMENT 'pids' AFTER `productcategoryid`;
ALTER TABLE `__PREFIX__wwh_productcategory` ADD `pids` VARCHAR(255) NOT NULL DEFAULT '0' COMMENT 'pids' AFTER `pid`;

--
-- 1.0.6
-- 旧版本修复表结构，新增关键字及说明
--
ALTER TABLE `__PREFIX__wwh_config` ADD `logo` VARCHAR(1000) NULL COMMENT 'logo' AFTER `description`;
ALTER TABLE `__PREFIX__wwh_cases` ADD `c_keywords` VARCHAR(1000) NULL COMMENT '关键字' AFTER `casesname`;
ALTER TABLE `__PREFIX__wwh_cases` ADD `c_description` VARCHAR(1000) NULL COMMENT '说明' AFTER `c_keywords`;
ALTER TABLE `__PREFIX__wwh_news` ADD `n_keywords` VARCHAR(1000) NULL COMMENT '关键字' AFTER `newsname`;
ALTER TABLE `__PREFIX__wwh_news` ADD `n_description` VARCHAR(1000) NULL COMMENT '说明' AFTER `n_keywords`;
ALTER TABLE `__PREFIX__wwh_product` ADD `p_keywords` VARCHAR(1000) NULL COMMENT '关键字' AFTER `productname`;
ALTER TABLE `__PREFIX__wwh_product` ADD `p_description` VARCHAR(1000) NULL COMMENT '说明' AFTER `p_keywords`;
