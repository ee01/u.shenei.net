<?php
/*
   �����װ˵���ļ�
   2010.1.4
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$taskid_ext = intval( $_GET['taskid'] );
$done = 0;

$query_task_xinnian = $_SGLOBAL['db']->query('SELECT * FROM '.tname('task_xinnian')." WHERE taskid='$taskid_ext' AND uid='".$_SGLOBAL['supe_uid']."'");
$task_xinnian = $_SGLOBAL['db']->fetch_array($query_task_xinnian);
if( $task_xinnian['count']>9 ) {
	$done = 1;
}
if( empty($task_xinnian['count']) ) {
	$task_xinnian['count'] = 0;
	$task_xinnian['view'] = 0;
}

$task_xinnian_url = 'http://2010.shenei.net/?u=' . $_SGLOBAL['supe_uid'];

if($done) {

	$task['done'] = 1;
	$task['result'] = '<p style="color:red;">�ۣ��������������Ѿ�����������������ѳɹ�����' . $task_xinnian['count'] . '������ף���ˣ�����ף���������' . $task_xinnian['view'] . '�Ρ�����Ȼ���Լ���ʹ��������ף��ҳ�棬������������ȥ����ֿ������ף���ɡ�</p>
					<br />&nbsp;<a onclick="javascript:setCopy(\'' . $task_xinnian_url . '\r\nף��������֣�\');return false;" href="' . $task_xinnian_url . '"><b style="font-size:14px;">' . $task_xinnian_url . '</b></a>';

} else {

	if( $task_xinnian['count']>0 ) {
		$task['guide'] .= '<p style="color:red;">�ۣ��������������ѳɹ�����<font size=4 class=bold>' . $task_xinnian['count'] . '</font>����������ף���ˣ�ף���������<font size=4 class=bold>' . $task_xinnian['view'] . '</font>�Ρ�<br>����Ŭ��Ŷ��</p><br>';
	}
//	$task_xinnian_url = getsiteurl() . 'do.php?ac=task&amp;taskid=' . $taskid_ext . '&amp;u=' . $_SGLOBAL['supe_uid'];
	$task['guide'] .= '<strong>�밴�����µ�˵������ɱ�����</strong>
		<div class="task">
		��������ͨ��QQ��MSN�����������칤�ߣ����߷��͵����ʼ��������������ף��ҳ�����ӷ���������ˡ�ͬѧ����ѣ���ȥ����ϵ�����ף����
		<br />&nbsp;<a onclick="javascript:setCopy(\'' . $task_xinnian_url . '\r\nף��������֣�\');return false;" href="' . $task_xinnian_url . '"><b style="font-size:14px;">' . $task_xinnian_url . '</b></a> </li>
		<br />&nbsp;��������ӣ���Ϊ���Զ��������ӵ�ճ���壬�ɹ���ֱ��Ctrl+Vճ���Ϳ����ˣ�
		<br />������Ҫ�ɹ����͸�10�����Ѳ�����ɡ�
		<br />���������֮������Ȼ���Լ���ʹ����ף��ҳ����������ѷ����������ף����
		</div>';
}

?>