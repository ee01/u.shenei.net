<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_index.php 10118 2008-11-25 07:21:33Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
$uid = $space[uid];


//个人资料
//性别
$space['sex_org'] = $space['sex'];
if(ckprivacy('profile')) {//隐私
	$space['showprofile'] = 1;
	$space['sex'] = $space['sex']=='1'?'<a href="cp.php?ac=friend&op=search&sex=1&searchmode=1" target="_blank">'.lang('man').'</a>':($space['sex']=='2'?'<a href="cp.php?ac=friend&op=search&sex=2&searchmode=1" target="_blank">'.lang('woman').'</a>':'');
	$space['birthday'] = ($space['birthyear']?"$space[birthyear]".lang('year'):'').($space['birthmonth']?"$space[birthmonth]".lang('month'):'').($space['birthday']?"$space[birthday]".lang('day'):'');
	$space['marry'] = $space['marry']=='1'?'<a href="cp.php?ac=friend&op=search&marry=1&searchmode=1" target="_blank">'.lang('unmarried').'</a>':($space['marry']=='2'?'<a href="cp.php?ac=friend&op=search&marry=2&searchmode=1" target="_blank">'.lang('married').'</a>':'');
	$space['birth'] = trim(($space['birthprovince']?"<a href=\"cp.php?ac=friend&op=search&birthprovince=".rawurlencode($space['birthprovince'])."&searchmode=1\" target=\"_blank\">$space[birthprovince]</a>":'').($space['birthcity']?" <a href=\"cp.php?ac=friend&op=search&birthcity=".rawurlencode($space['birthcity'])."&searchmode=1\" target=\"_blank\">$space[birthcity]</a>":''));
	$space['reside'] = trim(($space['resideprovince']?"<a href=\"cp.php?ac=friend&op=search&resideprovince=".rawurlencode($space['resideprovince'])."&searchmode=1\" target=\"_blank\">$space[resideprovince]</a>":'').($space['residecity']?" <a href=\"cp.php?ac=friend&op=search&residecity=".rawurlencode($space['residecity'])."&searchmode=1\" target=\"_blank\">$space[residecity]</a>":''));
	$space['qq'] = empty($space['qq'])?'':"<a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?V=1&Uin=$space[qq]&Site=$space[username]&Menu=yes\">$space[qq]</a>";
	//自定义
	@include_once(S_ROOT.'./data/data_profilefield.php');
	$fields = empty($_SGLOBAL['profilefield'])?array():$_SGLOBAL['profilefield'];
} else {
	$space['showprofile'] = 0;
}



//个人群组
$mtaglist = array();
if(ckprivacy('mtag')) {
	$query = $_SGLOBAL['db']->query("SELECT field.* FROM ".tname('tagspace')." main
		LEFT JOIN ".tname('mtag')." field ON field.tagid=main.tagid
		WHERE main.uid='$space[uid]' LIMIT 0, 100");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$mtaglist[$value['fieldid']][] = $value;
	}
	if($mtaglist) {
		ksort($mtaglist);
		@include_once(S_ROOT.'./data/data_profield.php');
	}
}
//是否在线
$isonline = 0;
$isonline = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT lastactivity FROM ".tname('session')." WHERE uid='$uid'"), 0);

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



include template('newsp_viewspace_aboutme');

?>
