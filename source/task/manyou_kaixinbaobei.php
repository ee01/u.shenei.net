<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 9984 2008-11-21 08:57:24Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//内置变量：$task['done'] (完成标识变量) $task['result'] (结果文字) $task['guide'] (向导文字)

//判断用户是否参与了活动
$done = 0;

//---------------------------------------------------
//	编写代码，判读用户是否完成活动要求 $done = 1;
//---------------------------------------------------
$appid = 1029889;
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('userapp')." WHERE uid='$_SGLOBAL[supe_uid]' AND appid='$appid'"),0);

if($count) {

	$task['done'] = 1;//任务完成
	$task['result'] = '你已经完成了这个活动';//用户参与活动看到的文字说明。支持html代码
	
} else {

	//任务完成向导
	$task['guide'] = '
	<strong>请按照以下的说明来参与本活动：</strong>
		<ul>
		<li>1. <a href="userapp.php?id=1029889" target="_blank">新窗口打开开心宝贝页面</a>；</li>
		<li>2. 在新打开的页面中，安装开心宝贝，领养一只自己的宠物并为它设计形象！</li>
		<li>3. 完成开心宝贝左上角的任务，并到游乐场去和大家一起玩吧~</li>
		</ul>
	'; //指导用户如何参与活动的文字说明。支持html代码

}

?>