<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
if(!checkperm('allowshare')) 
{
	ckspacelog();
	showmessage('no_privilege');
}
//ʵ����֤
ckrealname('share');
	
//��Ƶ��֤
ckvideophoto('share');

//���û���ϰ
cknewuser();

$type = empty($_GET['type'])?'':$_GET['type'];
$id = empty($_GET['id'])?0:intval($_GET['id']);
$note_uid = 0;
$note_message = '';
	
$hotarr = array();

$arr = array();
switch ($type) {
	case 'ND_info':
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." WHERE id='$id'");
		if(!$blog = $_SGLOBAL['db']->fetch_array($query)) {
			showmessage('��Դ�ѱ�ɾ���ˣ�');
		}
		if($blog['uid'] == $space['uid']) {
			showmessage('�����Լ������Լ�����Դ');
		}
		if($blog['publicall']>1) {
			showmessage('����Դ���޺�������');
		}
		$spaces	=	getspace($blog['uid']);
		$blog['username']	=	$spaces['username'];
		//ʵ��
		realname_set($blog['uid'], $blog['username']);
		realname_get();

		$arr['title_template'] = '������һ����Դ';
		$arr['body_template'] = '<b>{subject}</b><br>{username}<br>{message}';
		$arr['body_data'] = array(
			'subject' => "<a href=\"ND_upload.php?do=info&id=$blog[id]\">$blog[title]</a>",
			'username' => "<a href=\"space.php?uid=$blog[uid]\">".$_SN[$blog['uid']]."</a>",
			'message' => getstr($blog['jieshao'], 150, 0, 1, 0, 0, -1)
		);
			
		//֪ͨ
		$note_uid = $blog['uid'];
		$note_message = cplang('����������Դ', array("ND_upload.php?do=info&id=$blog[id]", $blog['title']));
			
		break;
}
if(submitcheck('sharesubmit')) 
{
	$_POST['topicid'] = topic_check($_POST['topicid'], 'share');
	
	if(empty($_POST['refer'])) $_POST['refer'] = "space.php?do=share&view=me";
	
	$arr['body_general'] = getstr($_POST['general'], 150, 1, 1, 1, 1);
	$arr['type'] = $type;
	$arr['uid'] = $_SGLOBAL['supe_uid'];
	$arr['username'] = $_SGLOBAL['supe_username'];
	$arr['dateline'] = $_SGLOBAL['timestamp'];
	$arr['topicid']  = $_POST['topicid'];
	$arr['body_data'] = serialize($arr['body_data']);//����ת��
		
	$setarr = saddslashes($arr);//����ת��
	$sid = inserttable('share', $setarr, 1);
	
	updatestat('share');
	if($note_uid && $note_uid != $_SGLOBAL['supe_uid']) {
		notification_add($note_uid, 'sharenotice', $note_message);
	}
	
	if(empty($space['sharenum'])) {
		$space['sharenum'] = getcount('share', array('uid'=>$space['uid']));
		$sharenumsql = "sharenum=".$space['sharenum'];
	} else {
		$sharenumsql = 'sharenum=sharenum+1';
	}
		
	//��̬
	if(ckprivacy('share', 1)) {
		include_once(S_ROOT.'./source/function_feed.php');
		feed_publish($sid, 'sid', 1);
	}
		
	if($_POST['topicid']) {
		topic_join($_POST['topicid'], $_SGLOBAL['supe_uid'], $_SGLOBAL['supe_username']);
		$url = 'space.php?do=topic&topicid='.$_POST['topicid'].'&view=share';
	} else {
		$url = $_POST['refer'];
	}

	showmessage('do_success', $url, 0);
}
$arr['body_data'] = serialize($arr['body_data']);//����ת��
$arr = mkshare($arr);
realname_get();
include_once template("ND_upload/template/share");
?>
