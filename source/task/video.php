<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 11056 2009-02-09 01:59:47Z liguode $
*/
include_once(S_ROOT.'./source/function_cp.php');

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//���ñ�����$task['done'] (��ɱ�ʶ����) $task['result'] (�������) $task['guide'] (������)

//�ж��û��Ƿ����������

//---------------------------------------------------
//	��д���룬�ж��û��Ƿ��������Ҫ�� $done = 1;
//---------------------------------------------------

if($space[videostatus]) {

	$task['done'] = 1;//�������
	
} else {

	//���������
	$task['guide'] = '<strong>�밴�����µ�˵�������뱾����</strong>
		<ul>
		<li>1. <a href="cp.php?ac=videophoto" target="_blank">�´��ڴ���Ƶ��֤ҳ��</a>��</li>
		<li>2. ���´򿪵�����ҳ���У���׼��������ͷ����ʼ��Ƶ��֤�ɡ�</li>
		</ul>'; //ָ���û���β������������˵����֧��html����

}

?>