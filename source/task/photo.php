<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}

$albumcount = getcount('album', array('uid'=>$space['uid']));
if($albumcount) {

 $task['done'] = 1;//����

} else {

 //������
 $task['guide'] = '
 <strong>�밴�����µ�˵�������뱾���</strong>
 <ul class="task">
 <li>1. <a href="cp.php?ac=upload" target="_blank">�´��ڴ�ͼƬ�ϴ�ҳ��</a>��</li>
 <li>2. ���´򿪵�ҳ���У�ѡ��Ҫ�ϴ�����Ƭ������ʼ�ϴ���</li>
 </ul>';

}

?>