<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}
//����b.fieldid = 1�е�1ΪȺ����ĿID, �ɸ�����Ҫ�����޸�.
if($_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('tagspace')." AS a INNER JOIN ".tname('mtag')." AS b ON a.tagid = b.tagid WHERE a.uid = ".$_SGLOBAL['supe_uid']." AND b.fieldid = 3 LIMIT 1"), 0)) {

        $task['done'] = 1;//����

} else {

        //��
        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=mtag" target="_blank">�´��ڴ�"����/����Ⱥ��"ҳ��</a>��</li>
                <li>2. ���´򿪵�ҳ���У�����ʾ����ָ������Ⱥ�鼴�ɻ�ý������֡�</li>
                </ul>';
}

?>