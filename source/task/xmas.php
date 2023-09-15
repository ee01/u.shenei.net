<?php
/*
   详见安装说明文件
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
	$task['result'] = '<p style="color:red;">哇，你真厉害，你已经完成了任务。您现在已成功发送' . $task_xinnian['count'] . '份新年祝福了，新年祝福共被浏览' . $task_xinnian['view'] . '次。你仍然可以继续使用新年送祝福页面，给其他好友送去你真挚的新年祝福吧。</p>
					<br />&nbsp;<a onclick="javascript:setCopy(\'' . $task_xinnian_url . '\r\n祝你新年快乐！\');return false;" href="' . $task_xinnian_url . '"><b style="font-size:14px;">' . $task_xinnian_url . '</b></a>';

} else {

	if( $task_xinnian['count']>0 ) {
		$task['guide'] .= '<p style="color:red;">哇，厉害，您现在已成功发送<font size=4 class=bold>' . $task_xinnian['count'] . '</font>份新年新年祝福了，祝福共被浏览<font size=4 class=bold>' . $task_xinnian['view'] . '</font>次。<br>继续努力哦！</p><br>';
	}
//	$task_xinnian_url = getsiteurl() . 'do.php?ac=task&amp;taskid=' . $taskid_ext . '&amp;u=' . $_SGLOBAL['supe_uid'];
	$task['guide'] .= '<strong>请按照以下的说明来完成本任务：</strong>
		<div class="task">
		◎您可以通过QQ、MSN、旺旺等聊天工具，或者发送电子邮件，把下面的新年祝福页面链接发给你的亲人、同学或好友，送去你真诚的新年祝福。
		<br />&nbsp;<a onclick="javascript:setCopy(\'' . $task_xinnian_url . '\r\n祝你新年快乐！\');return false;" href="' . $task_xinnian_url . '"><b style="font-size:14px;">' . $task_xinnian_url . '</b></a> </li>
		<br />&nbsp;（点击链接，可为您自动复制链接到粘贴板，成功后直接Ctrl+V粘贴就可以了）
		<br />◎您需要成功发送给10个好友才算完成。
		<br />◎完成任务之后，你仍然可以继续使用送祝福页面给其他好友发送你的新年祝福。
		</div>';
}

?>