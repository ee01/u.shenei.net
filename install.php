<?php
/*
	安装程序 执行完请删除
	author:冯超养
	website:http://xekee.com
	description:本插件业余时间完成 可能还存在不足和BUG 希望大家一起完善他
*/
include_once('./common.php');
require_once './source/function_common.php';
include_once(S_ROOT.'./source/function_cp.php');
if(function_exists('xcache_set'))
{
	include_once(S_ROOT . '/source/class.cache_xcache.php');
}
else
{
	include_once(S_ROOT . '/source/class_cache.php');
}

$rs1 = $_SGLOBAL['db']->query("CREATE TABLE IF NOT EXISTS `sn_app_card` (
  `id` int(8) NOT NULL auto_increment,
  `cardnum` varchar(12) NOT NULL,
  `cardpsw` varchar(12) NOT NULL,
  `carduser` varchar(10) NOT NULL,
  `cardusername` varchar(50) NOT NULL,
  `money` varchar(8) NOT NULL,
  `ztinfo` varchar(4) NOT NULL,
  `overtime` varchar(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

$rs2 = $_SGLOBAL['db']->query("CREATE TABLE `sn_exchange_orderform` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `gid` int(10) NOT NULL,
	`keyid` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `data` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

$rs3 = $_SGLOBAL['db']->query("CREATE TABLE `sn_exchange_gifts` (
  `id` int(10) NOT NULL auto_increment,
  `title` char(255) NOT NULL,
  `body` text NOT NULL,  
  `price` int(10) NOT NULL,
  `num` int(10) NOT NULL,
  `sale` int(10) NOT NULL,
  `pic` char(255) NOT NULL,
  `picflag` tinyint(1) NOT NULL,
  `rank` int(10) NOT NULL,
  `ac` tinyint(1) NOT NULL,
  `dateline` int(10) NOT NULL,
  `type` int(10) NOT NULL,
	`url` char(255) NOT NULL,
  `begin` int(10) NOT NULL,
  `expiration` int(10) NOT NULL,
	`autokey` tinyint(1) NOT NULL,	
	`buynum` int(10) NOT NULL,
	`needregtime` int(10) NOT NULL,
	`robnumber` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

$rs4 = $_SGLOBAL['db']->query("CREATE TABLE `sn_exchange_keys` (
  `keyid` int(10) NOT NULL auto_increment,
  `gifts_id` int(10) NOT NULL,
  `keys` varchar(255) NOT NULL,
  `keyuid` int(10) NOT NULL,
  `keyac` tinyint(1) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY  (`keyid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

$rs5 = $_SGLOBAL['db']->query("CREATE TABLE `sn_exchange_user` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `name` varchar(50)  NOT NULL,
  `city` varchar(200)  NOT NULL,
  `state` varchar(200)  NOT NULL,
  `street` varchar(200)  NOT NULL,
  `country` varchar(100)  NOT NULL,
  `address` varchar(200)  NOT NULL,
  `zipcode` varchar(100)  NOT NULL,
  `phone` varchar(100)  NOT NULL,
  `mobile` varchar(100)  NOT NULL,
  `qq` varchar(100)  NOT NULL,
  `skype` varchar(100) NOT NULL,
  `msn` varchar(100) NOT NULL,
	`email` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

$rs6 = $_SGLOBAL['db']->query("CREATE TABLE `sn_exchange_class` (
  `classid` int(10) NOT NULL auto_increment,
  `classname` varchar(100) NOT NULL,
  PRIMARY KEY  (`classid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;");

if($rs1 && $rs2 && $rs3 && $rs4 && $rs5 && $rs6)
{
	echo '恭喜你，安装成功';

//删除安装文件
@unlink( "card_install.php" );
}
else
{
	echo '安装失败，请检查权限';
}
?>