<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_cp.php?ac=card 10953 2009-01-12 02:55:37Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

function updatecredit($uid, $credit, $method='+')
{
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";	
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}
if ($_POST['rbinfo'] == 'check'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card)) {
			showmessage("���Ų����ڻ��������", 'cp.php?ac=card');
		
		}
		if (time()<$card[overtime]){
			$overtime=date("Y��m��d��",$card[overtime]);
		}else{
			$overtime='�ѹ���';
		}
}elseif ($_POST['rbinfo'] == 'in'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card) || time()>$card[overtime]) {
			showmessage("���Ų����ڻ��ѹ���", 'cp.php?ac=card');
		}elseif (!empty($card[carduser])){
			showmessage("�����ѱ�����ʹ��", 'cp.php?ac=card');
		}else{
			include_once(S_ROOT.'./source/function_cp.php');
			updatecredit($_SGLOBAL['supe_uid'], $card[money]);
			$sql = "UPDATE ".tname("app_card")." SET carduser = '".$_SGLOBAL['supe_uid']."',cardusername = '".$_SGLOBAL['supe_username']."' WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
			$_SGLOBAL['db']->query( $sql );
			$message = "����ֵ��".$card[money]."���֣����ţ�".$card[cardnum]."�����룺".$card[cardpsw]."��";
			notification_add($_SGLOBAL['supe_uid'], "app", $message );
$icon = 'card';
$title_template = '{actor} ͨ�����ֳ�ֵ�����ʺų���<a href="cp.php?ac=card"><font color ="#ff0000"><b>'.$card[money].'</b></font>������</a>';
feed_add($icon, $title_template);
			showmessage("��ϲ���ɹ���ֵ$card[money] ����", 'cp.php?ac=card');
		}
}


include_once template("cp_card");

?>