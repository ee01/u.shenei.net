<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion')." WHERE uid='$space[uid]'");
$result = $_SGLOBAL['db']->fetch_array($query);
if($result['enable']) {

        $task['done'] = 1;//����

} else {

        //������
		        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. ���û�������ֻ�����<a href="cp.php?ac=profile&op=contact" target="_blank">�´��ڴ� ��ϵ��ʽ ����</a>��</li>
                <li>2. <a href="cp.php?ac=fetion&op=setuser" target="_blank">�´��ڴ� �����˺�����</a>��</li>
                <li>3. ��д���ķ��ŵ�½���������������֤�롱���������յ�����֤�룬�������֤����</li>
                <li>4. ���žͿ���ȥ<a href="cp.php?ac=fetion" target="_blank">վ�ڷ���</a>��ɧ�ű�������Ҳ���Ե�<a href="cp.php?ac=fetion&op=friend" target="_blank">վ�ڷ���</a>ȥ��ѷ����ֻ����Ÿ����ѡ�</li>
                </ul>';

}
mysql_close($link);
?>