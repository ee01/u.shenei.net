<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: magic.php 11056 2009-02-09 01:59:47Z Micwolfzhou $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$done = 0;


//随机道具
$mid = rand(0,9);
$magic=Array(
	0 => Array(
			'mid' => 'visit',
			'name' => '互访卡'),
	1 => Array(
			'mid' => 'gift',
			'name' => '红包卡'),
	2 => Array(
			'mid' => 'color',
			'name' => '彩色灯'),
	3 => Array(
			'mid' => 'hot',
			'name' => '热点灯'),
	4 => Array(
			'mid' => 'icon',
			'name' => '彩虹蛋'),
	5 => Array(
			'mid' => 'flicker',
			'name' => '彩虹炫'),
	6 => Array(
			'mid' => 'call',
			'name' => '点名卡'),
	7 => Array(
			'mid' => 'frame',
			'name' => '相框'),
	8 => Array(
			'mid' => 'bgimage',
			'name' => '信纸'),
	9 => Array(
			'mid' => 'doodle',
			'name' => '涂鸦板')
	);

$querysql = "select * from ".tname("blog")." where uid =".$_SGLOBAL['supe_uid']." order by dateline desc limit 1";
	$query = $_SGLOBAL['db']->query($querysql);
//判断是否写了日志
      if($data = $_SGLOBAL['db']->fetch_array($query)) {
 //判断是否今天写了日志
         if (sgmdate("Y-m-d",$_SGLOBAL['timestamp'])==sgmdate("Y-m-d",$data['dateline'])) {
  	
 //判断赠送行为，防止刷赠品

$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("magicinlog")." WHERE uid='$_SGLOBAL[supe_uid]' AND (mid='visit' or mid='gift' or mid='color' or mid='hot' or mid='icon' or mid='flicker' or mid='call' or mid='frame' or mid='bgimage' or mid='doodle') AND type=2 and fromid=0 order by dateline desc limit 0,1");
if ($data = $_SGLOBAL['db']->fetch_array($query)) {
 if (sgmdate("Y-m-d",$_SGLOBAL['timestamp'])!=sgmdate("Y-m-d",$data['dateline'])) {
     
//赠送道具
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("usermagic")." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='".$magic[$mid][mid]."'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$count = $value['count'] + 1;
	} else {
		$count = 1;
	}
	$_SGLOBAL['db']->query("REPLACE ".tname('usermagic')."(uid, username, mid, count) VALUES ('$_SGLOBAL[supe_uid]', '$_SGLOBAL[username]', '".$magic[$mid][mid]."', '$count')");
//赠送日志
	inserttable('magicinlog',
		array(
			'uid'=>$_SGLOBAL['supe_uid'],
			'username'=>$_SGLOBAL['supe_username'],
			'mid'=>$magic[$mid][mid],
			'count'=>1,
			'type'=>2,
			'credit'=>0,
			'dateline'=>$_SGLOBAL['timestamp']));
}else{
//作弊
	$got = 1;
}
}
 else {

//赠送道具
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("usermagic")." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='".$magic[$mid][mid]."'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$count = $value['count'] + 1;
	} else {
		$count = 1;
	}
	$_SGLOBAL['db']->query("REPLACE ".tname('usermagic')."(uid, username, mid, count) VALUES ('$_SGLOBAL[supe_uid]', '$_SGLOBAL[username]', '".$magic[$mid][mid]."', '$count')");
//赠送日志
	inserttable('magicinlog',
		array(
			'uid'=>$_SGLOBAL['supe_uid'],
			'username'=>$_SGLOBAL['supe_username'],
			'mid'=>$magic[$mid][mid],
			'count'=>1,
			'type'=>2,
			'credit'=>0,
			'dateline'=>$_SGLOBAL['timestamp']));
}


			$task['done'] = 1;//活动完成
			if(!$got){
				$task['result'] = '<div style="padding:10px 0 5px 0;color:red;font-weight:bold;">您已成功获得了一个随机道具：<a href="cp.php?ac=magic&view=me&mid='.$magic[$mid][mid].'" target="_blank">'.$magic[$mid][name].'<br><img src="image/magic/'.$magic[$mid][mid].'.gif" alt="'.$magic[$mid][name].'" /><br>点击这里打开看看有什么用！</a></div>';
			}else{
				$task['result'] = '<div style="padding:10px 0 5px 0;color:red;font-weight:bold;">检测到你有作弊嫌疑，已经获赠过道具！明天再来吧～</div>';
			}

} else {

	//任务完成向导
	$task['guide'] = '
		<strong>请按照以下的说明来参与本任务：</strong>
		<ul>
		<li>1. <a href="cp.php?ac=blog" target="_blank">新窗口打开发表日志页面</a>；</li>
		<li>2. 在新打开的页面中，书写自己的日志，并进行发布。</li>
		</ul>';

}
}
else {

	//任务完成向导
	$task['guide'] = '
		<strong>请按照以下的说明来参与本任务：</strong>
		<ul>
		<li>1. <a href="cp.php?ac=blog" target="_blank">新窗口打开发表日志页面</a>；</li>
		<li>2. 在新打开的页面中，书写自己的日志，并进行发布。</li>
		</ul>';

}

?>