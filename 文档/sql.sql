

-- `hr_article_category` 文章分类表

CREATE TABLE IF NOT EXISTS `hr_article_category` (
   `catid`      int(10)          NOT NULL AUTO_INCREMENT  comment '自增ID',
   `cat_name`   varchar(120)     not null default ''      comment '分类名称',
   `parent_id`  int(10)          not null default '0'     comment '所属父id',
   `type`       tinyint(1)       not null default '0'     comment '0，多内容；1单页',
   `thumb`      varchar(40)      not null default ''      comment '缩略图',
   `status`     tinyint(1)       not null default '1'     comment '信息状态，1正常，0异常',
   `createtime` int(10) unsigned not null default '0'     comment '信息录入时间',
   PRIMARY KEY (`catid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章分类表';



-- hr_article 文章主表

CREATE TABLE IF NOT EXISTS `hr_article` (
   `article_id`     int(10)          NOT NULL AUTO_INCREMENT  comment '自增ID',
   `article_title`  varchar(120)     not null default ''      comment '文章标题',
   `article_matter` varchar(255)     not null default ''      comment '内容简介',
   `catid`          int(10)          not null default '0'     comment '所属分类id',
   `thumb`          varchar(40)      not null default ''      comment '缩略图',
   `toptime`        int(10) unsigned not null default '0'     comment '信息置顶时间',
   `click`          int(10) unsigned not null default '0'     comment '浏览次数',
   `writer`         varchar(30)      not null default ''      comment '作者',
   `source`         varchar(30)      not null default ''      comment '来源',
   `link_url`       varchar(255)     not null default ''      comment '外链地址',

   `updater`        int(10)          not null default '0'     comment '信息修改人员id',
   `updatetime`     int(10) unsigned not null default '0'     comment '信息录入时间',
   `status`         tinyint(1)       not null default '1'     comment '信息状态，1正常，0异常',
   `createtime`     int(10) unsigned not null default '0'     comment '信息录入时间',
   PRIMARY KEY (`article_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章主表';



-- hr_article_body  文章内容表

CREATE TABLE IF NOT EXISTS `hr_article_body` (
   `article_id`      int(10)  NOT NULL default '0' comment '文章主表主键',
   `article_body`    text     not null             comment '正文内容',
    KEY `article_id` (`article_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章详细内容';


-- `hr_case_category` 案例分类表

CREATE TABLE IF NOT EXISTS `hr_case_category` (
   `catid`      int(10)          NOT NULL AUTO_INCREMENT  comment '自增ID',
   `cat_name`   varchar(120)     not null default ''      comment '分类名称',
   `parent_id`  int(10)          not null default '0'     comment '所属父id',
   `type`       tinyint(1)       not null default '0'     comment '0，多内容；1单页',
   `thumb`      varchar(40)      not null default ''      comment '缩略图',
   `status`     tinyint(1)       not null default '1'     comment '信息状态，1正常，0异常',
   `createtime` int(10) unsigned not null default '0'     comment '信息录入时间',
   PRIMARY KEY (`catid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='案例分类表';



-- hr_case 案例主表

CREATE TABLE IF NOT EXISTS `hr_case` (
   `case_id`     int(10)          NOT NULL AUTO_INCREMENT  comment '自增ID',
   `case_title`  varchar(120)     not null default ''      comment '项目介绍、标题',
   `case_matter` varchar(255)     not null default ''      comment '内容简介',
   `catid`          int(10)          not null default '0'     comment '所属分类id',
   `thumb`          varchar(40)      not null default ''      comment '缩略图',
   `toptime`        int(10) unsigned not null default '0'     comment '信息置顶时间',
   `click`          int(10) unsigned not null default '0'     comment '浏览次数',
   `writer`         varchar(30)      not null default ''      comment '作者',
   `source`         varchar(30)      not null default ''      comment '来源',
   `link_url`       varchar(255)     not null default ''      comment '外链地址',

   `updater`        int(10)          not null default '0'     comment '信息修改人员id',
   `updatetime`     int(10) unsigned not null default '0'     comment '信息录入时间',
   `status`         tinyint(1)       not null default '1'     comment '信息状态，1正常，0异常',
   `createtime`     int(10) unsigned not null default '0'     comment '信息录入时间',
   PRIMARY KEY (`case_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='案例主表';


-- `hr_case_body`  案例内容表

CREATE TABLE IF NOT EXISTS `hr_case_body` (
   `case_id`      int(10)  NOT NULL default '0' comment '案例主表主键',
   `case_body`    text     not null             comment '正文内容',
    KEY `case_id` (`case_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='案例内容表';


-- `hr_case_flag`  案例标签表

CREATE TABLE IF NOT EXISTS `hr_case_flag` (
   `case_id`      int(10)  NOT NULL default '0' comment '案例主表主键',
   `flag_name`    varchar(30) not null default '' comment '标签名',
    KEY `case_id` (`case_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='案例标签表';



--
-- 表的结构 `hr_admin`
--

CREATE TABLE IF NOT EXISTS `hr_admin` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户登录名',
  `realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '随机码',
  `work_number` varchar(30) NOT NULL DEFAULT '' COMMENT '工号',
  `national` varchar(50) NOT NULL DEFAULT '' COMMENT '民族',
  `card_id` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
  `jiguan` varchar(50) NOT NULL DEFAULT '' COMMENT '籍贯',
  `xueli` varchar(20) NOT NULL DEFAULT '' COMMENT '学历',
  `marriage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已婚,2未婚,0保密',
  `professional` varchar(40) NOT NULL DEFAULT '' COMMENT '专业',
  `graduated` varchar(50) NOT NULL DEFAULT '' COMMENT '毕业学校',
  `begin_work_time` int(10) NOT NULL DEFAULT '0' COMMENT '参加工作时间',
  `join_work_time` int(10) NOT NULL DEFAULT '0' COMMENT '加入本单位时间',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '部门id',
  `work_id` int(10) NOT NULL DEFAULT '0' COMMENT '岗位id',
  `work_name` varchar(50) NOT NULL DEFAULT '' COMMENT '岗位名称',
  `political` varchar(20) NOT NULL DEFAULT '' COMMENT '政治面貌',
  `zhicheng` varchar(20) NOT NULL DEFAULT '' COMMENT '职称',
  `home_address` varchar(255) NOT NULL DEFAULT '' COMMENT '家庭住址',
  `home_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '家庭电话',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '电子邮件',
  `photo` varchar(40) NOT NULL DEFAULT '' COMMENT '头像',
  `login_num` int(10) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `logintime` int(10) NOT NULL DEFAULT '0' COMMENT '最近一次登录时间',
  `loginip` varchar(15) NOT NULL DEFAULT '' COMMENT '最近登录的IP地址',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '家庭住址',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未知,1男,2女',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `office_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '办公联系电话',
  `community_id` int(10) NOT NULL DEFAULT '0' COMMENT '社区id',
  PRIMARY KEY (`userid`),
  KEY `department_id` (`department_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员基础表';

--
-- 转存表中的数据 `hr_user`
--

INSERT INTO `hr_admin` (`userid`, `username`, `realname`, `password`, `salt`, `work_number`, `national`, `card_id`, `jiguan`, `xueli`, `marriage`, `professional`, `graduated`, `begin_work_time`, `join_work_time`, `department_id`, `work_id`, `work_name`, `political`, `zhicheng`, `home_address`, `home_mobile`, `email`, `photo`, `login_num`, `status`, `createtime`, `logintime`, `loginip`, `remark`, `sex`, `mobile`, `office_tel`, `community_id`) VALUES
(1, 'admin', '张三', '9593f25527c1edd825c94b1adcc87bc4', 'k3iisb', '33', '', '210281198210104313', '', '', 0, '', '', -28800, -28800, 3, 0, '管理员', '', '高级管理员', '大连市中山区', '999', '', '38e893bd76e49a1ec476b4156b7a1c71.jpg', 44, 1, 0, 1492065961, '0.0.0.0', '', 1, '000', '', 0);






