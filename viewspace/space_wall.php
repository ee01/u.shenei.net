<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_wall.php 10314 2008-11-28 09:09:23Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}


//分页
$perpage = 10;
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;

//检查开始数
ckstart($start, $perpage);

if($space['self'])
{
$styname=stylecount($_SGLOBAL['supe_uid']);
$usercss = new Usercssabouta($uid,$_SGLOBAL['supe_uid']);
$tempstyle=$usercss -> tempstyle;
$defaultset=$usercss -> defaultset;
$wrap=$usercss -> wrap;
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'?'.$usercss->date.'" />';
}
else
{
$value=Usercssabout($uid);
$wrap='wraptwo';
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'" />';
}

$musiclist=unserialize($space[smusic]);
$mcount=0;
foreach ($musiclist as $key => $value) {
$mlist .='listURL['.$mcount.']="'.$value.'"
';
$mlist .='RadioList['.$mcount.']="'.$key.'"
';
$mcount =$mcount+1;
}
//是否好友
$space['isfriend'] = $space['self'];
if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
	$space['isfriend'] = 1;//是好友
}

//红包道具
$space['magiccredit'] = 0;
if($_SGLOBAL['magic']['gift'] && $_SGLOBAL['supe_uid']) {
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('magicuselog')." WHERE uid='$space[uid]' AND mid='gift' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$data = empty($value['data'])?array():unserialize($value['data']);
		if($data['left'] <= 0) {
			$_SGLOBAL['db']->query('DELETE FROM '.tname('magicuselog')." WHERE uid = '$_SGLOBAL[supe_uid]' AND mid = 'gift'");
		}
		if(!$data['receiver'] || !in_array($_SGLOBAL['supe_uid'], $data['receiver'])) {
			$space['magiccredit'] = $data['left'] >= $data['chunk'] ? $data['chunk'] : $data['left'];
		}
	}
}

//是否在线
$isonline = 0;
$isonline = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT lastactivity FROM ".tname('session')." WHERE uid='$uid'"), 0);

//处理查询
$theurl = "viewspace.php?uid=$space[uid]&do=$do";

$cid = empty($_GET['cid'])?0:intval($_GET['cid']);
$csql = $cid?"cid='$cid' AND":'';
	
$list = array();
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('comment')." WHERE $csql id='$space[uid]' AND idtype='uid'"),0);
if($count) {
	include_once(S_ROOT.'./source/function_blog.php');	//Add By 01
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE $csql id='$space[uid]' AND idtype='uid' ORDER BY dateline DESC LIMIT $start,$perpage");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['authorid'], $value['author']);
		$value['message'] = comment_hidden($value['cid'],$_SGLOBAL['supe_uid']);	//Add By 01
		$list[] = $value;
	}
}

//分页
$multi = multiviewspace($count, $perpage, $page, $theurl);

realname_get();

include_once template("newsp_viewspace_wall");

?>