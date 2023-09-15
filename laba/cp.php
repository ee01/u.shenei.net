<?php
//发布
if(submitcheck('labasubmit')){
	$act = (int)$_POST['act'];
	if(!isset($gacts[$act])){
		showmessage('请务必提交正确的发布动作');
	}
	$act = $gacts[$act];
	//时间
	$dealy = (int)$_POST['dealy'];
	if($dealy<3||$dealy>10){
		showmessage('请务必提交正确的显示时间');
	}
	//计算积分
	$usemoney = $dealy * $gcost;
	
	if($space['credit']<$usemoney){
		showmessage('您暂时没有那么多金币了（'.$usemoney.'个），赚足了金币再来吧！');
	}
	
	//内容
	$content = $_POST['content'];
	if(!$content){
		showmessage('请输入您要发布的内容！');
	}
	
	$att = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'dealy' => $dealy,
		'act' => $act,
		'say' => $content,
		'dateline' => $_SGLOBAL['timestamp'],
	);
	
	$lid = inserttable('app_laba', $att, 1);
	if($lid){
		//扣积分
		updatecredit($_SGLOBAL['supe_uid'], $usemoney, $method='-');
		//
		showmessage('发布成功！你发布的内容将在一分钟内显示。');
	}
}
/*
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;


//评论
$perpage = 30;
$perpage = mob_perpage($perpage);

$start = ($page-1)*$perpage;

//检查开始数
ckstart($start, $perpage);

$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query('SELECT count(lid) FROM '.tname('app_laba').''), 0);

$list = array();
if($count){
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('app_laba').' ORDER BY dateline DESC LIMIT '.$start.','.$perpage);
	while($value = $_SGLOBAL['db']->fetch_array($query)){
		realname_set($value['uid'], $value['username']);//实名
		$list[] = $value;
	}
}

*/
//实名
realname_get();
include_once( template( "./laba/view/cp" ) );
?>