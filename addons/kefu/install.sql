-- ----------------------------
-- 配置表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '变量名',
  `value` text COMMENT '变量值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服配置表';

-- ----------------------------
-- 插入配置项
-- ----------------------------
BEGIN;
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('1', 'chat_name', '在线客服');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('2', 'ecs_exit', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('3', 'send_message_key', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('4', 'new_user_tip', '您准备好体验在线客服系统了吗？');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('5', 'new_user_msg', '这是一个欢迎新用户的消息,系统为用户成功分配客服后,自动以该客服身份发送此消息~单客服的欢迎消息请于：客服管理-》客服代表管理进行设置');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('6', 'csr_distribution', '2');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('7', 'announcement', '这是一条公告！你可以在后台管理->客服管理->会话窗口中进行更换公告内容！');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('8', 'slider_images', '/assets/addons/kefu/img/slider1.jpg,/assets/addons/kefu/img/slider2.jpg');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('9', 'chat_introduces', '<p style=\"line-height: 1.6;\">\r\n  <b><span style=\"font-size: 16px;\">功能简介</span></b><br>\r\n</p>\r\n<p>\r\n <b>模块化开发</b><br>\r\n  强大的一键生成功能极速简化你的开发流程,加快你的项目开发\r\n</p>\r\n<p>\r\n <b>响应式布局</b><br>\r\n  自动适配,无需要担心兼容性问题\r\n</p>\r\n<p>\r\n  <b>完善的权限管理</b><br>\r\n  自由分配子级权限、一个管理员可同时属于多个组别\r\n</p>\r\n<p>\r\n  <b>通用的会员和API模块</b><br>\r\n  共用同一账号体系的Web端会员中心权限验证和API接口会员权限验证\r\n</p>\r\n<p>\r\n  <b>丰富的应用市场</b><br>\r\n  第三方云存储、云短信、富文本编辑器、CMS、博客、文档生成，一切均可在线安装卸载\r\n</p>\r\n<p></p>');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('10', 'auto_invitation_switch', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('11', 'auto_invitation_timing', '7');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('12', 'invite_box_img', '/assets/addons/kefu/img/invite_box_img.jpg');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('13', 'csr_admin', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('14', 'trajectory_save_cycle', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('15', 'wechat_app_id', '');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('16', 'wechat_app_secret', '');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('17', 'wechat_token', '');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('18', 'wechat_encodingkey', '');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('19', 'new_message_notice', '');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('20', 'only_first_invitation', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('21', 'new_message_shake', '3');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('22', 'only_csr_online_invitation', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('23', 'kbs_switch', '1');
INSERT IGNORE INTO `__PREFIX__kefu_config` VALUES ('24', 'input_status_display', '2');
COMMIT;

-- ----------------------------
-- 客服代表配置表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_csr_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定管理员',
  `ceiling` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '接待上限',
  `reception_count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '当前接待量',
  `last_reception_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次接待时间',
  `keep_alive` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否保持在线',
  `welcome_msg` text COMMENT '欢迎语',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=离线,1=繁忙,2=离开,3=在线',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服代表(csr)配置表';

-- ----------------------------
-- 插入客服代表
-- ----------------------------
BEGIN;
INSERT IGNORE INTO `__PREFIX__kefu_csr_config` VALUES ('1', '1', '8', '1', '1567046865', '0', '欢迎访问！', '0');
COMMIT;

-- ----------------------------
-- 用户留言表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_leave_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户(KeFu用户ID)',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `contact` varchar(50) NOT NULL DEFAULT '' COMMENT '联系方式',
  `message` text COMMENT '留言内容',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='用户留言记录';

-- ----------------------------
-- 客服接待记录
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_reception_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `csr_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '客服代表ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户(KeFu用户ID)',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '接待时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服接待记录';

-- ----------------------------
-- 聊天记录表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `session_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会话ID',
  `sender_identity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发送人身份:0=客服,1=用户',
  `sender_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送人ID',
  `message_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型:0=富文本,1=图片,2=文件,3=系统消息,4=商品卡片,5=订单卡片',
  `message` text COMMENT '消息',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=未读,1=已读',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='聊天记录表';

-- ----------------------------
-- 会话表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_session` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `csr_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客服代表ID',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `deletetime` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服会话表';

-- ----------------------------
-- 用户轨迹表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_trajectory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'KeFu用户',
  `csr_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客服代表',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '轨迹类型:0=访问,1=被邀请,2=开始对话,3=拒绝会话,4=客服添加,5=关闭页面,6=留言,7=其他',
  `note` text COMMENT '轨迹详情',
  `url` text COMMENT '轨迹额外数据',
  `referrer` text COMMENT '来路',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='用户轨迹表';

-- ----------------------------
-- 插件用户表
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对应用户ID',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `referrer` varchar(255) NOT NULL DEFAULT '' COMMENT '用户来路',
  `contact` varchar(100) NOT NULL DEFAULT '' COMMENT '联系方式',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '客服备注',
  `token` varchar(59) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `wechat_openid` varchar(28) NOT NULL DEFAULT '' COMMENT '微信openid',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服用户表';

-- ---------------------------
-- 黑名单表
-- ---------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被屏蔽人(KeFu用户ID)',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作客服',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='客服黑名单表';

-- ---------------------------
-- 快捷回复表
-- ---------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_fast_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属客服',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '回复内容',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '状态:0=关闭,1=启用',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `deletetime` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='快捷回复表';

-- ----------------------------
-- 插入通用快捷回复
-- ----------------------------
BEGIN;
INSERT IGNORE INTO `__PREFIX__kefu_fast_reply` VALUES ('1', '0', '打招呼', '您好，请问有什么可以帮您？', '1', '1567332795', null);
INSERT IGNORE INTO `__PREFIX__kefu_fast_reply` VALUES ('2', '0', '询问联系方式', '您可以提供下您的联系方式么？您的电话是？或者QQ，我们可以更方便的联系您！', '1', '1567338640', null);
INSERT IGNORE INTO `__PREFIX__kefu_fast_reply` VALUES ('3', '0', '提示客户等待-处理中', '请稍等片刻，我们正在为您处理！', '1', '1567338672', null);
INSERT IGNORE INTO `__PREFIX__kefu_fast_reply` VALUES ('4', '0', '提示客户等待-问', '我去问一下，您稍等片刻~', '1', '1567338693', null);
INSERT IGNORE INTO `__PREFIX__kefu_fast_reply` VALUES ('5', '0', '道别', '那好，祝您生活愉快，再见！', '1', '1567338716', null);
COMMIT;

-- ---------------------------
-- 知识库表
-- ---------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_kbs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `questions` text COMMENT '知识点',
  `match` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '自动回复匹配度',
  `answer` text COMMENT '问题答案',
  `admin_id` varchar(100) NOT NULL DEFAULT '' COMMENT '限定客服生效',
  `status` enum('2','1','0') NOT NULL DEFAULT '0' COMMENT '状态:0=关闭,1=启用,2=启用为万能知识',
  `weigh` int(10) NOT NULL DEFAULT '1' COMMENT '权重',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `deletetime` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='知识库表';

-- ----------------------------
-- 插入知识点
-- ----------------------------
INSERT IGNORE INTO `__PREFIX__kefu_kbs` VALUES ('1', '万能知识', '100', '<p>我是来自知识库的万能知识~</p><p>我不计算匹配度，只要没有任何知识点被匹配到，且没被“限定客服生效”所限定，就会回复我了~</p><p><b><span style="font-size: 12px;">万能知识常用于限定客服才生效，若不需要请直接从知识库删除。</span></b></p>', '1', '2', '1', '1574075097', null);
INSERT IGNORE INTO `__PREFIX__kefu_kbs` VALUES ('2', '你好\r\n您好\r\n在吗\r\n在？\r\n在', '80', '<p>亲，在的呢~</p><p>这是一条来自知识库的自动回复~</p>', '', '1', '2', '1574072922', null);

-- ---------------------------
-- 窗口工具栏表
-- ---------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kefu_toolbar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `position` enum('frontend','backend','general') NOT NULL DEFAULT 'backend' COMMENT '工具位置:backend=后台,frontend=前台,general=通用',
  `mark` varchar(20) NOT NULL DEFAULT '' COMMENT '唯一标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '标题',
  `icon_image` varchar(200) NOT NULL DEFAULT '' COMMENT '图标',
  `data_api` varchar(200) NOT NULL DEFAULT '' COMMENT '数据接口Url',
  `card_url` varchar(200) NOT NULL DEFAULT '' COMMENT '消息卡片Url',
  `card_frontend_url` varchar(200) NOT NULL DEFAULT '' COMMENT '消息卡片Url(uni端)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=隐藏,1=正常',
  `deletetime` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='窗口工具栏表';

-- ----------------------------
-- 插入预设工具
-- ----------------------------
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('6', 'general', 'expression', '发送表情', '/assets/addons/kefu/img/smiley.png', '', '', '', '1', null);
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('5', 'general', 'file', '发送文件', '/assets/addons/kefu/img/attachment.png', '', '', '', '1', null);
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('4', 'general', 'link', '发送链接', '/assets/addons/kefu/img/link.png', '', '', '', '1', null);
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('3', 'backend', 'fastreply', '快捷回复', '/assets/addons/kefu/img/fastreply.png', '', '', '', '1', null);
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('2', 'frontend', 'goods', '发送商品', '/assets/addons/kefu/img/goods.png', '/api/Kefu/goodsList', '/bISTVBHhuo.php/user/user', '', '0', null);
INSERT IGNORE INTO `__PREFIX__kefu_toolbar` VALUES ('1', 'frontend', 'order', '发送订单', '/assets/addons/kefu/img/order.png', '/api/Kefu/orderList', '/bISTVBHhuo.php/kefu/csrkpi', '', '0', null);

-- ----------------------------
-- 旧版本字段处理
-- ----------------------------
BEGIN;
ALTER TABLE `__PREFIX__kefu_blacklist` ADD COLUMN `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作客服' AFTER `user_id`;
COMMIT;

BEGIN;
ALTER TABLE `__PREFIX__kefu_csr_config` ADD COLUMN `keep_alive` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否保持在线' AFTER `last_reception_time`;
COMMIT;

BEGIN;
ALTER TABLE `__PREFIX__kefu_user` ADD COLUMN `referrer` varchar(255) NOT NULL DEFAULT '' COMMENT '用户来路' AFTER `nickname`;
ALTER TABLE `__PREFIX__kefu_user` ADD COLUMN `contact` varchar(100) NOT NULL DEFAULT '' COMMENT '联系方式' AFTER `referrer`;
ALTER TABLE `__PREFIX__kefu_user` ADD COLUMN `note` varchar(255) NOT NULL DEFAULT '' COMMENT '客服备注' AFTER `contact`;
COMMIT;

BEGIN;
ALTER TABLE `__PREFIX__kefu_record` MODIFY message_type tinyint(1) comment '消息类型:0=富文本,1=图片,2=文件,3=系统消息,4=商品卡片,5=订单卡片';
COMMIT;

BEGIN;
ALTER TABLE `__PREFIX__kefu_csr_config` ADD COLUMN `welcome_msg` text COMMENT '欢迎语' AFTER `keep_alive`;
COMMIT;

BEGIN;
ALTER TABLE `__PREFIX__kefu_blacklist` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_config` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_csr_config` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_fast_reply` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_kbs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_leave_message` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_reception_log` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_record` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_session` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_toolbar` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_trajectory` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
ALTER TABLE `__PREFIX__kefu_user` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
COMMIT;