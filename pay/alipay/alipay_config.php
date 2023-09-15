<?php
include_once('../../common.php');
$partner = $_SCONFIG[appay];//合作伙伴ID
//$partner = "2088102718539695";//合作伙伴ID
//$security_code = "fwd1h92hv4dh1b3w9rzcplbm59j1m6f1";//安全检验码
$security_code = $_SCONFIG[apm];//安全检验码
$seller_email = $_SCONFIG[payemail];//卖家邮箱
$_input_charset = $_SC['dbcharset']; //字符编码格式  目前支持 GBK 或 utf-8
$sign_type = "MD5"; //加密方式  系统默认(不要修改)
$transport= "https";//访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
$notify_url = $_SCONFIG[payurl].$_SCONFIG[apfile]."/notify_url.php";// 异步返回地址
$return_url = $_SCONFIG[payurl].$_SCONFIG[apfile]."/return_url.php"; //同步返回地址
$show_url   = $_SCONFIG[payurl]  //你网站商品的展示地址,可以为空

/** 提示：如何获取安全校验码和合作ID
1.访问 www.alipay.com，然后登陆您的帐户($seller_email).
2.点商家服务.

*/
?>