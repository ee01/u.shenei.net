<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

$groupcount = getcount('tagspace', array('uid'=>$space['uid']));
if($groupcount>=3) {

        $task['done'] = 1;//����

} else {

        //��
        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=mtag" target="_blank">�´��ڴ�"����/����Ⱥ��"ҳ��</a>��</li>
                <li>2. ���´򿪵�ҳ���У������Լ������е�Ⱥ��򴴽��µ�Ⱥ�鲢�Զ����룻</li>
                <li>3. ���������ָ��������Ⱥ����ܻ�û�������֡�</li>
                </ul>';
}

?>