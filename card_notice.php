<?php
include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');

$uid=$_POST['uid'];
$cardnum=$_POST['cardnum'];
$cardpsw=$_POST['cardpsw'];
$money=$_POST['money'];
$overtime=$_POST['overtime'];
$liyong=$_POST['liyong'];
if($cardnum!='') {
$setarrno = array(
		'ztinfo' => '已'
	);
updatetable('app_card', $setarrno, array('cardnum' => $cardnum));
$message= "<br>积分充值卡发放通知：<br>".$liyong."<br>卡号：".$cardnum."<br>密码：".$cardpsw."<br>积分：".$money."个<br>有效期：".$overtime."<br>请在有效期内使用，点击右上角中的“设置”->><a href=cp.php?ac=card target=_blank>“积分充值卡”</a>使用！";
notification_add($uid, "app", $message );

$subject="积分充值卡发放通知";
$query = $_SGLOBAL['db']->query("SELECT email, emailcheck FROM ".tname('spacefield')." WHERE uid = $uid ");
$value = $_SGLOBAL['db']->fetch_array($query);
if($value['email']) {
smail(0, $value['email'], $subject, $message);
}
showmessage("发放积分充值卡成功！", 'admincp.php?ac=card');
}

?>