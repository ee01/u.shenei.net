<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: do_login.php 13210 2009-08-20 07:09:06Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

include_once(S_ROOT.'./source/function_cp.php');

if($_SGLOBAL['supe_uid']) {
	showmessage('do_success', 'space.php', 0);
}

$refer = empty($_GET['refer'])?rawurldecode($_SCOOKIE['_refer']):$_GET['refer'];
preg_match("/(admincp|do|cp)\.php\?ac\=([a-z]+)/i", $refer, $ms);
if($ms) {
	if($ms[1] != 'cp' || $ms[2] != 'sendmail') $refer = '';
}
if(empty($refer)) {
	$refer = 'space.php?do=home';
}

//好友邀请
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);
$code = empty($_GET['code'])?'':$_GET['code'];
$app = empty($_GET['app'])?'':intval($_GET['app']);
$invite = empty($_GET['invite'])?'':$_GET['invite'];
$invitearr = array();
$reward = getreward('invitecode', 0);
if($uid && $code && !$reward['credit']) {
	$m_space = getspace($uid);
	if($code == space_key($m_space, $app)) {//验证通过
		$invitearr['uid'] = $uid;
		$invitearr['username'] = $m_space['username'];
	}
	$url_plus = "uid=$uid&app=$app&code=$code";
} elseif($uid && $invite) {
	include_once(S_ROOT.'./source/function_cp.php');
	$invitearr = invite_get($uid, $invite);
	$url_plus = "uid=$uid&invite=$invite";
}

//没有登录表单
$_SGLOBAL['nologinform'] = 1;

//发送短信密码
if($_GET[a]==send){
	$membername = $_POST[username];
	$username = $membername;
	include_once S_ROOT.'./uc_client/client.php';
	if($get_user_data = uc_get_user($username)){
		list($uid, $username, $email) = $get_user_data;
	}else{
		showmessage('用户不存在！');
	}

	include_once(S_ROOT.'./fetion/class.fetion.php');
	$fetion=get_fetion($uid);

	if($fetion[set_login_pw] != 1){showmessage('用户没有开启 短信密码 功能！');}
	
	$code=substr(rand(1000000,9999999),-6);
	$str=authcode($code.'@'.$_SGLOBAL['timestamp'],'ENCODE');
	$setarr = array(
	'uid'=>$uid,
	'loginpw' => $str
	);
	$msg="您的 $_SCONFIG[sitename] 动态密码是：$code ，密码的有效期为3分钟。温馨提示：请不要把密码告知他人。# $_SCONFIG[sitename] #";
	sendfetion_sms($uid,$msg);
	updatetable('fetion', $setarr, array('uid'=>$uid));
	showmessage('系统已将短信密码发送到你的手机, 请接收后填写到"密码"输入框。密码有效期为3分钟！','do.php?ac='.$_SCONFIG[login_action].'&m=1','3');
}

