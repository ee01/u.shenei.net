<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: blog.php 11056 2009-02-09 01:59:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$done = 0;
	$querysql = "select * from ".tname('polluser')." where ".$_SGLOBAL['supe_uid']." in (uid)";
	$query = $_SGLOBAL['db']->query($querysql);
	if($data = $_SGLOBAL['db']->fetch_array($query)){
	$task['done'] = 1;//����

} else {

	//���������
	$task['guide'] = '
		<strong>�밴�����µ�˵�������뱾����</strong>
		<ul>
		<li>1. <a href="space.php?do=poll" target="_blank">���������´��ڴ�ͶƱҳ��</a>��</li>
		<li>2. ���´򿪵�ҳ���У�ѡ��һ�������Ȥ��ͶƱ��Ͷ���Լ���һƱ��</li>
		</ul>';

}

?>