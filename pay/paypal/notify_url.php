<?
include_once('./../../common.php');
$email = $_SCONFIG[pppay];
$key = $_SCONFIG[ppm];
//返回文本
$ty_text = $_POST[option_selection1];
$web_paykey = md5($_SCONFIG[web_paykey].$ty_text);
//返回MD5
$sid = $_POST[item_number];
//远程MD5
$dno = $_POST[option_selection2];
$money = intval($_POST[payment_gross]);
$oldsid = md5($email.md5($dno.$_POST[receiver_id].$money).$web_paykey);
//本地MD5
$newsid = md5($email.md5($dno.$key.$money).$web_paykey);
if ($sid == $oldsid) {

//验证签名成功
   if ($oldsid == $newsid) {
   //正常数据
   pay_update($dno,$ty_text,$money); //pay_update(订单号,返回数据,充值金额)
   } else {
   //非法数据
   print "非法数据";
   }
} else {

   print "验证签名失败";
//验证签名失败
}
?>