if(submitcheck('loginsubmit')) {

	$password = $_POST['password'];
	$username = trim($_POST['username']);
	$cookietime = intval($_POST['cookietime']);
	
	$cookiecheck = $cookietime?' checked':'';
	$membername = $username;
	
	if(empty($_POST['username'])) {
		showmessage('users_were_not_empty_please_re_login', 'do.php?ac='.$_SCONFIG['login_action']);
	}
	
	if($_SCONFIG['seccode_login']) {
		include_once(S_ROOT.'./source/function_cp.php');
		if(!ckseccode($_POST['seccode'])) {
			$_SGLOBAL['input_seccode'] = 1;
			include template('do_login');
			exit;
		}
	}

	//同步获取用户源
/*
	if(!$passport = getpassport($username, $password)) {
		showmessage('login_failure_please_re_login', 'do.php?ac='.$_SCONFIG['login_action']);
	}
Add By 01↓*/
	if(empty($_POST['login_m'])){
		//网站密码登陆
		if(!$passport = getpassport($username, $password)) {
			showmessage('login_failure_please_re_login', 'do.php?ac='.$_SCONFIG['login_action']);
		}
	}else{
		//短息密码登陆
		if(!$passport = fetion_getpassport($username, $password)) {
			showmessage('login_failure_please_re_login', 'do.php?ac='.$_SCONFIG['login_action'].'&m=1');
		}
	}
	
	//发送飞信登陆信息
	include_once(S_ROOT.'./fetion/class.fetion.php');
	$fetion=get_fetion($passport['uid']);
	if(!empty($fetion[enable]) && $fetion[set_login] == 1){
		$msg='尊敬的'.$_SCONFIG[sitename].'用户，您好！您已于'.date("Y-m-d H:i:s").'登陆'.$_SCONFIG[sitename].'。登陆IP为：'.getonlineip();
		sendfetion_sms($passport['uid'],$msg);
	}
//Add By 01↑
	
	$setarr = array(
		'uid' => $passport['uid'],
		'username' => addslashes($passport['username']),
		'password' => md5("$passport[uid]|$_SGLOBAL[timestamp]")//本地密码随机生成
	);
	
	include_once(S_ROOT.'./source/function_space.php');
	//开通空间
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('space')." WHERE uid='$setarr[uid]'");
	if(!$space = $_SGLOBAL['db']->fetch_array($query)) {
		$space = space_open($setarr['uid'], $setarr['username'], 0, $passport['email']);
	}
	
	$_SGLOBAL['member'] = $space;
	
	//实名
	realname_set($space['uid'], $space['username'], $space['name'], $space['namestatus']);
	
	//检索当前用户
	$query = $_SGLOBAL['db']->query("SELECT password FROM ".tname('member')." WHERE uid='$setarr[uid]'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$setarr['password'] = addslashes($value['password']);
	} else {
		//更新本地用户库
		inserttable('member', $setarr, 0, true);
	}

	//清理在线session
	insertsession($setarr);
	
	//设置cookie
	ssetcookie('auth', authcode("$setarr[password]\t$setarr[uid]", 'ENCODE'), $cookietime);
	ssetcookie('loginuser', $passport['username'], 31536000);
	ssetcookie('_refer', '');
	
	//同步登录
	if($_SCONFIG['uc_status']) {
		include_once S_ROOT.'./uc_client/client.php';
		$ucsynlogin = uc_user_synlogin($setarr['uid']);
	} else {
		$ucsynlogin = '';
	}
	
	//好友邀请
	if($invitearr) {
		//成为好友
		invite_update($invitearr['id'], $setarr['uid'], $setarr['username'], $invitearr['uid'], $invitearr['username'], $app);
	}
	$_SGLOBAL['supe_uid'] = $space['uid'];
	//判断用户是否设置了头像
	$reward = $setarr = array();
	$experience = $credit = 0;
	$avatar_exists = ckavatar($space['uid']);
	if($avatar_exists) {
		if(!$space['avatar']) {
			//奖励积分
			$reward = getreward('setavatar', 0);
			$credit = $reward['credit'];
			$experience = $reward['experience'];
			if($credit) {
				$setarr['credit'] = "credit=credit+$credit";
			}
			if($experience) {
				$setarr['experience'] = "experience=experience+$experience";
			}
			$setarr['avatar'] = 'avatar=1';
			$setarr['updatetime'] = "updatetime=$_SGLOBAL[timestamp]";
		}
	} else {
		if($space['avatar']) {
			$setarr['avatar'] = 'avatar=0';
		}
	}
	
	if($setarr) {
		$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET ".implode(',', $setarr)." WHERE uid='$space[uid]'");
	}

	if(empty($_POST['refer'])) {
		$_POST['refer'] = 'space.php?do=home';
	}
	
	realname_get();
	
	showmessage('login_success', $app?"userapp.php?id=$app":$_POST['refer'], 1, array($ucsynlogin));
}

$membername = empty($_SCOOKIE['loginuser'])?'':sstripslashes($_SCOOKIE['loginuser']);
$cookiecheck = ' checked';

include template('do_login');

?>