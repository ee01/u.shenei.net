<?php
/*
		UCHome2.0 飞信插件091031
		作者：54alin@discuz	http://www.hialin.com
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

include_once(S_ROOT.'./fetion/class.fetion.php');
$op = empty($_GET['op'])?'':$_GET['op'];

if(submitcheck('lostpwsubmit')) {
	$spaceinfo = array();
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.groupid, s.username, s.flag, sf.mobile FROM '.tname('space').' s LEFT JOIN '.tname('spacefield')." sf ON sf.uid=s.uid WHERE s.username='$_POST[username]'");
	$spaceinfo = $_SGLOBAL['db']->fetch_array($query);
	$fetion=get_fetion($spaceinfo['uid']);
	if(empty($spaceinfo['mobile']) || empty($fetion['enable'])) {
		showmessage('您的账户资料中没有完整的手机号码，或没有设置飞信，不能使用飞信短信取回密码功能，如有疑问请与管理员联系。');
	}
	//创始人、管理员不允许找回密码
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	$op = 'fetion';
	$username = $spaceinfo['username'];
	
} elseif(submitcheck('sendcodesubmit')) {
	//获取UCHome本身的手机号码
	$spaceinfo = array();
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.groupid, s.username, s.flag, sf.mobile FROM '.tname('space').' s LEFT JOIN '.tname('spacefield')." sf ON sf.uid=s.uid WHERE s.username='$_POST[username]'");
	$spaceinfo = $_SGLOBAL['db']->fetch_array($query);
	$fetion=get_fetion($spaceinfo['uid']);
	if(empty($spaceinfo['mobile']) || $spaceinfo['mobile'] != $_POST['mobile']) {
		showmessage('输入的手机号码地址与用户名不匹配，请重新确认。');
	}
	//创始人、管理员不允许找回密码
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	//发送6位验证码
	$code=substr(rand(1000000,9999999),-6);
	$str=authcode($code.'@getpw@'.$_SGLOBAL['timestamp'],'ENCODE');
	$setarr = array('loginpw' => $str);
	$msg="您的取回密码验证码为：$code ，验证码的有效期为3分钟。温馨提示：请不要把验证码告知他人。# $_SCONFIG[sitename] #";
	sendfetion_sms($spaceinfo['uid'],$msg);
	updatetable('fetion', $setarr, array('uid'=>$spaceinfo['uid']));
	$op = 'entercode';
	$username = $spaceinfo['username'];

} elseif(submitcheck('entercodesubmit')) {
	//获取UCHome本身的手机号码
	$spaceinfo = array();
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.groupid, s.username, s.flag, sf.mobile FROM '.tname('space').' s LEFT JOIN '.tname('spacefield')." sf ON sf.uid=s.uid WHERE s.username='$_POST[username]'");
	$spaceinfo = $_SGLOBAL['db']->fetch_array($query);
	$fetion=get_fetion($spaceinfo['uid']);
	list($code,$fac,$dateline) =  explode("@", authcode($fetion['loginpw'],'DECODE'));
	if($dateline < $_SGLOBAL['timestamp']-60*3 || $fac != 'getpw' || $code != $_POST['code']) {
		showmessage('您所用的验证码不存在或已经过期，无法取回密码。');
	}
	//创始人、管理员不允许找回密码
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	
	showmessage('验证成功，跳转至重设密码。','do.php?ac=fetion_getpw&op=reset&uid='.$spaceinfo['uid'].'&code='.$code,'2');

} elseif(submitcheck('resetsubmit')) {
	$uid = empty($_POST['uid'])?0:intval($_POST['uid']);
	$getcode = empty($_POST['code'])?0:trim($_POST['code']);
	if($_POST['newpasswd1'] != $_POST['newpasswd2']) {
		showmessage('password_inconsistency');
	}
	if($_POST['newpasswd1'] != addslashes($_POST['newpasswd1'])) {
		showmessage('profile_passwd_illegal');
	}
	
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.username, s.groupid, s.flag, sf.email FROM '.tname('space').' s, '.tname('spacefield')." sf WHERE s.uid='$uid' AND sf.uid=s.uid");
	$space = $_SGLOBAL['db']->fetch_array($query);
	checkuser($getcode, $space);	
	//验证是否受保护、创始人、有站点设置权限的人禁止找回密码方式修改密码
	$founderarr = explode(',', $_SC['founder']);
	if($space['flag'] || in_array($space['uid'], $founderarr) || checkperm('admin')) {
		showmessage('reset_passwd_account_invalid');
	}
	
	if(!@include_once S_ROOT.'./uc_client/client.php') {
		showmessage('system_error');
	}
	if(uc_user_edit(addslashes($space['username']), $_POST['newpasswd1'], $_POST['newpasswd1'], $space['email'], 1)>0) {
		updatetable('fetion', array('loginpw'=>''), array('uid'=>$uid));
	}
	showmessage('getpasswd_succeed');
}

if($op == 'reset') {
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.username, sf.email FROM '.tname('space').' s, '.tname('spacefield')." sf WHERE s.uid='$_GET[uid]' AND sf.uid=s.uid");
	$space = $_SGLOBAL['db']->fetch_array($query);
	checkuser($_GET['code'], $space);
}

include template('do_fetion_getpw');

//验证地址地否有效
function checkuser($getcode, $space) {
	global $_SGLOBAL;
	if(empty($space)) {
		showmessage('user_does_not_exist');
	}
	$fetion=get_fetion($space['uid']);
	list($code, $fac, $dateline) = explode("@", authcode($fetion['loginpw'],'DECODE'));
	if($dateline < $_SGLOBAL['timestamp']-60*3 || $fac != 'getpw' || $code != $getcode) {
		showmessage('您所用的验证码不存在或已经过期，无法取回密码。');
	}
}
?>