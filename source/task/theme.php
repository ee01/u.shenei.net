<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

if(!empty($space['theme'])) {

        $task['done'] = 1;//����

} else {

        //��
        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=theme" target="_blank">�´��ڴ�"��ҳ�������"ҳ��</a>��</li>
                <li>2. ���´򿪵�����ҳ���У�ѡ������ķ�����á�</li>
                </ul>';
}

?>