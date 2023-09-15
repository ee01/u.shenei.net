<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: blog.php 11056 2009-02-09 01:59:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$blogcount = getcount('poll', array('uid'=>$space['uid']));
if($blogcount) {

	$task['done'] = 1;//任务完成

} else {

	//任务完成向导
	$task['guide'] = '
		<strong>请按照以下的说明来参与本任务：</strong>
		<ul>
		<li>1. <a href="cp.php?ac=poll" target="_blank">新窗口打开发表投票页面</a>；</li>
		<li>2. 在新打开的页面中，发表一份新投票，成功后获得积分奖励。</li>
		</ul>';

}

?>