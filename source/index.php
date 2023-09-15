<?
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: network_album.php 12078 2009-05-04 08:28:37Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//是否公开
if(empty($_SCONFIG['networkpublic'])) {
	checklogin();//需要登录
}

include_once(S_ROOT.'./data/data_network.php');
	
//用户积分排行
	$spacelist = array();
	if(empty($network['space'])) {
		$sql = " ORDER BY credit DESC LIMIT 0,18";
	} else {
		eval("\$network['space'] = \"$network[space]\";");
		$sql = ' '.trim($network['space']);
	}
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space').$sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$spacelist[] = $value;
	}
		//首页居民秀
	$favs = array();
	$favlists = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY RAND() LIMIT 72");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$favlists[] = $value;
}
	
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
	$query = $_SGLOBAL['db']->query("SELECT tagid,tagname,membernum,pic FROM ".tname('mtag')." WHERE fieldid>3 ORDER BY membernum DESC LIMIT 0,1");	//类别前3为系统群组
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
		$sql = " ORDER BY updatetime DESC LIMIT 0,7";
	} else {
		eval("\$network['space'] = \"$network[space]\";");
		$sql = ' '.trim($network['space']);
	}
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space').$sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$netcache['spacelist'][] = $value;
	}

//在线人数
$olcount = getcount('session', array());

realname_get();

//最后登录名
$membername = empty($_SCOOKIE['loginuser'])?'':sstripslashes($_SCOOKIE['loginuser']);
$wheretime = $_SGLOBAL['timestamp']-3600*24*30;

$_TPL['css'] = 'index';
include_once template("index");

//检查缓存
function check_network_cache($type) {
	global $_SGLOBAL;
	
	if($_SGLOBAL['network'][$type]['cache']) {
		$cachefile = S_ROOT.'./data/cache_network_'.$type.'.txt';
		$ftime = filemtime($cachefile);
		if($_SGLOBAL['timestamp'] - $ftime < $_SGLOBAL['network'][$type]['cache']) {
			return true;
		}
	}
	return false;
}

//获得SQL
function mk_network_sql($type, $ids, $crops, $days, $orders) {
	global $_SGLOBAL;
	
	$nt = $_SGLOBAL['network'][$type];
	
	$wherearr = array('1');
	//指定
	foreach ($ids as $value) {
		if($nt[$value]) {
			$wherearr[] = "main.{$value} IN (".$nt[$value].")";
		}
	}
	
	//范围
	foreach ($crops as $value) {
		$value1 = $value.'1';
		$value2 = $value.'2';
		if($nt[$value1]) {
			$wherearr[] = "main.{$value} >= '".$nt[$value1]."'";
		}
		if($nt[$value2]) {
			$wherearr[] = "main.{$value} <= '".$nt[$value2]."'";
		}
	}
	//时间
	foreach ($days as $value) {
		if($nt[$value]) {
			$daytime = $_SGLOBAL['timestamp'] - $nt[$value]*3600*24;
			$wherearr[] = "main.{$value}>='$daytime'";
		}
	}
	//排序
	$order = in_array($nt['order'], $orders)?$nt['order']:array_shift($orders);
	$sc = in_array($nt['sc'], array('desc','asc'))?$nt['sc']:'desc';
	
	return array('wherearr'=>$wherearr, 'order'=>$order, 'sc'=>$sc);
}
$fav = array();
$favlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY RAND() LIMIT 52");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$favlist[] = $value;
}
?>