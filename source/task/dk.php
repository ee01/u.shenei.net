<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 11056 2009-02-09 01:59:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//内置变量：$task['done'] (完成标识变量) $task['result'] (结果文字) $task['guide'] (向导文字)

//判断用户是否完成了任务

if(sgmdate('H')<9 ||(sgmdate('H')==9 && sgmdate('i')<30))
{
	$task['credit'] = $task['credit']*2;
}
$done = 1;

if($done) {

	$task['done'] = 1;//任务完成
	//$task['result'] = '21世纪怎么挣钱最容易？来舍内打卡领工资啊。每天09：30之前打卡，工资翻番哦！！';//用户参与任务看到的文字说明。支持html代码
	$task['result'] = '<p>给您送上一份 《热门日志导读》 看看吧：</p>';
	$task['result'] .= '<br><ul class="line_list">';

	$bloglist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('blog')." WHERE hot>='3' AND friend='0' ORDER BY dateline DESC LIMIT 0,20");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$bloglist[] = $value;
	}
	realname_get();
	
	foreach ($bloglist as $value) {
		$task['result'] .= "<li><a href=\"space.php?uid=$value[uid]\" target=\"_blank\"><strong>".$_SN[$value['uid']]."</strong></a>：<a href=\"space.php?uid=$value[uid]&do=blog&id=$value[blogid]\" target=\"_blank\">$value[subject]</a> <span class=\"gray\">($value[hot]人推荐)</span></li>";
	}
	$task['result'] .= '</ul>';
} else {

	//任务完成向导
	$task['guide'] = '21世纪怎么挣钱最容易？来舍内打卡领工资啊。每天09：30之前打卡，工资翻番哦！！'; //指导用户如何参与任务的文字说明。支持html代码

}

?>