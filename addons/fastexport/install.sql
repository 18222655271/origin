
-- ---------------------------
-- 数据导出任务表
-- ---------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__fastexport` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导出人',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '任务名称',
  `main_table` varchar(50) NOT NULL DEFAULT '' COMMENT '数据源表',
  `field_config` text COMMENT '字段配置',
  `join_table` text COMMENT '关联表配置',
  `where_field` text COMMENT '筛选规则',
  `order_field` varchar(50) NOT NULL DEFAULT '' COMMENT '排序字段',
  `order_type` varchar(4) NOT NULL DEFAULT '' COMMENT '排序方式',
  `xls_max_number` int(7) unsigned NOT NULL DEFAULT '10000' COMMENT '单个xls最大记录数',
  `xls_create_concurrent` tinyint(3) unsigned NOT NULL DEFAULT '3' COMMENT 'xls创建并发',
  `memory_limit` decimal(8,0) unsigned NOT NULL DEFAULT '128' COMMENT '脚本内存限制(Mb)',
  `export_number` int(10) unsigned DEFAULT NULL COMMENT '导出记录数',
  `subtask` text COMMENT '子任务资料',
  `progress` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '导出进度',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '文件包',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据导出表';