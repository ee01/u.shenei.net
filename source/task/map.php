<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


$query = $_SGLOBAL['db']->query("SELECT lat,lng,zoom FROM ".tname('spacefield')." WHERE uid='$space[uid]'");
$value = $_SGLOBAL['db']->fetch_array($query);
if($value[lat] && $value[lng] && $value[zoom]) {

        $task['done'] = 1;//����

} else {

        //������
		        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=profile&op=contact" target="_blank">�´��ڴ� ��ϵ��ʽ ����</a>��</li>
                <li>2. ��� "��������ļ����ڵ�ͼ�ϵ�λ�ðɣ���ʶ���������" ������ť��</li>
                <li>3. �ڵ�ͼ�ϰ������϶�����ļ���ĵط���</li>
                <li>4. �����´��ڴ򿪡�<a href="city.php" target="_blank">��ѧ���ǿ��</a>�����Ϳ��Կ����Լ��ͺ��ѵ�λ����!</li>
                </ul>';

}
mysql_close($link);
?>