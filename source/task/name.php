<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: renzheng.php 8401 2008-08-12 15:12:55Z PopLong $
*/



if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//�ж��Ƿ�ͨ��ʵ����֤

if($space[namestatus] == 1) {

	$task['done'] = 1;//����
	
	

} else {

	//������
	$task['guide'] = '
		<strong>����û�в���������</strong><br>
		<span style="color:red;">'.implode('<br>', $nones).'</span><br><br>
		<strong>�밴�����µ�˵������ɱ����</strong>
		<ul class="task">
		<li>1. <a href="cp.php?ac=profile" target="_blank">�´��ڴ�����ҳ��</a>��</li>
		<li>2. ���´򿪵�����ҳ���У����������ʵ�������ϴ���ʵ��ƬΪͷ�񣬵ȴ�����Ա���ͨ������ɡ�</li>
		</ul>';

}

?>