<?php

include_once('../../common.php');
/*
 * @Description 易宝支付神州行卡支付标准版接口范例 
 * @V3.0
 * @Author rui.xin
 */

#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
//$p1_MerId			= "10000432521"	;																										#测试使用

$p1_MerId = trim($_SCONFIG[clpay]);
																										
//$merchantKey	= "8UPp0KE8sq73zVP370vko7C39403rtK1YwX40Td6irH216036H27Eb12792t";		#测试使用
$merchantKey = trim($_SCONFIG[clm]);	
//exit;	
#	神州行卡支付标准版正式请求地址,无需更改
$reqURL_SZXstd = "https://www.yeepay.com/app-merchant-proxy/node";
#	神州行卡支付标准版测试请求地址
//$reqURL_SZXstd = "http://tech.yeepay.com:8080/robot/szxStdDebug.action";
# 业务类型
##在线支付请求，固定值"Buy" .	
$p0_Cmd = "Buy";
#	送货地址
##为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为"0".
$p9_SAF = "0";
#	银行编码
##神州行支付标准版，固定值"SZX".			
$pd_FrpId					= "SZX";
?> 