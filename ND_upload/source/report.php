<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$_GET['idtype'] = trim($_GET['idtype']);
$_GET['id'] = intval($_GET['id']);
$uidarr = $report = array();
if(!in_array($_GET['idtype'], array('ND_info')) || empty($_GET['id'])) {
	showmessage('report_error');
}
//获取举报记录
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_report')." WHERE id='$_GET[id]' AND idtype='$_GET[idtype]'");
if($report = $_SGLOBAL['db']->fetch_array($query)) {
	$uidarr = unserialize($report['uids']);
	if($uidarr[$space['uid']]) {
		showmessage('repeat_report');
	}
}

if(submitcheck('reportsubmit')) {
	$reason = getstr($_POST['reason'], 150, 1, 1);
	$reason = "<li><strong><a href=\"space.php?uid=$space[uid]\" target=\"_blank\">$_SGLOBAL[supe_username]</a>:</strong> ".$reason.' ('.sgmdate('m-d H:i').')</li>';
	if($report) {
		$uidarr[$space['uid']] = $space['username'];
		$uids = addslashes(serialize($uidarr));
		$reason = addslashes($report['reason']).$reason;
		$_SGLOBAL['db']->query("UPDATE ".tname('nd_report')." SET num=num+1, reason='$reason', dateline='$_SGLOBAL[timestamp]', uids='$uids' WHERE rid='$report[rid]'");
	} else {
		$uidarr[$space['uid']] = $space['username'];
		$setarr = array(
			'id' => $_GET['id'],
			'idtype' => $_GET['idtype'],
			'num' => 1,
			'new' => 1,
			'reason' => $reason,
			'uids' => addslashes(serialize($uidarr)),
			'dateline' => $_SGLOBAL['timestamp']
		);
		inserttable('nd_report', $setarr);
		showmessage('report_success');
	}
}
if(isset($report['num']) && $report['num'] < 1) {
	showmessage('the_normal_information');
}

$reason = explode("\r\n", trim(preg_replace("/(\s*(\r\n|\n\r|\n|\r)\s*)/", "\r\n", data_get('reason'))));
if(is_array($reason) && count($reason) == 1 && empty($reason[0])) {
	$reason = array();
}
include_once template("ND_upload/template/report");
?>
