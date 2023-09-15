<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_ad.php 11954 2009-04-17 09:29:53Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//权限
if(!checkperm('managead')) {
	cpmessage('no_authority_management_operation');
}

if(submitcheck('attentionsubmit')) {

	$id = intval($_POST['id']);
	$_POST['title'] = getstr($_POST['title'], 80, 1, 1);
	if(empty($_POST['title'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}
	$_POST['imgurl'] = getstr($_POST['imgurl'], 80, 1, 1);
	if(empty($_POST['imgurl'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}
	$_POST['content'] =   getstr($_POST['content'], 300, 1, 1, 1, 2);
	if(empty($_POST['content'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}


	$setarr = array(
		'title' => $_POST['title'],
		'imgurl' => $_POST['imgurl'],
		'imglink' =>  getstr($_POST['imglink'], 200, 1, 1),
		'content' => $_POST['content'],
		'endtime' => sstrtotime($_POST['endtime']),
		'startime' => sstrtotime($_POST['startime']),
		'isactive' => intval($_POST['available']),
		'aorder' => intval($_POST['aorder'])
	);

	if(empty($id)) {
		$id = inserttable('attention', $setarr, 1);
	} else {
		updatetable('attention', $setarr, array('id' => $id));
	}


	cpmessage('do_success', 'admincp.php?ac=attention');

} elseif(submitcheck('delsubmit')) {

	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['adids']) && deleteattention($_POST['adids'])) {

		cpmessage('do_success', 'admincp.php?ac=attention');
	} else {
		cpmessage('please_choose_to_remove_advertisements', 'admincp.php?ac=attention');
	}

}

if(empty($_GET['op'])) {

	$sql = '';
	if($_GET['pagetype']) {
		$sql = " WHERE pagetype='$_GET[pagetype]'";
	}
	$listvalue = array();
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('attention').$sql." ORDER BY aorder DESC,id DESC");
	while($value = $_SGLOBAL['db']->fetch_array($query)) {
		$listvalue[] = $value;
	}
	
	$actives = array('view' => ' class="active"');

} elseif ($_GET['op'] == 'add' || $_GET['op'] == 'edit') {

	$_GET['id'] = empty($_GET['id'])?0:intval($_GET['id']);

	$attention = array();
	if($_GET['id']) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('attention')." WHERE id='$_GET[id]'");
		$attention = $_SGLOBAL['db']->fetch_array($query);
		//显示
		include_once(S_ROOT.'./source/function_bbcode.php');
		$attention['content'] = html2bbcode($attention['content']);
	}


	//显示处理
	$availables = array($attention['isactive'] => ' checked');

}

?>