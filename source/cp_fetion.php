<?php
/*
		UCHome2.0 ���Ų��091031
		���ߣ�54alin@discuz	http://www.hialin.com
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

//��ȡʵ�� Add By 01
realname_set($_SGLOBAL[supe_uid],$_SGLOBAL[supe_username]);
realname_get();

$msgend=" #$_SCONFIG[sitename] ".$_SN[$_SGLOBAL['supe_uid']]." ����#";	//Modify By 01
$reward = getreward('sendfetion', 0);

$actives = empty($_GET['op'])?array('websites' => ' class="active"'):array($_GET['op'] => ' class="active"');

if($_GET[op] == help){
	if(empty($_GET['type'])) $_GET['type'] = 'ktfx';
	$sub_actives = array($_GET['type'] => ' style="font-weight:bold;"');
}else{
	$sub_actives = empty($_GET['type'])?array('send' => ' class="active"'):array($_GET['type'] => ' class="active"');
}


if(empty($_GET['op'])){
	//��ȡ�û��б�
	$friends = array();
	if($space['friendnum']) {
		$query = $_SGLOBAL['db']->query("SELECT fuid AS uid, fusername AS username FROM ".tname('friend')." WHERE uid=$_SGLOBAL[supe_uid] AND status='1' ORDER BY num DESC, dateline DESC LIMIT 0,100");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value['username'] = saddslashes($value['username']);
			$friends[] = $value;
		}
	}
}

//վ�ڷ���
if(submitcheck('fetion_sendsubmit')) {
		//�ж��Ƿ񷢲�̫��
		$waittime = interval_check('post');
		if($waittime > 0) {
			showmessage('operating_too_fast','',1,array($waittime));
		}	
		//���û���ϰ
		cknewuser();
		//������
		if($touid) {
			if(isblacklist($touid)) {
				showmessage('is_blacklist');
			}
		}
		//������Ϣ
		$username = empty($_POST['username'])?'':$_POST['username'];
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		if(empty($message)) {
			showmessage('unable_to_send_air_news');
		}
		if($touid) {
			//�Ӹ�����ҳ���ͷ���
			$fetion=get_fetion($touid);
			$compare_time=compare_time($touid);
			if(empty($compare_time)){showmessage('����ʧ�ܣ��Է������˽���ʱ�Σ�'.$fetion[set_receive_time].'������ʱ�ξܾ����ա�');}
			//������	
			if($space['credit'] < $reward['credit']) {showmessage('integral_inadequate', '', 1, array($space['credit'],  $reward['credit']));}
			
			sendfetion_sms($touid,$message.$msgend);
			getreward('sendfetion', 1, $_SGLOBAL['supe_uid']);
			showmessage('���Ķ��ŷ��ͳɹ���');
		} elseif($username) {
			//��վ�ں��ѷ��ͷ���
			$users = explode(',', $username);
			if($users[1]){showmessage('��֧��Ⱥ����', 'cp.php?ac=fetion','2');}
			$touid=getuid($users[0]);
			
			//��ȡʵ�� Add By 01
			realname_set($touid);
			realname_get();

			$fetion=get_fetion($touid);
			$compare_time=compare_time($touid);
			if(empty($compare_time)){showmessage('����ʧ�ܣ��Է������˽���ʱ�Σ�'.$fetion[set_receive_time].'������ʱ�ξܾ����ա�');}
			//������
			if($space['credit'] < $reward['credit']) {showmessage('integral_inadequate', '', 1, array($space['credit'],  $reward['credit']));}
						
			sendfetion_sms($touid,$message.$msgend);
			getreward('sendfetion', 1, $_SGLOBAL['supe_uid']);
			$title='{actor} �� <a href="cp.php?ac=fetion">վ�ڷ���</a> �������� '.$_SN[$touid].' ������һ�����š�';	//Modify By 01
			feed_add('fetion', $title);
			showmessage('���ͳɹ���', 'cp.php?ac=fetion','2');
		}
}

if($_GET['op'] == friend){
	//��ҳ
	$perpage = 10;
	$perpage = mob_perpage($perpage);
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	$mpurl .= '&perpage='.$perpage;
	if($page<1) $page=1;
	$start = ($page-1)*$perpage;
	//��鿪ʼ��
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
			//��ҳ
			$multi = multi($count, $perpage, $page, $theurl);
		}

	}

	//��ȡ�����б�
	$friends = array();
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion_friend')." WHERE uid=$_SGLOBAL[supe_uid] ORDER BY fid DESC");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value[] = saddslashes($value['name']);
			$friends[] = $value;
		}
		
	//����վ�����
	if(submitcheck('friend_sendsubmit')) {
		//���û���ϰ
		cknewuser();
		$username = empty($_POST['username'])?'':$_POST['username'];
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		$users = explode(',', $username);
		if($users[1]){showmessage('��֧��Ⱥ����', 'cp.php?ac=fetion','2');}
		
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion_friend')." WHERE uid=$_SGLOBAL[supe_uid] AND name='$users[0]'");
		$fetion_friend = $_SGLOBAL['db']->fetch_array($query);
		if(!$fetion_friend[mobileno]){showmessage('����ʧ�ܣ�������ķ��ź��Ѳ������ĺ����б��');}
		$fetion=get_fetion($_SGLOBAL['supe_uid']);
		
		sendfetion($space['mobile'],authcode($fetion['pw'],'DECODE'),$fetion_friend[mobileno],$message.$msgend);
		feed_add('fetion', '{actor} �� <a href="cp.php?ac=fetion&op=friend">վ�����</a> ������ź��ѷ�����һ�����š�');
		showmessage('���ͳɹ���', 'cp.php?ac=fetion&op=friend','2');
	}

	//��ӷ��ź���
	if(submitcheck('addsubmit')) {
		$name= trim($_POST['name']);
		$mobileno = trim($_POST['mobileno']);
		if(empty($name) || empty($mobileno))showmessage('do_success','cp.php?ac=fetion&op=friend&type=friend', 0);
		$setarr=array('uid' => $_SGLOBAL['supe_uid'],'name' => $name,'mobileno' => $mobileno,);
		$fid=inserttable('fetion_friend', $setarr, 1);
		showmessage('do_success','cp.php?ac=fetion&op=friend&type=friend', 0);
	}

	//ɾ�����ź���
	if($_GET['do'] == 'delete') {
		if(submitcheck('deletesubmit')) {
			$_SGLOBAL['db']->query("DELETE FROM ".tname('fetion_friend')." WHERE fid='$_GET[fid]'");
			showmessage('do_success', $_POST[refer], 0);
		}
	}
}

//����Ӧ������
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

//�����ʺ�����
if($_GET['op'] == setuser){
	$fetion = get_fetion($_SGLOBAL[supe_uid]);
	//������֤��
	if(submitcheck('fetion_send_code')){
		if(empty($space['mobile'])){showmessage('���� <b>��ϵ��ʽ</b> ��û����д�ֻ����뷵�����ú����ԣ�');}
		if(empty($_POST['fetion_pw'])){showmessage('���� <b>��������</b> ����Ϊ�գ�');}
		$code=substr(rand(1000000,9999999),-6);
		$str=authcode($code.'@'.$_POST['fetion_pw'].'@'.$_SGLOBAL['timestamp'],'ENCODE');
		$setarr = array('uid'=>$_SGLOBAL['supe_uid'],'authstr' => $str);
		$codemsg = "���ã����ķ�����֤��Ϊ $code ����֤��3��������Ч�������ʧЧ�������»�ȡ��֤�룡# $_SCONFIG[sitename] #";
		sendfetion($space['mobile'],$_POST['fetion_pw'],$space['mobile'],$codemsg);
		if(empty($fetion['uid'])){
			inserttable('fetion', $setarr, 1);
		}else{
			updatetable('fetion', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
		}
		showmessage('���ͳɹ�����ע������ֻ����ţ���3������δ�յ���֤���ţ������·��ͣ�','cp.php?ac=fetion&op=setuser','2');
	}
	//��֤����
	if(submitcheck('fetion_submit')){
		if(empty($space['mobile'])){showmessage('���� <b>��ϵ��ʽ</b> ��û����д�ֻ����뷵�����ú����ԣ�');}
		list($code,$pw,$dateline) = explode("@", authcode($fetion['authstr'],'DECODE'));
		if($dateline < $_SGLOBAL['timestamp']-60*3){showmessage('���������֤���ѹ���ʧЧ�������·�����֤�룡','cp.php?ac=fetion&op=setuser','2');}
		$fetion_code = trim($_POST['fetion_code']);
		if($fetion_code != $code){
			showmessage('���������֤������뷵�غ����ԣ�','cp.php?ac=fetion&op=setuser','2');
		}else{
			$setarr = array('enable' => '1','pw' => authcode($pw,'ENCODE'),'authstr' => '');
			updatetable('fetion', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
			$icon = 'fetion';
			$title='{actor} �Ѿ������ <a href="cp.php?ac=fetion&op=setuser">��������</a> �����ȥ <a href="cp.php?ac=fetion">վ�ڷ���</a> ɧ�����������ɣ�';
			feed_add($icon, $title);
			showmessage('��֤�ɹ���','cp.php?ac=fetion&op=setuser','2');
		}
	}
}

//��ϵ����
if($_GET['op'] == contact){
	if(submitcheck('fetion_contactsubmit')){
		$message = getstr($_POST['message'], 0, 1, 1, 1, 2);
		$category = trim($_POST['category']);
		sendfetion_sms('1',$category.':'.$message.$msgend);
		showmessage('���ͳɹ��������յ���ἰʱ��ϵ����','cp.php?ac=fetion&op=contact','2');
	}
}

include_once template("cp_fetion");

?>