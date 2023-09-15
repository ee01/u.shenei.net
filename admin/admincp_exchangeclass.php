<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_blog.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//Ȩ��
if(!checkperm('managecache')) {
	cpmessage('no_authority_management_operation');
}

if(submitcheck('exchangeclasssubmit')) {

	$classid = intval($_POST['classid']);
	$_POST['classname'] = getstr($_POST['classname'], 50, 1, 1);
	if(empty($_POST['classname'])) {
		$_POST['classname'] = 'AD'.sgmdate('ndHis');
	}
	
	$setarr = array(
		'classname' => $_POST['classname']
	);

	if(empty($classid)) {
		$classid = inserttable('exchange_class', $setarr, 1);
	} else {
		updatetable('exchange_class', $setarr, array('classid' => $classid));
	}
	//д��ģ��
	$tpl = S_ROOT.'./data/adtpl/'.$classid.'.htm';
	swritefile($tpl, $html);

	//�������
	include_once(S_ROOT.'./source/function_cache.php');
	ad_cache();

	cpmessage('do_success', 'admincp.php?ac=exchangeclass');

} elseif(submitcheck('delsubmit')) {

	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['classids']) && deleteexchangeclasss($_POST['classids'])) {

		//�������
		include_once(S_ROOT.'./source/function_cache.php');
		ad_cache();

		cpmessage('do_success', 'admincp.php?ac=exchangeclass');
	} else {
		cpmessage('please_choose_to_remove_advertisements', 'admincp.php?ac=exchangeclass');
	}

}

if(empty($_GET['op'])) {

if(submitcheck('deletesubmit')) {
	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['classids']) && deleteexchangeclasss($_POST['classids'])) {
		cpmessage('do_success', $_POST['mpurl']);
	} else {
		cpmessage('the_correct_choice_to_delete_the_log', $_POST['mpurl']);
	}
}

$mpurl = 'admincp.php?ac=exchangeclass';

//��������
$intkeys = array('classid');
$strkeys = array('classname');
$results = getwheres($intkeys, $strkeys);
$wherearr = $results['wherearr'];
$mpurl .= '&'.implode('&', $results['urls']);

$wheresql = empty($wherearr)?'1':implode(' AND ', $wherearr);

//����
$orderby = array($_GET['orderby']=>' selected');
$ordersc = array($_GET['ordersc']=>' selected');

$perpage = empty($_GET['perpage'])?0:intval($_GET['perpage']);
if(!in_array($perpage, array(20,50,100,1000))) $perpage = 20;

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page = 1;
$start = ($page-1)*$perpage;
//��鿪ʼ��
ckstart($start, $perpage);

//��ʾ��ҳ
if($perpage > 100) {
	$count = 1;
	$selectsql = 'classid';
} else {
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('exchange_class')." WHERE $wheresql"), 0);
	$selectsql = '*';
}
$mpurl .= '&perpage='.$perpage;
$perpages = array($perpage => ' selected');

$list = array();
$multi = '';

if($count) {
	$query = $_SGLOBAL['db']->query("SELECT $selectsql FROM ".tname('exchange_class')." WHERE $wheresql $ordersql LIMIT $start,$perpage");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$multi = multi($count, $perpage, $page, $mpurl);
}

//��ʾ��ҳ
if($perpage > 100) {
	$count = count($list);
}

} elseif ($_GET['op'] == 'add' || $_GET['op'] == 'edit') {

	$_GET['classid'] = empty($_GET['classid'])?0:intval($_GET['classid']);

	$advalue = array();
	if($_GET['classid']) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('exchange_class')." WHERE classid='$_GET[classid]'");
		$advalue = $_SGLOBAL['db']->fetch_array($query);
	}

	//��ʾ����
	$availables = array($advalue['available'] => ' checked');

}
?>