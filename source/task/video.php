<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 11056 2009-02-09 01:59:47Z liguode $
*/
include_once(S_ROOT.'./source/function_cp.php');

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//内置变量：$task['done'] (完成标识变量) $task['result'] (结果文字) $task['guide'] (向导文字)

//判断用户是否完成了任务

//---------------------------------------------------
//	编写代码，判读用户是否完成任务要求 $done = 1;
//---------------------------------------------------

if($space[videostatus]) {

	$task['done'] = 1;//任务完成
	
} else {

	//任务完成向导
	$task['guide'] = '<strong>请按照以下的说明来参与本任务：</strong>
		<ul>
		<li>1. <a href="cp.php?ac=videophoto" target="_blank">新窗口打开视频认证页面</a>；</li>
		<li>2. 在新打开的设置页面中，请准备好摄像头，开始视频认证吧。</li>
		</ul>'; //指导用户如何参与任务的文字说明。支持html代码

}

?>