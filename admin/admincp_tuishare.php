<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_tuishare.php 11954 2010-01-03 09:29:53Z ooidea-fareign $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//权限
if(!checkperm('managead')) {
	cpmessage('no_authority_management_operation');
}

if(submitcheck('tuisubmit')) {

	$id = intval($_POST['id']);
	$_POST['name'] = getstr($_POST['name'], 80, 1, 1);
	if(empty($_POST['name'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}
	$_POST['icon'] = getstr($_POST['icon'], 80, 1, 1);
	if(empty($_POST['icon'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}
	$_POST['appurl'] =   getstr($_POST['appurl'], 255, 1, 1, 1, 1);
	if(empty($_POST['appurl'])) {
		cpmessage('please_check_whether_the_option_complete_required');
	}


	$setarr = array(
		'name' => $_POST['name'],
		'icon' => $_POST['icon'],
		'appurl' =>  $_POST['appurl'],
		'type' => intval($_POST['type']),
		'sorder' => intval($_POST['sorder'])
	);

	if(empty($id)) {
		$id = inserttable('tuishare', $setarr, 1);
	} else {
		updatetable('tuishare', $setarr, array('tuid' => $id));
	}


	cpmessage('do_success', 'admincp.php?ac=tuishare');

} elseif(submitcheck('delsubmit')) {

	include_once(S_ROOT.'./source/function_delete.php');
	if(!empty($_POST['adids']) && deletetuishare($_POST['adids'])) {

		cpmessage('do_success', 'admincp.php?ac=tuishare');
	} else {
		cpmessage('please_choose_to_remove_advertisements', 'admincp.php?ac=tuishare');
	}

}

if(empty($_GET['op'])) {

	$sql = '';
	if($_GET['pagetype']) {
		$sql = " WHERE pagetype='$_GET[pagetype]'";
	}
	$listvalue = array();
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('tuishare').$sql." ORDER BY sorder DESC,tuid DESC");
	while($value = $_SGLOBAL['db']->fetch_array($query)) {
		$listvalue[] = $value;
	}
	
	$actives = array('view' => ' class="active"');

} elseif ($_GET['op'] == 'add' || $_GET['op'] == 'edit') {

	$_GET['id'] = empty($_GET['id'])?0:intval($_GET['id']);

	$tuishare = array();
	if($_GET['id']) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('tuishare')." WHERE tuid='$_GET[id]'");
		$tuishare = $_SGLOBAL['db']->fetch_array($query);

	}


	//显示处理
	$type = array($tuishare['type'] => ' checked');

}

?>