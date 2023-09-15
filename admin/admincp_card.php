<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_blog.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//权限
if(!checkperm('managecache')) {
	cpmessage('no_authority_management_operation');
}
        $oneweek=time()+24*60*60*7;
	$onemonth=time()+24*60*60*30;
	$oneyear=time()+24*60*60*365;
	$monthfive=time()+24*60*60*150;
	$money=100;
	$cardnum=rand(1234567890,9876543210);
	$cardpsw=rand(1234509876,9876501234);

if(submitcheck('cardsubmit')) {

	$id = intval($_POST['id']);
	$_POST['cardnum'] = getstr($_POST['cardnum'], 50, 1, 1);
	if(empty($_POST['cardnum'])) {
		$_POST['cardnum'] = 'AD'.sgmdate('ndHis');
	}
	
	$setarr = array(
		'cardnum' => $_POST['cardnum'],
		'cardpsw' => $_POST['cardpsw'],
		'money' => $_POST['money'],
		'ztinfo' => $_POST['ztinfo'],
		'overtime' => $_POST['overtime']
	);

	if(empty($id)) {
		$id = inserttable('app_card', $setarr, 1);
	} else {
		updatetable('app_card', $setarr, array('id' => $id));
	}
	//写入模板
	$tpl = S_ROOT.'./data/adtpl/'.$id.'.htm';
	swritefile($tpl, $html);

	//缓存更新
	include_once(S_ROOT.'./source/function_cache.php');
	ad_cache();

	cpmessage('do_success', 'admincp.php?ac=card');

} elseif(submitcheck('delsubmit')) {

	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['ids']) && deletecards($_POST['ids'])) {

		//缓存更新
		include_once(S_ROOT.'./source/function_cache.php');
		ad_cache();

		cpmessage('do_success', 'admincp.php?ac=card');
	} else {
		cpmessage('please_choose_to_remove_advertisements', 'admincp.php?ac=card');
	}

}
if($_GET['op']=='clno') {
$id = intval($_GET['id']);
$setarrno = array(
		'ztinfo' => '已'
	);
updatetable('app_card', $setarrno, array('id' => $id));
cpmessage('do_success', 'admincp.php?ac=card');
}

if($_GET['op']=='clyes') {
$id = intval($_GET['id']);
$setarryes = array(
		'ztinfo' => '未'
	);
updatetable('app_card', $setarryes, array('id' => $id));
cpmessage('do_success', 'admincp.php?ac=card');
}
if(empty($_GET['op'])) {

if(submitcheck('deletesubmit')) {
	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['ids']) && deletecards($_POST['ids'])) {
		cpmessage('do_success', $_POST['mpurl']);
	} else {
		cpmessage('the_correct_choice_to_delete_the_log', $_POST['mpurl']);
	}
}

$mpurl = 'admincp.php?ac=card';

//处理搜索
$intkeys = array('id');
$strkeys = array('cardnum');
$results = getwheres($intkeys, $strkeys);
$wherearr = $results['wherearr'];
$mpurl .= '&'.implode('&', $results['urls']);

$wheresql = empty($wherearr)?'1':implode(' AND ', $wherearr);

//排序
$orderby = array($_GET['orderby']=>' selected');
$ordersc = array($_GET['ordersc']=>' selected');

$perpage = empty($_GET['perpage'])?0:intval($_GET['perpage']);
if(!in_array($perpage, array(20,50,100,1000))) $perpage = 20;

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page = 1;
$start = ($page-1)*$perpage;
//检查开始数
ckstart($start, $perpage);

//显示分页
if($perpage > 100) {
	$count = 1;
	$selectsql = 'id';
} else {
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_card')." WHERE $wheresql"), 0);
	$selectsql = '*';
}
$mpurl .= '&perpage='.$perpage;
$perpages = array($perpage => ' selected');

$list = array();
$multi = '';

if($count) {
	$query = $_SGLOBAL['db']->query("SELECT $selectsql FROM ".tname('app_card')." WHERE $wheresql $ordersql LIMIT $start,$perpage");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username'], $value['username'], 1);
		$value = mkfeed($value);
		$list[] = $value;
	}
	$multi = multi($count, $perpage, $page, $mpurl);
}

//显示分页
if($perpage > 100) {
	$count = count($list);
}

} elseif ($_GET['op'] == 'add' || $_GET['op'] == 'edit') {

	$_GET['id'] = empty($_GET['id'])?0:intval($_GET['id']);

	$advalue = array();
	if($_GET['id']) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('app_card')." WHERE id='$_GET[id]'");
		$advalue = $_SGLOBAL['db']->fetch_array($query);
	}

	//显示处理
	$availables = array($advalue['available'] => ' checked');

}
?>