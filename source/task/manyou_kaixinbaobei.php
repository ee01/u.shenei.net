<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 9984 2008-11-21 08:57:24Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//���ñ�����$task['done'] (��ɱ�ʶ����) $task['result'] (�������) $task['guide'] (������)

//�ж��û��Ƿ�����˻
$done = 0;

//---------------------------------------------------
//	��д���룬�ж��û��Ƿ���ɻҪ�� $done = 1;
//---------------------------------------------------
$appid = 1029889;
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('userapp')." WHERE uid='$_SGLOBAL[supe_uid]' AND appid='$appid'"),0);

if($count) {

	$task['done'] = 1;//�������
	$task['result'] = '���Ѿ����������';//�û���������������˵����֧��html����
	
} else {

	//���������
	$task['guide'] = '
	<strong>�밴�����µ�˵�������뱾���</strong>
		<ul>
		<li>1. <a href="userapp.php?id=1029889" target="_blank">�´��ڴ򿪿��ı���ҳ��</a>��</li>
		<li>2. ���´򿪵�ҳ���У���װ���ı���������һֻ�Լ��ĳ��ﲢΪ���������</li>
		<li>3. ��ɿ��ı������Ͻǵ����񣬲������ֳ�ȥ�ʹ��һ�����~</li>
		</ul>
	'; //ָ���û���β���������˵����֧��html����

}

?>