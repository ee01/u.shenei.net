<?php
$op = $_GET['op'];
if (empty($op)) {
	showmessage('δ����ķ���');
}

if ('post' == $op) {	
	$title = trim($_POST['title']);
	if (empty($title) || empty($_POST['typeid']) || empty($_POST['content'])) {
		showmessage("��¼��Ϣδ�������룬����...");
	}
	$post_ana_id = intval($_POST['ana_id']);

	//�޸�
	if ($post_ana_id > 0) {
		$data = array(
			'title' => shtmlspecialchars($title),
			'content' => trim($_POST['content']),
			'typeid' =>  intval($_POST['typeid'])
		 );
		 updatetable("ana", $data, "id = {$post_ana_id}");
		 showmessage("�޸ĳɹ���", "ana.php?do=ana&ac=view&id={$post_ana_id}");
	}
	
	$score = 0;
	getmember();
	if ($_SGLOBAL['member']['credit'] < $score) {
		showmessage("�Բ������Ļ��ֲ�����");
	}

	$data = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'title' => shtmlspecialchars($title),
		'content' => trim($_POST['content']),
		'typeid' =>  intval($_POST['typeid']),
		'score' => $score,
		'dateline' => $_SGLOBAL['timestamp'],
		'status' => 1
	 );

	$ana_id = inserttable('ana', $data, 1);

	//�¼�feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} �������µ���¼ <strong>{subject}</strong>";
	
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$title}</a>",
		'score' => $score
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);

	updatecredit($_SGLOBAL['supe_uid'], $score, '-');
	showmessage("���Ѿ��ɹ�����һ������¼���Ѿ������Ļ����п۳�{$score}�֡�Ҳ����2099���ĳһ�죬������¼����Ӱ�������ǵ����", "ana.php?do=ana&ac=view&id={$ana_id}");

}
elseif ('reply' == $op )
{
	$ana_id = intval($_POST['ana_id']);
	$ana_title = trim($_POST['ana_title']);
	$ana_uid = intval($_POST['ana_uid']);
	if (empty($ana_id)) {
		showmessage("��������");
	}
	$content = trim($_POST['content']);
	if (empty($content) ) {
		showmessage("�ظ����ݲ���Ϊ��");
	}

	$data = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'content' => trim($_POST['content']),
		'ana_id' => $ana_id,
		'dateline' => $_SGLOBAL['timestamp']
	 );
	$reply_id = inserttable('ana_reply', $data, 1);
	
	//���»ظ���
	$sql = "UPDATE ".tname("ana")." SET reply_count = reply_count + 1 WHERE id = ".$ana_id;
	$_SGLOBAL['db']->query( $sql );
	
	if ($ana_uid !=  $_SGLOBAL['supe_uid']) {
		//֪ͨ
		include_once(S_ROOT.'./source/function_cp.php');
		$message = "�ظ�������������¼ <a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana_title}</a>";
	    notification_add($ana_uid, "app", $message );
	}
	
    
    //�¼�feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} �ظ�����¼ <strong>{subject}</strong>";
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana_title}</a>"
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);
	
    
	showmessage("�ظ��ɹ�", $theurl, 1);
}
elseif ('finish' == $op)
{
	$ana_id = intval($_POST['ana_id']);
	$theurl = trim($_POST['theurl']);
	
	//��ȡ��¼
	$sql = "SELECT * FROM ".tname("ana")." WHERE id= $ana_id AND uid = ".$_SGLOBAL['supe_uid'] ;
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana)) {
		showmessage("��¼�����ڻ����Ѿ���ɾ��", 'ana.php?do=ana');
	}
	if (2 == $ana['status']) {
		showmessage("����¼�Ѿ��������벻Ҫ�ظ��ύ��");
	}
	
	foreach ($pscore as $key => $val) {
		$sql = "UPDATE ".tname('ana_reply')." SET score = ".intval($val)." WHERE id =".$key;
		$_SGLOBAL['db']->query($sql);
	}

	$sql = "SELECT * FROM ".tname('ana_reply')." WHERE ana_id = $ana_id ";
	$query = $_SGLOBAL['db']->query($sql);
	$list = array( );
	while ( $value = $_SGLOBAL['db']->fetch_array( $query ) )
	{
		$list[$value['uid']]['fen'] = intval($list[$value['uid']]['fen']) +  $value['score'];
		$list[$value['uid']]['username'] = $value['username'];
	}
	
	$arr_fedd_str = "";
	include_once(S_ROOT.'./source/function_cp.php');
	foreach ($list as $key => $val) {
		if (empty($val['fen'])) {
			continue;
		}
		updatecredit($key, $val['fen'], '+');
		
		$arr_fedd_str[] =  "<a href='space.php?uid={$key}'>{$val['username']}</a>: {$val['fen']}�� ";
		
		$message = "�� <a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana['content']}</a> ���˼������������ {$val['fen']} �֡�";
    	notification_add($key, "app", $message );
	}
	
	$sql = "UPDATE ".tname("ana")." SET status='2', msg='". addslashes(implode("��", $arr_fedd_str))."' WHERE id = $ana_id ";
	$_SGLOBAL['db']->query($sql);
	
	
	//�¼�feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} ��������¼ <strong>{subject}</strong> �����������". implode("��", $arr_fedd_str);
	
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana['title']}</a>"
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);
	
	
	showmessage("��ϲ����������¼�ɹ���", $theurl);
}
elseif ('delete' == $op)
{
	$ana_id = intval($_GET['id']);
	if (empty($ana_id)) {
		showmessage("��������");
	}
	$sql = " select * from ".tname('ana')." where id = ".$ana_id." ";
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana))
	{
		showmessage("��¼�����ڻ����Ѿ���ɾ��", 'ana.php?do=ana');
	}
	if ( $_SGLOBAL['supe_uid'] != $ana['uid'] &&  $_SGLOBAL['supe_uid'] != ADMIN_ID ) {
		showmessage("��û��ɾ������¼��Ȩ��", 'ana.php?do=ana');
	}

	$sql = "DELETE FROM ".tname('ana')." WHERE id = {$ana_id} ";
	$query = $_SGLOBAL['db']->query($sql);
	$sql = "DELETE FROM ".tname('ana_reply')." WHERE ana_id = {$ana_id} ";
	$query = $_SGLOBAL['db']->query($sql);
	showmessage("ɾ���ɹ���", "ana.php?do=ana", 0);
}
elseif ('replydelete' == $op)
{
	$ana_id = intval($_GET['ana_id']);
	$id = intval($_GET['id']);
	if (empty($ana_id) || empty($id)) {
		showmessage("��������");
	}

	$sql = " select * from ".tname('ana_reply')." where id = ".$id." ";
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana))
	{
		showmessage("��¼�����ڻ����Ѿ���ɾ��", 'ana.php?do=ana');
	}
	if ( $_SGLOBAL['supe_uid'] != $ana['uid'] &&  $_SGLOBAL['supe_uid'] != ADMIN_ID ) {
		showmessage("��û��ɾ������¼��Ȩ��", 'ana.php?do=ana');
	}

	$sql = "DELETE FROM ".tname('ana_reply')." WHERE id = {$id} ";
	$query = $_SGLOBAL['db']->query($sql);
	showmessage("ɾ���ɹ���", "ana.php?do=ana&ac=view&id={$ana_id}");
}