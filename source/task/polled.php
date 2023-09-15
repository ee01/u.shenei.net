<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: blog.php 11056 2009-02-09 01:59:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$done = 0;
	$querysql = "select * from ".tname('polluser')." where ".$_SGLOBAL['supe_uid']." in (uid)";
	$query = $_SGLOBAL['db']->query($querysql);
	if($data = $_SGLOBAL['db']->fetch_array($query)){
	$task['done'] = 1;//活动完成

} else {

	//任务完成向导
	$task['guide'] = '
		<strong>请按照以下的说明来参与本任务：</strong>
		<ul>
		<li>1. <a href="space.php?do=poll" target="_blank">点击这里，在新窗口打开投票页面</a>；</li>
		<li>2. 在新打开的页面中，选择一个你感兴趣的投票，投出自己的一票。</li>
		</ul>';

}

?>