<?php
//����
if(submitcheck('labasubmit')){
	$act = (int)$_POST['act'];
	if(!isset($gacts[$act])){
		showmessage('������ύ��ȷ�ķ�������');
	}
	$act = $gacts[$act];
	//ʱ��
	$dealy = (int)$_POST['dealy'];
	if($dealy<3||$dealy>10){
		showmessage('������ύ��ȷ����ʾʱ��');
	}
	//�������
	$usemoney = $dealy * $gcost;
	
	if($space['credit']<$usemoney){
		showmessage('����ʱû����ô�����ˣ�'.$usemoney.'������׬���˽�������ɣ�');
	}
	
	//����
	$content = $_POST['content'];
	if(!$content){
		showmessage('��������Ҫ���������ݣ�');
	}
	
	$att = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'dealy' => $dealy,
		'act' => $act,
		'say' => $content,
		'dateline' => $_SGLOBAL['timestamp'],
	);
	
	$lid = inserttable('app_laba', $att, 1);
	if($lid){
		//�ۻ���
		updatecredit($_SGLOBAL['supe_uid'], $usemoney, $method='-');
		//
		showmessage('�����ɹ����㷢�������ݽ���һ��������ʾ��');
	}
}
/*
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;


//����
$perpage = 30;
$perpage = mob_perpage($perpage);

$start = ($page-1)*$perpage;

//��鿪ʼ��
ckstart($start, $perpage);

$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query('SELECT count(lid) FROM '.tname('app_laba').''), 0);

$list = array();
if($count){
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('app_laba').' ORDER BY dateline DESC LIMIT '.$start.','.$perpage);
	while($value = $_SGLOBAL['db']->fetch_array($query)){
		realname_set($value['uid'], $value['username']);//ʵ��
		$list[] = $value;
	}
}

*/
//ʵ��
realname_get();
include_once( template( "./laba/view/cp" ) );
?>