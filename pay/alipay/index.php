<?php
/*
	*功能：设置商品有关信息及物流信息
	*版本：2.0
	*日期：2008-08-01
	*作者：支付宝公司销售部技术支持团队
	*联系：0571-26888888
	*版权：支付宝公司
*/

require_once("alipay_service.php");
require_once("alipay_config.php");
			   $varr = get_vipday(intval($_POST[cvip]));//返数组0为天数,1为金额
$p3_Amt						= (intval($_POST[paym]) > 0) ? intval($_POST[paym]) : intval($varr[1]);									
$pa_MP						= (intval($_POST[paym]) > 0) ? "1:".$_SGLOBAL['supe_uid'].":3" : "2:".$_SGLOBAL['supe_uid'].":".$_POST[cvip].":3";
$p5_Pid						= (intval($_POST[paym]) > 0) ? "积分充值" : "购买VIP";	
$parameter = array(
	"service"        => "create_partner_trade_by_buyer",  //交易类型
	"partner"        => $partner,         //合作商户号
	"return_url"     => $return_url,      //同步返回
	"notify_url"     => $notify_url,      //异步返回
	"_input_charset" => $_input_charset,  //字符集，默认为GBK
	"subject"        => $p5_Pid,       //商品名称，必填
	"body"           => $pa_MP,       //商品描述，必填
	"out_trade_no"   => date(Ymdhms),     //商品外部交易号，必填（保证唯一性）
	"price"          => $p3_Amt,           //商品单价，必填（价格不能为0）
	"payment_type"   => "1",              //默认为1,不需要修改
	"quantity"       => "1",              //商品数量，必填
		
	"logistics_fee"      =>'0.00',        //物流配送费用
	"logistics_payment"  =>'BUYER_PAY',   //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
	"logistics_type"     =>'EXPRESS',     //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

	"show_url"       => $show_url,        //商品相关网站
	"seller_email"   => $seller_email     //卖家邮箱，必填
);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
$link=$alipay->create_url();
if (intval($_POST[paym]) > 0 or $_POST[cvip] != '') {
	showmessage('', $link,0);	
	}
?>

