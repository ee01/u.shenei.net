<?
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: network_album.php 12078 2009-05-04 08:28:37Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//�Ƿ񹫿�
if(empty($_SCONFIG['networkpublic'])) {
	checklogin();//��Ҫ��¼
}

include_once(S_ROOT.'./data/data_network.php');



$dodolist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." ORDER BY dateline DESC LIMIT 0,30");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
	$value['title'] = getstr($value['message'], 0, 0, 0, 0, 0, -1);
	$dodolist[] = $value;}



//��������
$showlist = array();
$query = $_SGLOBAL['db']->query("SELECT sh.note, s.* FROM ".tname('show')." sh
	LEFT JOIN ".tname('space')." s ON s.uid=sh.uid
	ORDER BY sh.credit DESC LIMIT 0,12");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
	$value['note'] = addslashes(getstr($value['note'], 80, 0, 0, 0, 0, -1));
	$showlist[$value['uid']] = $value;
}
if(empty($star) && $showlist) {
	$star = sarray_rand($showlist, 1);
}
//վ���Ƽ�
$star = array();
$starlist = array();
if($_SCONFIG['spacebarusername']) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE username IN (".simplode(explode(',', $_SCONFIG['spacebarusername'])).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
		$starlist[] = $value;
	}
}
if($starlist) {
	$star = sarray_rand($starlist, 3);
}
//�����û�
$onlinelist = array();
$query = $_SGLOBAL['db']->query("SELECT s.*, sf.note FROM ".tname('session')." s
	LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid
	ORDER BY s.lastactivity DESC LIMIT 0,12");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	if(!$value['magichidden']) {
		$value['note'] = shtmlspecialchars(strip_tags($value['note']));
		realname_set($value['uid'], $value['username']);
		$onlinelist[$value['uid']] = $value;
	}
}
if(empty($star) && $onlinelist) {
	$star = sarray_rand($onlinelist, 1);
}


//��������
$olcount = getcount('session', array());


//������
$fav = array();
$favlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY RAND() LIMIT 52");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$favlist[] = $value;
}

$viewin = array();
$viewinlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY viewnum DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$viewinlist[] = $value;
}

$ctop = array();
$ctoplist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY credit DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$ctoplist[] = $value;
}

$exptop = array();
$exptoplist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY experience DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$exptoplist[] = $value;
}

$frdtop = array();
$frdlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY friendnum DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$frdlist[] = $value;
}

$myshowtop = array();
$myshowtoplist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY albumnum DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$myshowtoplist[] = $value;
}

$blogtop = array();
$blogtoplist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY blognum DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$blogtoplist[] = $value;
}

$threadtop = array();
$threadtoplist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." AS `space` LEFT JOIN ".tname('spacefield')." AS `spacefield` on `space`.`uid`=`spacefield`.`uid` WHERE `space`.`avatar`='1' ORDER BY threadnum DESC LIMIT 0,11");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	realname_set($value['uid'], $value['username']);
	$threadtoplist[] = $value;
}

realname_get();

//����¼��
$membername = empty($_SCOOKIE['loginuser'])?'':sstripslashes($_SCOOKIE['loginuser']);
$wheretime = $_SGLOBAL['timestamp']-3600*24*30;

$_TPL['css'] = 'network';
include_once template("sohovicstyle");

//��黺��
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

//���SQL
function mk_network_sql($type, $ids, $crops, $days, $orders) {
	global $_SGLOBAL;
	
	$nt = $_SGLOBAL['network'][$type];
	
	$wherearr = array('1');
	//ָ��
	foreach ($ids as $value) {
		if($nt[$value]) {
			$wherearr[] = "main.{$value} IN (".$nt[$value].")";
		}
	}
	
	//��Χ
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
	//ʱ��
	foreach ($days as $value) {
		if($nt[$value]) {
			$daytime = $_SGLOBAL['timestamp'] - $nt[$value]*3600*24;
			$wherearr[] = "main.{$value}>='$daytime'";
		}
	}
	//����
	$order = in_array($nt['order'], $orders)?$nt['order']:array_shift($orders);
	$sc = in_array($nt['sc'], array('desc','asc'))?$nt['sc']:'desc';
	
	return array('wherearr'=>$wherearr, 'order'=>$order, 'sc'=>$sc);
}

?>