<?php
/*
		UCHome2.0 ���Ų��091031
		���ߣ�54alin@discuz	http://www.hialin.com
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
		showmessage('�����˻�������û���������ֻ����룬��û�����÷��ţ�����ʹ�÷��Ŷ���ȡ�����빦�ܣ����������������Ա��ϵ��');
	}
	//��ʼ�ˡ�����Ա�������һ�����
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	$op = 'fetion';
	$username = $spaceinfo['username'];
	
} elseif(submitcheck('sendcodesubmit')) {
	//��ȡUCHome������ֻ�����
	$spaceinfo = array();
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.groupid, s.username, s.flag, sf.mobile FROM '.tname('space').' s LEFT JOIN '.tname('spacefield')." sf ON sf.uid=s.uid WHERE s.username='$_POST[username]'");
	$spaceinfo = $_SGLOBAL['db']->fetch_array($query);
	$fetion=get_fetion($spaceinfo['uid']);
	if(empty($spaceinfo['mobile']) || $spaceinfo['mobile'] != $_POST['mobile']) {
		showmessage('������ֻ������ַ���û�����ƥ�䣬������ȷ�ϡ�');
	}
	//��ʼ�ˡ�����Ա�������һ�����
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	//����6λ��֤��
	$code=substr(rand(1000000,9999999),-6);
	$str=authcode($code.'@getpw@'.$_SGLOBAL['timestamp'],'ENCODE');
	$setarr = array('loginpw' => $str);
	$msg="����ȡ��������֤��Ϊ��$code ����֤�����Ч��Ϊ3���ӡ���ܰ��ʾ���벻Ҫ����֤���֪���ˡ�# $_SCONFIG[sitename] #";
	sendfetion_sms($spaceinfo['uid'],$msg);
	updatetable('fetion', $setarr, array('uid'=>$spaceinfo['uid']));
	$op = 'entercode';
	$username = $spaceinfo['username'];

} elseif(submitcheck('entercodesubmit')) {
	//��ȡUCHome������ֻ�����
	$spaceinfo = array();
	$query = $_SGLOBAL['db']->query('SELECT s.uid, s.groupid, s.username, s.flag, sf.mobile FROM '.tname('space').' s LEFT JOIN '.tname('spacefield')." sf ON sf.uid=s.uid WHERE s.username='$_POST[username]'");
	$spaceinfo = $_SGLOBAL['db']->fetch_array($query);
	$fetion=get_fetion($spaceinfo['uid']);
	list($code,$fac,$dateline) =  explode("@", authcode($fetion['loginpw'],'DECODE'));
	if($dateline < $_SGLOBAL['timestamp']-60*3 || $fac != 'getpw' || $code != $_POST['code']) {
		showmessage('�����õ���֤�벻���ڻ��Ѿ����ڣ��޷�ȡ�����롣');
	}
	//��ʼ�ˡ�����Ա�������һ�����
	$founderarr = explode(',', $_SC['founder']);
	if($spaceinfo['flag'] || in_array($spaceinfo['uid'], $founderarr) || checkperm('admin')) {
		showmessage('getpasswd_account_invalid');
	}
	
	showmessage('��֤�ɹ�����ת���������롣','do.php?ac=fetion_getpw&op=reset&uid='.$spaceinfo['uid'].'&code='.$code,'2');

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
	//��֤�Ƿ��ܱ�������ʼ�ˡ���վ������Ȩ�޵��˽�ֹ�һ����뷽ʽ�޸�����
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

//��֤��ַ�ط���Ч
function checkuser($getcode, $space) {
	global $_SGLOBAL;
	if(empty($space)) {
		showmessage('user_does_not_exist');
	}
	$fetion=get_fetion($space['uid']);
	list($code, $fac, $dateline) = explode("@", authcode($fetion['loginpw'],'DECODE'));
	if($dateline < $_SGLOBAL['timestamp']-60*3 || $fac != 'getpw' || $code != $getcode) {
		showmessage('�����õ���֤�벻���ڻ��Ѿ����ڣ��޷�ȡ�����롣');
	}
}
?>