-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-03-24 09:02:24
-- 服务器版本： 10.1.26-MariaDB-0+deb9u1
-- PHP Version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `permission` varchar(50) NOT NULL,
  `about` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限解释说明表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '0删除 1正常'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `admin_role_permissions`
--

CREATE TABLE `admin_role_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user` varchar(50) NOT NULL COMMENT '用户名',
  `pass` varchar(50) NOT NULL COMMENT '密码',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '绑定前台用户',
  `isroot` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否是超级管理员',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '状态 0删除 1待审核 2正常'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_user_permissions`
--

CREATE TABLE `admin_user_permissions` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户特有权限表';

-- --------------------------------------------------------

--
-- 表的结构 `admin_user_roles`
--

CREATE TABLE `admin_user_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户角色关联表';

-- --------------------------------------------------------

--
-- 表的结构 `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '作者id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(1000) NOT NULL COMMENT '简介',
  `content` mediumblob NOT NULL COMMENT '内容',
  `html` mediumblob NOT NULL COMMENT '正文html',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `edittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '状态[1草稿][2审核][3正式][4下线]',
  `locked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `top` tinyint(3) UNSIGNED NOT NULL COMMENT '是否置顶',
  `_tags` tinyblob NOT NULL COMMENT '缓存:文章标签',
  `_comments_num` int(11) NOT NULL DEFAULT '0' COMMENT '缓存:评论数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `article_comments`
-- (See below for the actual view)
--
CREATE TABLE `article_comments` (
`id` int(11)
,`article_id` int(11) unsigned
,`comment_id` int(11) unsigned
,`user_id` int(11) unsigned
,`content` mediumtext
,`addtime` timestamp
,`status` tinyint(4)
,`_replys_id` tinyblob
);

-- --------------------------------------------------------

--
-- 表的结构 `article_comments_0`
--

CREATE TABLE `article_comments_0` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]',
  `_replys_id` tinyblob NOT NULL COMMENT '第1页回复的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_comments_1`
--

CREATE TABLE `article_comments_1` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]',
  `_replys_id` tinyblob NOT NULL COMMENT '第1页回复的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_comments_2`
--

CREATE TABLE `article_comments_2` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]',
  `_replys_id` tinyblob NOT NULL COMMENT '第1页回复的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_comments_id`
--

CREATE TABLE `article_comments_id` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `article_replys`
-- (See below for the actual view)
--
CREATE TABLE `article_replys` (
`id` int(11)
,`article_id` int(11) unsigned
,`comment_id` int(11) unsigned
,`reply_id` int(11) unsigned
,`user_id` int(11) unsigned
,`content` mediumtext
,`addtime` timestamp
,`status` tinyint(4)
);

-- --------------------------------------------------------

--
-- 表的结构 `article_replys_0`
--

CREATE TABLE `article_replys_0` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL COMMENT '文章id',
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `reply_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复目标',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_replys_1`
--

CREATE TABLE `article_replys_1` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL COMMENT '文章id',
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `reply_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复目标',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_replys_2`
--

