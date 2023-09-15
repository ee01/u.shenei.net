<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: index.php 13003 2009-08-05 06:46:06Z liguode $
*/

include_once('./common.php');

//Add By 01↓
include_once 'DB.class.php';

$db = new DBAccess();

$sql = "select message from sn_doing order by doid desc LIMIT 0,10";
$list = $db->fetch_all($sql);

foreach ($list as $li) {
	
	$li[0]=strip_tags($li[0]);
	if(strlen($li[0])!=0){
		$msgs=$msgs.$li[0]."|";	
	}
}

$msgs=substr($msgs,0,strlen($msgs)-1);
$str = <<<eot


eot;
echo $str;
//Add By 01↑

if(is_numeric($_SERVER['QUERY_STRING'])) {
	showmessage('enter_the_space', "space.php?uid=$_SERVER[QUERY_STRING]", 0);
}

//大家的心情	Add By 01
$doinglist = "欢迎来到舍内网！|今天天气不错！|你喜欢什么东东呀？|我最喜欢我家的笨宠物～～～";
$query = $_SGLOBAL['db']->query("SELECT message,username FROM ".tname('doing')." where mood=0 ORDER BY dateline DESC LIMIT 0,15");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
  $value['message']=str_replace(" ","&nbsp;",$value['message']);
      $doinglist =$doinglist.$dui."|".$value['username'].'：'.$value['message'];
}

//二级域名
if(!isset($_GET['do']) && $_SCONFIG['allowdomain']) {
	$hostarr = explode('.', $_SERVER['HTTP_HOST']);
	$domainrootarr = explode('.', $_SCONFIG['domainroot']);
	if(count($hostarr) > 2 && count($hostarr) > count($domainrootarr) && $hostarr[0] != 'www' && !isholddomain($hostarr[0])) {
		showmessage('enter_the_space', $_SCONFIG['siteallurl'].'space.php?domain='.$hostarr[0], 0);
	}
}

@include_once(S_ROOT.'./data/data_yywang_link.php');	//Add By 01
/*	Modify By 01
if($_SGLOBAL['supe_uid']) {
	//已登录，直接跳转个人首页
	showmessage('enter_the_space', 'space.php?do=home', 0);
}
*/

if(empty($_SCONFIG['networkpublic'])) {
	
	$cachefile = S_ROOT.'./data/cache_index.txt';
	$cachetime = @filemtime($cachefile);
	
	$spacelist = array();
	if($_SGLOBAL['timestamp'] - $cachetime > 900) {
		//20位热门用户
		$query = $_SGLOBAL['db']->query("SELECT s.*, sf.resideprovince, sf.residecity
			FROM ".tname('space')." s
			LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid
			ORDER BY s.friendnum DESC LIMIT 0,18");	//20	Modify By 01
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$spacelist[] = $value;
		}
		swritefile($cachefile, serialize($spacelist));
	} else {
		$spacelist = unserialize(sreadfile($cachefile));
	}
	
/*	Modify By 01
	//应用
	$myappcount = 0;
	$myapplist = array();
	if($_SCONFIG['my_status']) {
		$myappcount = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('myapp')." WHERE flag>='0'"), 0);
		if($myappcount) {
			$query = $_SGLOBAL['db']->query("SELECT appid,appname FROM ".tname('myapp')." WHERE flag>=0 ORDER BY flag DESC, displayorder LIMIT 0,7");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$myapplist[] = $value;
			}
		}
	}
*/
//Add By 01↓
	//好玩的应用
	$myapplist = $onlielist = array();
	if($_SCONFIG['my_status']) {
		$query = $_SGLOBAL['db']->query("SELECT appid,appname FROM ".tname('myapp')." WHERE flag>=0 ORDER BY flag DESC, displayorder LIMIT 0,1");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$myapplist[] = $value;
		}
	} else {
		$query = $_SGLOBAL['db']->query("SELECT uid,username FROM ".tname('session')." ORDER BY lastactivity DESC LIMIT 0,5");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['uid'], $value['username']);
			$onlinelist[] = $value;
		}
	}

	//热门日志
	$bloglist = array();
	$query = $_SGLOBAL['db']->query("SELECT blogid,subject,uid,username FROM ".tname('blog')." WHERE friend='0' AND dateline>'$wheretime' ORDER BY replynum DESC LIMIT 0,4");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$bloglist[] = $value;
	}
	
	//最近更新的相册
	$albumlist = array();
	$query = $_SGLOBAL['db']->query("SELECT albumid,albumname,picnum,pic,picflag,uid,username FROM ".tname('album')." WHERE friend='0' AND picnum>0 ORDER BY updatetime DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$albumlist[] = $value;
	}
	
	//热门群组
	$mtaglist = $threadlist = array();
	$query = $_SGLOBAL['db']->query("SELECT tagid,tagname,membernum,pic FROM ".tname('mtag')." ORDER BY membernum DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if(empty($value['pic'])) {
			$value['pic'] = 'image/nologo.jpg';
		}
		$mtaglist[] = $value;
		
		//最新话题
		$query2 = $_SGLOBAL['db']->query("SELECT tid,subject,uid,username FROM ".tname('thread')." WHERE tagid='$value[tagid]' ORDER BY dateline DESC LIMIT 0,3");
		while ($thread = $_SGLOBAL['db']->fetch_array($query2)) {
			$threadlist[$value['tagid']][] = $thread;
		}
	}

	//竞价排名
	$showlist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." ORDER BY credit DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$value['note'] = addslashes(getstr($value['note'], 80, 0, 0, 0, 0, -1));
		$showlist[] = $value;
	}

	//成员列表
	$netcache['spacelist'] = array();
	if(empty($network['space'])) {
		$sql = " ORDER BY updatetime DESC LIMIT 0,5";
	} else {
		eval("\$network['space'] = \"$network[space]\";");
		$sql = ' '.trim($network['space']);
	}
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space').$sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$netcache['spacelist'][] = $value;
	}
//Add By 01↑
	
	//实名
	foreach ($spacelist as $key => $value) {
		realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	}
	realname_get();
	
	$_TPL['css'] = 'network';
	include_once template("index");
} else {
//	include_once(S_ROOT.'./source/network.php');
	include_once(S_ROOT.'./source/index.php');	//Add By 01
}

?>