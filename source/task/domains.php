<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: profile.php 8401 2008-08-06 09:19:53Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//�ж��û��Ƿ�ȫ�������˸�������
$nones = array();
$profile_lang = array(
	'domain' => '��������',
);
foreach (array('domain') as $key) {
	$value = trim($space[$key]);
	if(empty($value)) {
		$nones[] = $profile_lang[$key];
	}
}


if(empty($nones)) {

	$task['done'] = 1;//����
	
	

} else {

	//������
	$task['guide'] = '
		<strong>����û�в���������</strong><br>
		<span style="color:red;">'.implode('<br>', $nones).'</span><br><br>
		<strong>�밴�����µ�˵������ɱ����</strong>
		<ul class="task">
		<li>1. <a href="cp.php?ac=domain" target="_blank">�´��ڴ򿪸�����������ҳ��</a>��</li>
		<li>2. ���´򿪵�����ҳ���У����ÿռ����������</li>
		</ul>';

}

?>