CREATE TABLE `article_replys_2` (
  `id` int(11) NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL COMMENT '文章id',
  `comment_id` int(10) UNSIGNED NOT NULL COMMENT '评论id',
  `reply_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复目标',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态[0 未审][1 正常][2 屏蔽]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章评论表(以文章分表)';

-- --------------------------------------------------------

--
-- 表的结构 `article_replys_id`
--

CREATE TABLE `article_replys_id` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `article_tags`
--

CREATE TABLE `article_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `security_log`
--

CREATE TABLE `security_log` (
  `id` int(11) NOT NULL,
  `ip` varbinary(40) NOT NULL COMMENT 'ip址址',
  `type` varbinary(50) NOT NULL COMMENT '日志类型',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='安全日志表';

-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `sort` int(10) UNSIGNED NOT NULL,
  `top` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否显示在首页',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT ' 状态[1 正常][2 屏蔽] '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='标签表';

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL COMMENT '呢称',
  `status` tinyint(4) NOT NULL COMMENT '状态[1 正常][0 屏蔽]',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_notice`
--

CREATE TABLE `user_notice` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `num` int(10) UNSIGNED NOT NULL,
  `data` mediumblob NOT NULL,
  `version` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户通知表(读后即丢)';

-- --------------------------------------------------------

--
-- 表的结构 `user_records_0`
--

CREATE TABLE `user_records_0` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `to_id` varbinary(50) NOT NULL,
  `_addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户记录表(以用户分表)';

-- --------------------------------------------------------

--
-- 表的结构 `user_records_1`
--

CREATE TABLE `user_records_1` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `to_id` varbinary(50) NOT NULL,
  `_addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户记录表(以用户分表)';

-- --------------------------------------------------------

--
-- 表的结构 `user_records_2`
--

CREATE TABLE `user_records_2` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `to_id` varbinary(50) NOT NULL,
  `_addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户记录表(以用户分表)';

-- --------------------------------------------------------

--
-- 表的结构 `user_records_id`
--

CREATE TABLE `user_records_id` (
  `id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 视图结构 `article_comments`
--
DROP TABLE IF EXISTS `article_comments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `article_comments`  AS  (select `article_comments_0`.`id` AS `id`,`article_comments_0`.`article_id` AS `article_id`,`article_comments_0`.`comment_id` AS `comment_id`,`article_comments_0`.`user_id` AS `user_id`,`article_comments_0`.`content` AS `content`,`article_comments_0`.`addtime` AS `addtime`,`article_comments_0`.`status` AS `status`,`article_comments_0`.`_replys_id` AS `_replys_id` from `article_comments_0`) union all (select `article_comments_1`.`id` AS `id`,`article_comments_1`.`article_id` AS `article_id`,`article_comments_1`.`comment_id` AS `comment_id`,`article_comments_1`.`user_id` AS `user_id`,`article_comments_1`.`content` AS `content`,`article_comments_1`.`addtime` AS `addtime`,`article_comments_1`.`status` AS `status`,`article_comments_1`.`_replys_id` AS `_replys_id` from `article_comments_1`) union all (select `article_comments_2`.`id` AS `id`,`article_comments_2`.`article_id` AS `article_id`,`article_comments_2`.`comment_id` AS `comment_id`,`article_comments_2`.`user_id` AS `user_id`,`article_comments_2`.`content` AS `content`,`article_comments_2`.`addtime` AS `addtime`,`article_comments_2`.`status` AS `status`,`article_comments_2`.`_replys_id` AS `_replys_id` from `article_comments_2`) ;

-- --------------------------------------------------------

--
-- 视图结构 `article_replys`
--
DROP TABLE IF EXISTS `article_replys`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `article_replys`  AS  (select `article_replys_0`.`id` AS `id`,`article_replys_0`.`article_id` AS `article_id`,`article_replys_0`.`comment_id` AS `comment_id`,`article_replys_0`.`reply_id` AS `reply_id`,`article_replys_0`.`user_id` AS `user_id`,`article_replys_0`.`content` AS `content`,`article_replys_0`.`addtime` AS `addtime`,`article_replys_0`.`status` AS `status` from `article_replys_0`) union all (select `article_replys_1`.`id` AS `id`,`article_replys_1`.`article_id` AS `article_id`,`article_replys_1`.`comment_id` AS `comment_id`,`article_replys_1`.`reply_id` AS `reply_id`,`article_replys_1`.`user_id` AS `user_id`,`article_replys_1`.`content` AS `content`,`article_replys_1`.`addtime` AS `addtime`,`article_replys_1`.`status` AS `status` from `article_replys_1`) union all (select `article_replys_2`.`id` AS `id`,`article_replys_2`.`article_id` AS `article_id`,`article_replys_2`.`comment_id` AS `comment_id`,`article_replys_2`.`reply_id` AS `reply_id`,`article_replys_2`.`user_id` AS `user_id`,`article_replys_2`.`content` AS `content`,`article_replys_2`.`addtime` AS `addtime`,`article_replys_2`.`status` AS `status` from `article_replys_2`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`permission`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `admin_user_permissions`
--
ALTER TABLE `admin_user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_user_roles`
--
ALTER TABLE `admin_user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_comments_0`
--
ALTER TABLE `article_comments_0`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_comments_1`
--
ALTER TABLE `article_comments_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_comments_2`
--
ALTER TABLE `article_comments_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_comments_id`
--
ALTER TABLE `article_comments_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_replys_0`
--
ALTER TABLE `article_replys_0`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_replys_1`
--
ALTER TABLE `article_replys_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_replys_2`
--
ALTER TABLE `article_replys_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_replys_id`
--
ALTER TABLE `article_replys_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_tags`
--
ALTER TABLE `article_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_log`
--
ALTER TABLE `security_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `user_notice`
--
ALTER TABLE `user_notice`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_records_0`
--
ALTER TABLE `user_records_0`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_records_1`
--
ALTER TABLE `user_records_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_records_2`
--
ALTER TABLE `user_records_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_records_id`
--
ALTER TABLE `user_records_id`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- 使用表AUTO_INCREMENT `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `admin_user_permissions`
--
ALTER TABLE `admin_user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `admin_user_roles`
--
ALTER TABLE `admin_user_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- 使用表AUTO_INCREMENT `article_comments_id`
--
ALTER TABLE `article_comments_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- 使用表AUTO_INCREMENT `article_replys_id`
--
ALTER TABLE `article_replys_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `article_tags`
--
ALTER TABLE `article_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

--
-- 使用表AUTO_INCREMENT `security_log`
--
ALTER TABLE `security_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `user_records_id`
--
ALTER TABLE `user_records_id`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
