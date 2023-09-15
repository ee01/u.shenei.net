<?php
/*
		UCHome2.0 飞信插件091031
		作者：54alin@discuz	http://www.hialin.com
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$fetion_sendid = empty($_GET['fetion_sendid'])?0:floatval($_GET['fetion_sendid']);
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);
if($uid) {
	$touid = $uid;
} else {
	$touid = empty($_GET['touid'])?0:intval($_GET['touid']);
}
$daterange = empty($_GET['daterange'])?1:intval($_GET['daterange']);

include_once S_ROOT.'./uc_client/client.php';
include_once(S_ROOT.'./fetion/class.fetion.php');

//获取实名 Add By 01
realname_set($_SGLOBAL[supe_uid],$_SGLOBAL[supe_username]);
realname_get();

$msgend=" #$_SCONFIG[sitename] ".$_SN[$_SGLOBAL['supe_uid']]." 发出#";	//Modify By 01
$reward = getreward('sendfetion', 0);

$actives = empty($_GET['op'])?array('websites' => ' class="active"'):array($_GET['op'] => ' class="active"');

if($_GET[op] == help){
	if(empty($_GET['type'])) $_GET['type'] = 'ktfx';
	$sub_actives = array($_GET['type'] => ' style="font-weight:bold;"');
}else{
	$sub_actives = empty($_GET['type'])?array('send' => ' class="active"'):array($_GET['type'] => ' class="active"');
}


if(empty($_GET['op'])){
	//获取用户列表
	$friends = array();
	if($space['friendnum']) {
		$query = $_SGLOBAL['db']->query("SELECT fuid AS uid, fusername AS username FROM ".tname('friend')." WHERE uid=$_SGLOBAL[supe_uid] AND status='1' ORDER BY num DESC, dateline DESC LIMIT 0,100");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value['username'] = saddslashes($value['username']);
			$friends[] = $value;
		}
	}
}

//站内飞信
if(submitcheck('fetion_sendsubmit')) {
		//判断是否发布太快
		$waittime = interval_check('post');
		if($waittime > 0) {
			showmessage('operating_too_fast','',1,array($waittime));
		}	
		//新用户见习
		cknewuser();
		//黑名单
		if($touid) {
			if(isblacklist($touid)) {
				showmessage('is_blacklist');
			}
		}
		//发送消息
		$username = empty($_POST['username'])?'':$_POST['username'];
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		if(empty($message)) {
			showmessage('unable_to_send_air_news');
		}
		if($touid) {
			//从个人主页发送飞信
			$fetion=get_fetion($touid);
			$compare_time=compare_time($touid);
			if(empty($compare_time)){showmessage('发送失败，对方设置了接收时段：'.$fetion[set_receive_time].'，其他时段拒绝接收。');}
			//检查积分	
			if($space['credit'] < $reward['credit']) {showmessage('integral_inadequate', '', 1, array($space['credit'],  $reward['credit']));}
			
			sendfetion_sms($touid,$message.$msgend);
			getreward('sendfetion', 1, $_SGLOBAL['supe_uid']);
			showmessage('您的短信发送成功！');
		} elseif($username) {
			//向站内好友发送飞信
			$users = explode(',', $username);
			if($users[1]){showmessage('不支持群发！', 'cp.php?ac=fetion','2');}
			$touid=getuid($users[0]);
			
			//获取实名 Add By 01
			realname_set($touid);
			realname_get();

			$fetion=get_fetion($touid);
			$compare_time=compare_time($touid);
			if(empty($compare_time)){showmessage('发送失败，对方设置了接收时段：'.$fetion[set_receive_time].'，其他时段拒绝接收。');}
			//检查积分
			if($space['credit'] < $reward['credit']) {showmessage('integral_inadequate', '', 1, array($space['credit'],  $reward['credit']));}
						
			sendfetion_sms($touid,$message.$msgend);
			getreward('sendfetion', 1, $_SGLOBAL['supe_uid']);
			$title='{actor} 用 <a href="cp.php?ac=fetion">站内飞信</a> ，给好友 '.$_SN[$touid].' 发送了一条短信。';	//Modify By 01
			feed_add('fetion', $title);
			showmessage('发送成功！', 'cp.php?ac=fetion','2');
		}
}

if($_GET['op'] == friend){
	//分页
	$perpage = 10;
	$perpage = mob_perpage($perpage);
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	$mpurl .= '&perpage='.$perpage;
	if($page<1) $page=1;
	$start = ($page-1)*$perpage;
	//检查开始数
	ckstart($start, $perpage);
	
	$friendlist = array();
	$count = 0;
	$theurl = "cp.php?ac=fetion&op=friend&type=friend";
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('fetion_friend')." WHERE uid='$_SGLOBAL[supe_uid]'"), 0);
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion_friend')." WHERE uid='$_SGLOBAL[supe_uid]' ORDER BY fid DESC LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$friendids[] = $value['fid'];
			$friendlist[] = $value;
			//分页
			$multi = multi($count, $perpage, $page, $theurl);
		}

	}

	//获取好友列表
	$friends = array();
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion_friend')." WHERE uid=$_SGLOBAL[supe_uid] ORDER BY fid DESC");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value[] = saddslashes($value['name']);
			$friends[] = $value;
		}
		
	//发送站外飞信
	if(submitcheck('friend_sendsubmit')) {
		//新用户见习
		cknewuser();
		$username = empty($_POST['username'])?'':$_POST['username'];
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		$users = explode(',', $username);
		if($users[1]){showmessage('不支持群发！', 'cp.php?ac=fetion','2');}
		
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion_friend')." WHERE uid=$_SGLOBAL[supe_uid] AND name='$users[0]'");
		$fetion_friend = $_SGLOBAL['db']->fetch_array($query);
		if(!$fetion_friend[mobileno]){showmessage('发送失败，您输入的飞信好友不在您的好友列表里。');}
		$fetion=get_fetion($_SGLOBAL['supe_uid']);
		
		sendfetion($space['mobile'],authcode($fetion['pw'],'DECODE'),$fetion_friend[mobileno],$message.$msgend);
		feed_add('fetion', '{actor} 用 <a href="cp.php?ac=fetion&op=friend">站外飞信</a> ，向飞信好友发送了一条短信。');
		showmessage('发送成功！', 'cp.php?ac=fetion&op=friend','2');
	}

	//添加飞信好友
	if(submitcheck('addsubmit')) {
		$name= trim($_POST['name']);
		$mobileno = trim($_POST['mobileno']);
		if(empty($name) || empty($mobileno))showmessage('do_success','cp.php?ac=fetion&op=friend&type=friend', 0);
		$setarr=array('uid' => $_SGLOBAL['supe_uid'],'name' => $name,'mobileno' => $mobileno,);
		$fid=inserttable('fetion_friend', $setarr, 1);
		showmessage('do_success','cp.php?ac=fetion&op=friend&type=friend', 0);
	}

	//删除飞信好友
	if($_GET['do'] == 'delete') {
		if(submitcheck('deletesubmit')) {
			$_SGLOBAL['db']->query("DELETE FROM ".tname('fetion_friend')." WHERE fid='$_GET[fid]'");
			showmessage('do_success', $_POST[refer], 0);
		}
	}
}

//处理应用设置
if($_GET['op'] == apply){
	$fetion = get_fetion($_SGLOBAL[supe_uid]);
	if(submitcheck('fetion_setting_submit')){
		$setarr = array(
			'set_login' => getstr($_POST['set_login'], 2, 1, 1),
			'set_login_pw' => getstr($_POST['set_login_pw'], 2, 1, 1),
			'set_receive_time' => trim($_POST['set_receive_time'])
		);	
		updatetable('fetion', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
		showmessage('do_success','cp.php?ac=fetion&op=apply',2);
	}
}

//处理帐号设置
if($_GET['op'] == setuser){
	$fetion = get_fetion($_SGLOBAL[supe_uid]);
	//发送验证码
	if(submitcheck('fetion_send_code')){
		if(empty($space['mobile'])){showmessage('您的 <b>联系方式</b> 中没有填写手机，请返回设置后重试！');}
		if(empty($_POST['fetion_pw'])){showmessage('您的 <b>飞信密码</b> 不能为空！');}
		$code=substr(rand(1000000,9999999),-6);
		$str=authcode($code.'@'.$_POST['fetion_pw'].'@'.$_SGLOBAL['timestamp'],'ENCODE');
		$setarr = array('uid'=>$_SGLOBAL['supe_uid'],'authstr' => $str);
		$codemsg = "您好，您的飞信验证码为 $code ，验证码3分钟内有效。如过期失效，请重新获取验证码！# $_SCONFIG[sitename] #";
		sendfetion($space['mobile'],$_POST['fetion_pw'],$space['mobile'],$codemsg);
		if(empty($fetion['uid'])){
			inserttable('fetion', $setarr, 1);
		}else{
			updatetable('fetion', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
		}
		showmessage('发送成功，请注意查收手机短信！如3分钟内未收到验证短信，请重新发送！','cp.php?ac=fetion&op=setuser','2');
	}
	//验证密码
	if(submitcheck('fetion_submit')){
		if(empty($space['mobile'])){showmessage('您的 <b>联系方式</b> 中没有填写手机，请返回设置后重试！');}
		list($code,$pw,$dateline) = explode("@", authcode($fetion['authstr'],'DECODE'));
		if($dateline < $_SGLOBAL['timestamp']-60*3){showmessage('您输入的验证码已过期失效，请重新发送验证码！','cp.php?ac=fetion&op=setuser','2');}
		$fetion_code = trim($_POST['fetion_code']);
		if($fetion_code != $code){
			showmessage('您输入的验证码错误，请返回后重试！','cp.php?ac=fetion&op=setuser','2');
		}else{
			$setarr = array('enable' => '1','pw' => authcode($pw,'ENCODE'),'authstr' => '');
			updatetable('fetion', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
			$icon = 'fetion';
			$title='{actor} 已经完成了 <a href="cp.php?ac=fetion&op=setuser">飞信设置</a> ，大家去 <a href="cp.php?ac=fetion">站内飞信</a> 骚扰他（她）吧！';
			feed_add($icon, $title);
			showmessage('验证成功！','cp.php?ac=fetion&op=setuser','2');
		}
	}
}

//联系我们
if($_GET['op'] == contact){
	if(submitcheck('fetion_contactsubmit')){
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		$category = trim($_POST['category']);
		sendfetion_sms('1',$category.':'.$message.$msgend);
		showmessage('发送成功，我们收到后会及时联系您。','cp.php?ac=fetion&op=contact','2');
	}
}

include_once template("cp_fetion");

?>