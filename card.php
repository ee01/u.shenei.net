<?php

include_once('./common.php');


//�Ƿ�ر�վ��
checkclose();

//�Ƿ񹫿�
checklogin();//��Ҫ��¼

function updatecredit($uid, $credit, $method='+')
{
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";
	$newcredit = $num * $credit;
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
	//$_SGLOBAL['db']->query("UPDATE ".tname('session')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}

if ($_POST['ac'] == 'check'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card)) {
			showmessage("���Ų����ڻ��������", 'card.php');
		
		}
		if (time()<$card[overtime]){
			$overtime=date("Y��m��d��",$card[overtime]);
		}else{
			$overtime='�ѹ���';
		}
}elseif ($_POST['ac'] == 'in'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card) || time()>$card[overtime]) {
			showmessage("���Ų����ڻ��ѹ���", 'card.php');
		}elseif (!empty($card[carduser])){
			showmessage("�����ѱ�����ʹ��", 'card.php');
		}else{
			include_once(S_ROOT.'./source/function_cp.php');
			updatecredit($_SGLOBAL['supe_uid'], $card[money]);
			$sql = "UPDATE ".tname("app_card")." SET carduser = '".$_SGLOBAL['supe_uid']."',cardusername = '".$_SGLOBAL['supe_username']."',ztinfo = '��' WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
			$_SGLOBAL['db']->query( $sql );
			$message = "����ֵ��".$card[money]."���֣����ţ�".$card[cardnum]."�����룺".$card[cardpsw]."��";
			notification_add($_SGLOBAL['supe_uid'], "app", $message );
$icon = 'card';
$title_template = '{actor} ͨ�����ֳ�ֵ�����ʺų���<a href="cp.php?ac=card"><font color ="#ff0000"><b>'.$card[money].'</b></font>������</a>';
feed_add($icon, $title_template);
			showmessage("��ϲ���ɹ���ֵ$card[money] ����", 'cp.php?ac=card');
		}
}

	include_once( template( "award/view/card" ) );
?>