<?php

include_once('./common.php');


//是否关闭站点
checkclose();

//是否公开
checklogin();//需要登录

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
			showmessage("卡号不存在或密码错误", 'card.php');
		
		}
		if (time()<$card[overtime]){
			$overtime=date("Y年m月d日",$card[overtime]);
		}else{
			$overtime='已过期';
		}
}elseif ($_POST['ac'] == 'in'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card) || time()>$card[overtime]) {
			showmessage("卡号不存在或已过期", 'card.php');
		}elseif (!empty($card[carduser])){
			showmessage("卡号已被他人使用", 'card.php');
		}else{
			include_once(S_ROOT.'./source/function_cp.php');
			updatecredit($_SGLOBAL['supe_uid'], $card[money]);
			$sql = "UPDATE ".tname("app_card")." SET carduser = '".$_SGLOBAL['supe_uid']."',cardusername = '".$_SGLOBAL['supe_username']."',ztinfo = '已' WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
			$_SGLOBAL['db']->query( $sql );
			$message = "您充值了".$card[money]."积分，卡号：".$card[cardnum]."，密码：".$card[cardpsw]."！";
			notification_add($_SGLOBAL['supe_uid'], "app", $message );
$icon = 'card';
$title_template = '{actor} 通过积分充值卡向帐号充了<a href="cp.php?ac=card"><font color ="#ff0000"><b>'.$card[money].'</b></font>个积分</a>';
feed_add($icon, $title_template);
			showmessage("恭喜您成功充值$card[money] 积分", 'cp.php?ac=card');
		}
}

	include_once( template( "award/view/card" ) );
?>