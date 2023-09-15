<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_userapp.php 8390 2008-08-06 05:50:42Z liguode $
*/


include_once('../../common.php');
//是否关闭站点
checkclose();
checklogin();
if (intval($_POST[paym]) > 0 or $_POST[cvip] != '') {
require_once("md5_request.php");
			   $varr = get_vipday(intval($_POST[cvip]));//返数组0为天数,1为金额
$paymey = (intval($_POST[paym]) > 0) ? intval($_POST[paym]) : intval($varr[1]);
$pname = (intval($_POST[paym]) > 0) ? "积分充值" : "购买VIP";
$ptxt = (intval($_POST[paym]) > 0) ? "1:".$_SGLOBAL['supe_uid'].":1" : "2:".$_SGLOBAL['supe_uid'].":".$_POST[cvip].":1";//1积分充值 2购买VIP
$paymm = $paymey*100;
$dno = time();
$tenpay = new tenpay_online_payment();
$url = $tenpay->tenpay_interface_pay ("0",$pname,"fjyxian@qq.com",$dno,$paymm,$ptxt);
	
showmessage('', $url,0);		
}
?>