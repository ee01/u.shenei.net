<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}

$threadcount = getcount('thread', array('uid'=>$space['uid']));
if($threadcount) {

 $task['done'] = 1;//����

} else {

 //������
 $task['guide'] = '
 <strong>�밴�����µ�˵�������뱾���</strong>
 <ul class="task">
 <li>1. <a href="cp.php?ac=thread" target="_blank">�´��ڴ򿪷���Ⱥ���»���ҳ��</a>��</li>
 <li>2. ���´򿪵�ҳ���У���д�Լ��ĵ�һ�����⣬�����з�����</li>
 </ul>';

}

?>