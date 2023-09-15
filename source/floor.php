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

$dodolist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." ORDER BY dateline DESC LIMIT 0,30");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
	$value['title'] = getstr($value['message'], 0, 0, 0, 0, 0, -1);
	$dolist[] = $value;}

//记录
$dolist = array();
$query = $_SGLOBAL['db']->query("SELECT *
	FROM ".tname('doing')."
	ORDER BY dateline DESC LIMIT 0,5");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$value['title'] = getstr($value['message'], 0, 0, 0, 0, 0, -1);
	$dolist[] = $value;
}
//居民秀
$fav = array();
$favlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." AS `show` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `show`.`uid`=`spacefield`.`uid` ORDER BY RAND() LIMIT 27");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$favlist[] = $value;
}



//竞价排名
$showlisttop = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." as `show` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `show`.`uid`=`spacefield`.`uid` ORDER BY `show`.`credit` DESC LIMIT 1");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	$value['note'] = $value;
	$showlisttop[$value['uid']] = $value;
}
$showlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." as `show` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `show`.`uid`=`spacefield`.`uid` ORDER BY `show`.`credit` DESC LIMIT 1,10");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	$value['note'] = $value;
	$showlist[$value['uid']] = $value;
}
$showlistboy = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." as `show` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `show`.`uid`=`spacefield`.`uid` WHERE `spacefield`.`SEX`=1 ORDER BY `show`.`credit` DESC LIMIT 0,30");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	$value['note'] = $value;
	$showlistboy[$value['uid']] = $value;
}

$showlistgirl = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('show')." as `show` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `show`.`uid`=`spacefield`.`uid` WHERE `spacefield`.`SEX`=2 ORDER BY `show`.`credit` DESC LIMIT 0,30");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	$value['note'] = $value;
	$showlistgirl[$value['uid']] = $value;
}


//最后登录名
$membername = empty($_SCOOKIE['loginuser'])?'':sstripslashes($_SCOOKIE['loginuser']);
$wheretime = $_SGLOBAL['timestamp']-3600*24*30;

$_TPL['css'] = 'network';
include_once template("floor");

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

?>