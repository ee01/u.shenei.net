<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}
//$mtaginvitecount>=5�е�5Ϊ�����˷�����δ�������˴����������, �ɸ�����Ҫ�޸�
$mtaginvitecount = getcount('mtaginvite', array('fromuid'=>$space['uid']));
if($mtaginvitecount>=1) {

 $task['done'] = 1;//����

} else {

 //������
 $task['guide'] = '
 <strong>�밴�����µ�˵�������뱾���</strong>
 <ul class="task">
 <li>1. <a href="space.php?do=mtag" target="_blank">�´��ڴ�"�ҵ�Ⱥ��"ҳ��</a>��</li>
 <li>2. ���´򿪵�ҳ���У���������Լ�������Ⱥ�飬������������롱���ӣ�������Ѽ��롣</li>
 <li>3. �����н��ҳ�棬�������ȡ��������ť��</li>
 <li>ע�⣺��ȡ���������ں��ѽ��ܻ��������֮ǰ������������������н��ҳ����ȡ������</li>
 </ul>';

}

?>